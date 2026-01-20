<?php

use App\Http\Controllers\ProfileController;
use App\Models\Spot;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Página de bienvenida (Pública)
Route::get('/', function () {
    return view('welcome');
});

// 2. Redirigir el dashboard al explorador
Route::get('/dashboard', function () {
    return redirect()->route('explorar');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. Tu explorador personalizado (Frontend inyectado)
Route::get('/explorar', function () {
    return view('explorar');
})->middleware(['auth'])->name('explorar');

// --- NUEVA RUTA API (Dentro de web para compartir la sesión) ---
// Esta es la ruta que tu script.js llamará: fetch('/api/spots')
Route::middleware('auth')->get('/api/spots', function () {
    // Retorna tus spots con sus relaciones (Municipio y Etiquetas)
    return Spot::with(['municipio', 'etiquetas'])->get();
});

// 4. Gestión del Perfil (Rutas estándar de Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 5. RUTAS DE AUTENTICACIÓN
require __DIR__ . '/auth.php';