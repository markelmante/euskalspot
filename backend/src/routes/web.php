<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExplorerController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SpotController; // Asegúrate de que este controller existe

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // --- DASHBOARD & PLANES ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/planes', [PlanController::class, 'store'])->name('planes.store');
    Route::delete('/planes/{id}', [PlanController::class, 'destroy'])->name('planes.destroy');

    // --- EXPLORADOR (Mapa general) ---
    Route::get('/explorar', [ExplorerController::class, 'index'])->name('explorer');

    // --- SPOTS (Ficha de detalle) ---
    // Esta es la vista nueva con Tabs que vamos a crear
    Route::get('/spots/{id}', [SpotController::class, 'show'])->name('spots.show');

    // --- FAVORITOS ---
    // Usamos {id} para recibir el número directamente desde Ajax.
    Route::post('/favoritos/toggle/{id}', [SpotController::class, 'toggleFavorite'])->name('favoritos.toggle');

    // (Opcional) Si quieres borrar desde el perfil, podrías necesitar esta ruta:
    // Route::delete('/favoritos/{id}', [SpotController::class, 'removeFavorite'])->name('favoritos.remove');

    // --- PERFIL ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';