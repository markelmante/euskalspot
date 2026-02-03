<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpotController extends Controller
{
    public function show($id)
    {
        // Cargamos el spot con Municipio y Etiquetas
        $spot = Spot::with(['municipio', 'etiquetas'])->findOrFail($id);

        // Comprobamos si el usuario ya le dio like (si está logueado)
        $isFavorite = false;
        if (Auth::check()) {
            $isFavorite = Auth::user()->spotsFavoritos()->where('spot_id', $id)->exists();
        }

        return view('spots.show', compact('spot', 'isFavorite'));
    }

    public function toggleFavorite($id)
    {
        $user = Auth::user();
        $acciones = $user->spotsFavoritos()->toggle($id);
        $status = count($acciones['attached']) > 0 ? 'added' : 'removed';
        return response()->json(['status' => $status]);
    }
}