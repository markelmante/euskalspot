<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use App\Models\Etiqueta;
use App\Models\Favorito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExplorerController extends Controller
{
    public function index()
    {
        $spots = Spot::with(['municipio', 'etiquetas'])->get();
        $etiquetas = Etiqueta::all();
        $favoritosIds = Auth::user()->favoritos()->pluck('spot_id')->toArray();

        return view('explorer', compact('spots', 'etiquetas', 'favoritosIds'));
    }

    // Usado en el mapa para marcar/desmarcar
    public function toggleFavorite($id)
    {
        $user = Auth::user();
        $favorito = $user->favoritos()->where('spot_id', $id)->first();

        if ($favorito) {
            $favorito->delete();
            $status = 'removed';
        } else {
            Favorito::create(['user_id' => $user->id, 'spot_id' => $id]);
            $status = 'added';
        }

        return response()->json(['status' => $status]);
    }

    public function removeFavorite($id)
    {
        Auth::user()->favoritos()->where('spot_id', $id)->delete();

        return response()->json(['success' => true]);
    }
}