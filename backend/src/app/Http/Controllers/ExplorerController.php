<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use App\Models\Etiqueta;
use Illuminate\Http\Request;

class ExplorerController extends Controller
{
    public function index()
    {
        // Cargamos los spots con su municipio y etiquetas para usarlos en el mapa
        // Usamos 'get' para pasarlos todos al frontend
        $spots = Spot::with(['municipio', 'etiquetas'])->get();

        // También pasamos las etiquetas para poder filtrar
        $etiquetas = Etiqueta::all();

        return view('explorer', compact('spots', 'etiquetas'));
    }
}