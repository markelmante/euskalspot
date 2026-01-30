<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Municipio; // Asegúrate de tener este modelo
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Muestra el formulario de perfil.
     */
    public function edit(Request $request): View
    {
        // Intentamos cargar municipios si el modelo existe, si no, array vacío para evitar error
        $municipios = class_exists(Municipio::class) ? Municipio::orderBy('nombre')->get() : [];

        return view('profile.edit', [
            'user' => $request->user()->load('profile'), // Cargamos la relación
            'municipios' => $municipios,
        ]);
    }

    /**
     * Actualiza la información del perfil (User + Profile).
     */
    public function update(Request $request): RedirectResponse
    {
        // 1. Validar datos conjuntos
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($request->user()->id)],
            // Si usas select: 'exists:municipios,nombre'. Si usas input texto: 'string'.
            'municipio_residencia' => ['nullable', 'string', 'max:255'],
            'preferencia' => ['required', 'in:surf,monte,ambos'],
        ]);

        $user = $request->user();

        // 2. Actualizar User
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // 3. Actualizar Profile (Tabla profiles)
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'municipio_residencia' => $validated['municipio_residencia'],
                'preferencia' => $validated['preferencia'],
            ]
        );

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Borrar cuenta.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}