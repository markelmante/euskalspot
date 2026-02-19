<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;

class AdminMunicipioController extends Controller
{
    /**
     * Listado de municipios con buscador
     */
    public function index(Request $request)
    {
        $query = Municipio::query();

        // 1. FILTRAR: Solo municipios que tengan al menos 1 spot
        $query->has('spots');

        // 2. BUSCADOR: Si el usuario busca algo, filtramos por nombre
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nombre', 'LIKE', "%{$search}%");
        }

        // 3. CARGA: Traemos el conteo de spots para mostrarlo en la tabla
        $municipios = $query->withCount('spots')
            ->orderBy('nombre', 'asc')
            ->paginate(10);

        return view('admin.municipios.index', compact('municipios'));
    }
    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('admin.municipios.create');
    }

    /**
     * Guardar nuevo municipio
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:municipios,nombre',
        ], [
            'nombre.unique' => 'Este municipio ya existe en la base de datos.'
        ]);

        Municipio::create($request->all());

        return redirect()->route('admin.municipios.index')
            ->with('success', '¡Municipio añadido correctamente!');
    }

    /**
     * Formulario de edición
     */
    public function edit(Municipio $municipio)
    {
        return view('admin.municipios.edit', compact('municipio'));
    }

    /**
     * Actualizar municipio
     */
    public function update(Request $request, Municipio $municipio)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:municipios,nombre,' . $municipio->id,
        ]);

        $municipio->update($request->all());

        return redirect()->route('admin.municipios.index')
            ->with('success', 'Municipio actualizado con éxito.');
    }

    /**
     * Eliminar municipio
     */
    public function destroy(Municipio $municipio)
    {
        // Verificación de seguridad: ¿Tiene spots asociados?
        if ($municipio->spots()->count() > 0) {
            return redirect()->route('admin.municipios.index')
                ->with('error', 'No se puede eliminar el municipio porque tiene spots asociados. Elimina primero los spots.');
        }

        $municipio->delete();

        return redirect()->route('admin.municipios.index')
            ->with('success', 'Municipio eliminado correctamente.');
    }
}