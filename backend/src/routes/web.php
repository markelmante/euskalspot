<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExplorerController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\MunicipioController; // IMPORTANTE: Agregado para Entidad 1
use App\Http\Controllers\EtiquetaController;  // IMPORTANTE: Agregado para Entidad 3 (si ya lo creaste)

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- PÁGINA PÚBLICA (LANDING) ---
// Requisito A: Landing page con registro/login y reseñas.
Route::get('/', function () {
    return view('welcome');
});

// --- RUTAS PROTEGIDAS (APLICACIÓN WEB) ---
Route::middleware(['auth', 'verified'])->group(function () {

    // --- DASHBOARD & PLANES (Gestión Personal) ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/planes', [PlanController::class, 'store'])->name('planes.store');
    Route::delete('/planes/{id}', [PlanController::class, 'destroy'])->name('planes.destroy');

    // --- EXPLORADOR GLOBAL (Mapa general) ---
    Route::get('/explorar', [ExplorerController::class, 'index'])->name('explorer');

    // --- MUNICIPIOS ---
    // Listado y buscador de municipios
    Route::get('/municipios', [MunicipioController::class, 'index'])->name('municipios.index');
    // Detalle del municipio
    Route::get('/municipios/{id}', [MunicipioController::class, 'show'])->name('municipios.show');

    // --- SPOTS ---
    Route::get('/spots/{id}', [SpotController::class, 'show'])->name('spots.show');

    // --- FAVORITOS ---
    Route::post('/favoritos/toggle/{id}', [SpotController::class, 'toggleFavorite'])->name('favoritos.toggle');

    // --- PERFIL (Requisito B.1) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';