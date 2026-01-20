<?php

use App\Models\Spot;
use App\Models\Favorito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Cambiamos sanctum por auth para usar la sesión de Breeze
Route::middleware('auth')->group(function () {

    // Obtener todos los spots
    Route::get('/spots', function () {
        return Spot::with(['municipio', 'etiquetas'])->get();
    });

    // Añadir favorito
    Route::post('/favoritos', function (Request $request) {
        $request->validate(['spot_id' => 'required|exists:spots,id']);

        $favorito = Favorito::firstOrCreate([
            'user_id' => $request->user()->id,
            'spot_id' => $request->spot_id
        ]);

        return response()->json(['message' => 'Añadido a favoritos', 'data' => $favorito]);
    });
});