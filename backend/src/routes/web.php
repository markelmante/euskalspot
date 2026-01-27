<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExplorerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Página de bienvenida (Pública - Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// 2. DASHBOARD / PLANIFICADOR (Página principal al loguearse)
// Antes redirigía a explorar, ahora carga la vista 'dashboard' (Tu agenda)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. EXPLORADOR (Mapa y Buscador)
Route::get('/explorar', function () {
    return view('explorar');
})->middleware(['auth', 'verified'])->name('explorar');

// 4. PERFIL DE USUARIO
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/explorar', [ExplorerController::class, 'index'])->name('explorer');
// 5. RUTAS DE AUTENTICACIÓN (Login, Register, etc.)
require __DIR__ . '/auth.php';