<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSpotController;
use App\Http\Controllers\AdminMunicipioController; 
use App\Http\Controllers\AdminUserController;

// --- LANDING PAGE ---
Route::get('/', function () {
    return view('welcome');
});

// --- RUTAS PROTEGIDAS ---
Route::middleware(['auth', 'verified'])->group(function () {

    // --- AGENDA PLANES (DASHBOARD) ---
    Route::get('/dashboard', [PlanController::class, 'index'])->name('dashboard');

    // Rutas de acciones de planes
    Route::post('/planes', [PlanController::class, 'store'])->name('planes.store');
    Route::patch('/planes/{id}', [PlanController::class, 'update'])->name('planes.update');
    Route::delete('/planes/{id}', [PlanController::class, 'destroy'])->name('planes.destroy');

    // --- EXPLORADOR GLOBAL ---
    Route::get('/explorar', [SpotController::class, 'index'])->name('spots.index');

    // --- MUNICIPIOS ---
    Route::get('/municipios', [MunicipioController::class, 'index'])->name('municipios.index');
    Route::get('/municipios/{id}', [MunicipioController::class, 'show'])->name('municipios.show');

    // --- SPOTS ---
    Route::get('/spots/{id}', [SpotController::class, 'show'])->name('spots.show');

    // --- FAVORITOS ---
    Route::post('/favoritos/toggle/{id}', [FavoriteController::class, 'toggle'])->name('favoritos.toggle');

    // --- PERFIL ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- RUTAS DE ADMINISTRADOR ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Panel principal: /admin/panel
    Route::get('/panel', [AdminController::class, 'index'])->name('panel');

    // --- GESTIÓN DE SPOTS ---
    Route::get('/spots', [AdminSpotController::class, 'index'])->name('spots.index');
    Route::get('/spots/crear', [AdminSpotController::class, 'create'])->name('spots.create');
    Route::post('/spots', [AdminSpotController::class, 'store'])->name('spots.store');
    Route::get('/spots/{spot}/editar', [AdminSpotController::class, 'edit'])->name('spots.edit');
    Route::put('/spots/{spot}', [AdminSpotController::class, 'update'])->name('spots.update');
    Route::delete('/spots/{spot}', [AdminSpotController::class, 'destroy'])->name('spots.destroy');

    // --- GESTIÓN DE MUNICIPIOS  ---
    Route::get('/municipios', [AdminMunicipioController::class, 'index'])->name('municipios.index');
    Route::get('/municipios/crear', [AdminMunicipioController::class, 'create'])->name('municipios.create');
    Route::post('/municipios', [AdminMunicipioController::class, 'store'])->name('municipios.store');
    Route::get('/municipios/{municipio}/editar', [AdminMunicipioController::class, 'edit'])->name('municipios.edit');
    Route::put('/municipios/{municipio}', [AdminMunicipioController::class, 'update'])->name('municipios.update');
    Route::delete('/municipios/{municipio}', [AdminMunicipioController::class, 'destroy'])->name('municipios.destroy');

    // --- GESTIÓN DE USUARIOS ---
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/editar', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    // Ruta para el video tutorial
    Route::view('/tutorial', 'admin.tutorial')->name('tutorial');
});

require __DIR__ . '/auth.php';