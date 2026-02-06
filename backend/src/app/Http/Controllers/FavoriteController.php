<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // =========================================================================
    // OPCIÓN A: MÉTODO "TOGGLE" (Interruptor)
    // Ideal para botones de "Me gusta" / "Corazón" en el explorador.
    // Si ya lo tienes, lo quita. Si no lo tienes, lo añade.
    // =========================================================================

    public function toggle($spot_id)
    {
        $user = Auth::user();

        // La función toggle() revisa la tabla pivote 'favoritos'.
        // Devuelve un array indicando qué pasó ('attached' o 'detached').
        $acciones = $user->spotsFavoritos()->toggle($spot_id);

        // Verificamos si se añadió o se quitó para avisar al frontend
        $status = count($acciones['attached']) > 0 ? 'added' : 'removed';

        return response()->json([
            'success' => true,
            'status' => $status,
            'message' => $status === 'added' ? 'Añadido a favoritos' : 'Eliminado de favoritos'
        ]);
    }

    // =========================================================================
    // OPCIÓN B: CRUD ESTÁNDAR (Store y Destroy explícitos)
    // Ideal para situaciones donde quieres control total.
    // Ejemplo: El botón de basura en tu Dashboard (solo debe borrar).
    // =========================================================================

    /**
     * Store: Fuerza la creación del favorito.
     * Útil si tienes un botón que solo dice "Guardar".
     */
    public function store(Request $request)
    {
        // Validamos que venga el ID del spot
        $request->validate([
            'spot_id' => 'required|exists:spots,id'
        ]);

        $user = Auth::user();

        // syncWithoutDetaching evita duplicados: si ya existe, no hace nada.
        // O puedes usar ->attach() si controlas el error de duplicados.
        $user->spotsFavoritos()->syncWithoutDetaching([$request->spot_id]);

        return response()->json(['success' => true, 'message' => 'Guardado correctamente']);
    }

    /**
     * Destroy: Fuerza la eliminación del favorito.
     * Útil para el botón "X" o "Papelera" de tu Dashboard.
     */
    public function destroy($spot_id)
    {
        $user = Auth::user();

        // detach() borra la relación en la tabla pivote 'favoritos'
        $borrado = $user->spotsFavoritos()->detach($spot_id);

        if ($borrado > 0) {
            return response()->json(['success' => true, 'message' => 'Eliminado correctamente']);
        }

        return response()->json(['success' => false, 'message' => 'No se encontró el favorito'], 404);
    }
}