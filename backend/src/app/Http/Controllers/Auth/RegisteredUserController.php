<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\Municipio;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        // 1. Obtenemos las Provincias únicas para el primer desplegable
        $provincias = Municipio::select('provincia')->distinct()->pluck('provincia');

        // 2. Obtenemos todos los municipios (necesitamos la provincia para filtrar en JS)
        $municipios = Municipio::select('id', 'nombre', 'provincia')->orderBy('nombre', 'asc')->get();

        return view('auth.register', compact('municipios', 'provincias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'municipio' => ['required', 'string', 'max:100'], // Ahora es obligatorio elegir uno válido
            'preferencia' => ['nullable', 'string', 'in:surf,monte,ambos'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Profile::create([
            'user_id' => $user->id,
            'municipio_residencia' => $request->municipio,
            'preferencia' => $request->preferencia ?? 'ambos',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}