<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    /**
     *
     * 
     */
    public function index()
    {
        $municipios = Municipio::has('spots')
            ->with('spots')
            ->withCount('spots')
            ->get();

        return view('municipios.index', compact('municipios'));
    }

    /**
     * Muestra el detalle de de municipio junto con sus spots.
     */
    public function show($id)
    {
        // Buscamos el municipio y cargamos sus spots 
        $municipio = Municipio::with('spots')->findOrFail($id);

        return view('municipios.show', compact('municipio'));
    }
}