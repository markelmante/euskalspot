@extends('layouts.auth')

@section('title', 'Iniciar Sesión')

@section('content')
    <main class="auth-section" style="width: 100%;">
        <div class="auth-card" style="margin: 0 auto;">
            <div class="text-center">
                <h1>Bienvenido de nuevo</h1>
                <p>Entra para gestionar tus planes de aventura.</p>
            </div>

            @if (session('status'))
                <div role="status" aria-live="polite"
                    style="background: #dcfce7; color: #166534; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="tu@email.com" autocomplete="email"
                        @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    @error('email')
                        <span id="email-error" class="error-msg" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••" autocomplete="current-password"
                        @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                    @error('password')
                        <span id="password-error" class="error-msg" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-options">
                    <label for="remember" style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                        <input type="checkbox" id="remember" name="remember" style="width:auto; margin:0;">
                        <span>Recordarme</span>
                    </label>
                </div>

                <button type="submit" class="btn-cta-auth">Iniciar Sesión</button>
            </form>

            <div class="auth-footer">
                ¿No tienes cuenta? <a href="{{ route('register') }}">Crea una ahora</a>
            </div>
        </div>
    </main>
@endsection