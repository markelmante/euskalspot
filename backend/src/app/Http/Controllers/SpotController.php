<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use App\Models\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpotController extends Controller
{
    public function index()
    {
        $spots = Spot::with(['municipio', 'etiquetas'])->get();
        $etiquetas = Etiqueta::all();

        $favoritosIds = Auth::check()
            ? Auth::user()->favoritos()->pluck('spot_id')->toArray()
            : [];

        return view('explorer', compact('spots', 'etiquetas', 'favoritosIds'));
    }

    public function show($id)
    {
        $spot = Spot::with(['municipio', 'etiquetas'])->findOrFail($id);
        $isFavorite = false;
        if (Auth::check()) {
            $isFavorite = Auth::user()->spotsFavoritos()->where('spot_id', $id)->exists();
        }

        return view('spots.show', compact('spot', 'isFavorite'));
    }

}