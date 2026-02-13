<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use App\Models\Municipio;
use App\Models\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSpotController extends Controller
{
    /**
     * Listado de spots con buscador
     */
    public function index(Request $request)
    {
        $query = Spot::with(['municipio', 'etiquetas']);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('nombre', 'like', '%' . $searchTerm . '%')
                ->orWhereHas('municipio', function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', '%' . $searchTerm . '%');
                });
        }

        $spots = $query->paginate(15);
        return view('admin.spots.index', compact('spots'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $municipios = Municipio::orderBy('nombre')->get();
        $etiquetas = Etiqueta::orderBy('nombre')->get();
        return view('admin.spots.create', compact('municipios', 'etiquetas'));
    }

    /**
     * Guardar nuevo spot
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'municipio_id' => 'required|exists:municipios,id',
            'tipo' => 'required|in:playa,monte',
            'descripcion' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'etiquetas' => 'nullable|array',
            'etiquetas.*' => 'exists:etiquetas,id',
            'fotos' => 'required|array|min:3|max:3',
            'fotos.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->except(['fotos', 'etiquetas']);

        if ($request->hasFile('fotos')) {
            $paths = [];
            foreach ($request->file('fotos') as $foto) {
                $paths[] = $foto->store('spots', 'public');
            }
            $data['foto'] = $paths; // El cast del modelo se encarga del JSON
        }

        $spot = Spot::create($data);

        if ($request->has('etiquetas')) {
            $spot->etiquetas()->sync($request->etiquetas);
        }

        return redirect()->route('admin.spots.index')->with('success', '¡Spot creado con éxito!');
    }

    /**
     * Formulario de edición
     */
    public function edit(Spot $spot)
    {
        $municipios = Municipio::orderBy('nombre')->get();
        $etiquetas = Etiqueta::orderBy('nombre')->get();
        $spotEtiquetasIds = $spot->etiquetas->pluck('id')->toArray();

        return view('admin.spots.edit', compact('spot', 'municipios', 'etiquetas', 'spotEtiquetasIds'));
    }

    /**
     * Actualizar spot existente y limpiar fotos antiguas
     */
    public function update(Request $request, Spot $spot)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'municipio_id' => 'required|exists:municipios,id',
            'tipo' => 'required|in:playa,monte',
            'descripcion' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'etiquetas' => 'nullable|array',
            'etiquetas.*' => 'exists:etiquetas,id',
            'fotos' => 'nullable|array|min:3|max:3',
            'fotos.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->except(['fotos', 'etiquetas']);

        // Si se suben fotos nuevas, borramos TODAS las anteriores del disco
        if ($request->hasFile('fotos')) {

            // Verificación de seguridad para convertir el campo foto en array antes del loop
            $fotosViejas = is_array($spot->foto) ? $spot->foto : json_decode($spot->foto, true);

            if ($fotosViejas && is_array($fotosViejas)) {
                foreach ($fotosViejas as $oldPath) {
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
            }

            $paths = [];
            foreach ($request->file('fotos') as $foto) {
                $paths[] = $foto->store('spots', 'public');
            }
            $data['foto'] = $paths;
        }

        $spot->update($data);
        $spot->etiquetas()->sync($request->etiquetas ?? []);

        return redirect()->route('admin.spots.index')->with('success', '¡Spot actualizado correctamente!');
    }

    /**
     * Eliminar spot y sus archivos físicos
     */
    public function destroy(Spot $spot)
    {
        // 1. Obtener las rutas de las fotos (manejando array o JSON)
        $fotos = is_array($spot->foto) ? $spot->foto : json_decode($spot->foto, true);

        // 2. Eliminar cada archivo del disco físico
        if ($fotos && is_array($fotos)) {
            foreach ($fotos as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        // 3. Limpiar relaciones en la tabla pivot y borrar registro
        $spot->etiquetas()->detach();
        $spot->delete();

        return redirect()->route('admin.spots.index')->with('success', 'Spot y sus imágenes eliminados correctamente.');
    }
}