<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // Si ya lo tienes, lo quita. Si no lo tienes, lo añade.


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
}