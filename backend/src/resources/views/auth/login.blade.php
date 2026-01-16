@extends('layouts.app')

@section('title', 'Iniciar Sesión')
@section('body-class', 'auth-page')

@section('content')
    <section class="auth-section">
        <div class="auth-card">
            <h2>Bienvenido de nuevo</h2>
            <p>Entra para gestionar tus planes de aventura.</p>

            @if (session('status'))
                <div style="color: #15803D; margin-bottom: 15px; font-size: 0.9rem;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="tu@email.com">
                    @error('email')
                        <span class="error-msg"
                            style="color:#dc2626; font-size:0.8rem; font-weight: 600; margin-top: 5px; display: block;">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                    @error('password')
                        <span class="error-msg"
                            style="color:#dc2626; font-size:0.8rem; font-weight: 600; margin-top: 5px; display: block;">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-options">
                    <label style="display:flex; align-items:center; gap:5px; cursor:pointer; color: #64748b;">
                        <input type="checkbox" name="remember"> Recordarme
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            style="color:var(--azul); text-decoration:none; font-weight:600;">¿Olvidaste tu contraseña?</a>
                    @endif
                </div>

                <button type="submit" class="btn-cta-auth">Iniciar Sesión</button>
            </form>

            <p class="auth-footer" style="margin-top:20px; color:#64748b;">
                ¿No tienes cuenta? <a href="{{ route('register') }}"
                    style="color:var(--verde); font-weight:bold; text-decoration:none;">Crea una ahora</a>
            </p>
        </div>
    </section>
@endsection