<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View; // Importante para la vista

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     * (ESTA ES LA FUNCIÓN QUE TE FALTABA)
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        // 1. Validamos los datos
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'municipio' => ['nullable', 'string', 'max:100'],
            'preferencia' => ['nullable', 'string', 'in:surf,monte,ambos'],
        ]);

        // 2. Creamos el Usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Creamos el Perfil asociado
        Profile::create([
            'user_id' => $user->id,
            // Mapeo: Base de datos <= Formulario
            'municipio_residencia' => $request->municipio,
            'preferencia' => $request->preferencia,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}