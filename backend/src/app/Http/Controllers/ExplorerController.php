<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use App\Models\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExplorerController extends Controller
{
    public function index()
    {
        // Cargamos spots con sus relaciones para el mapa y las tarjetas
        $spots = Spot::with(['municipio', 'etiquetas'])->get();
        $etiquetas = Etiqueta::all();

        // Obtenemos solo los IDs de los favoritos para saber cuáles pintar de rojo en el mapa
        // Usamos un array vacío si el usuario no está logueado
        $favoritosIds = Auth::check()
            ? Auth::user()->favoritos()->pluck('spot_id')->toArray()
            : [];

        return view('explorer', compact('spots', 'etiquetas', 'favoritosIds'));
    }
}