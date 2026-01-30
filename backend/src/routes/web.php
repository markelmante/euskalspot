<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExplorerController;
use App\Http\Controllers\PlanController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // DASHBOARD & PLANES
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/planes', [PlanController::class, 'store'])->name('planes.store');
    Route::delete('/planes/{id}', [PlanController::class, 'destroy'])->name('planes.destroy');

    // EXPLORADOR
    Route::get('/explorar', [ExplorerController::class, 'index'])->name('explorer');

    // RUTAS FAVORITOS
    // Toggle (Añadir/Quitar desde el mapa)
    Route::post('/favoritos/toggle/{spot}', [ExplorerController::class, 'toggleFavorite'])->name('favoritos.toggle');
    // Remove
    Route::delete('/favoritos/{spot}', [ExplorerController::class, 'removeFavorite'])->name('favoritos.remove');

    // PERFIL
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';