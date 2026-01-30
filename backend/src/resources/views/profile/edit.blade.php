@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">

    <div class="profile-container">

        {{-- HEADER --}}
        <div class="profile-header">
            <div class="profile-avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="profile-title">
                <h1>Hola, {{ explode(' ', $user->name)[0] }}</h1>
                <p>Aquí tienes el control de tu cuenta y preferencias.</p>
            </div>
        </div>

        <div class="profile-grid">

            {{-- COLUMNA IZQUIERDA: INFORMACIÓN PERSONAL --}}
            <div class="column-left">
                <div class="profile-card">
                    <div class="card-header">
                        <h2>👤 Datos Personales</h2>
                        <p>Actualiza tu información de contacto y ubicación.</p>
                    </div>

                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="form-group">
                            <label class="form-label" for="name">Nombre Completo</label>
                            <input class="form-input" type="text" name="name" id="name"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name') <span class="error-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Correo Electrónico</label>
                            <input class="form-input" type="email" name="email" id="email"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email') <span class="error-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="municipio">Municipio</label>
                            @if(isset($municipios) && count($municipios) > 0)
                                <select class="form-input" name="municipio_residencia" id="municipio">
                                    <option value="">Selecciona tu ciudad...</option>
                                    @foreach($municipios as $muni)
                                        <option value="{{ $muni->nombre }}" {{ (old('municipio_residencia', $user->profile?->municipio_residencia) == $muni->nombre) ? 'selected' : '' }}>
                                            {{ $muni->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input class="form-input" type="text" name="municipio_residencia"
                                    value="{{ old('municipio_residencia', $user->profile?->municipio_residencia) }}"
                                    placeholder="Escribe tu ciudad...">
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="margin-bottom:12px">Tu Estilo</label>
                            <div class="visual-selector">
                                <label class="selector-option">
                                    <input type="radio" name="preferencia" value="surf" {{ (old('preferencia', $user->profile?->preferencia) === 'surf') ? 'checked' : '' }}>
                                    <div class="selector-content">
                                        <span class="selector-emoji">🌊</span>
                                        <span class="selector-text">Surf</span>
                                    </div>
                                </label>
                                <label class="selector-option">
                                    <input type="radio" name="preferencia" value="monte" {{ (old('preferencia', $user->profile?->preferencia) === 'monte') ? 'checked' : '' }}>
                                    <div class="selector-content">
                                        <span class="selector-emoji">⛰️</span>
                                        <span class="selector-text">Monte</span>
                                    </div>
                                </label>
                                <label class="selector-option">
                                    <input type="radio" name="preferencia" value="ambos" {{ (old('preferencia', $user->profile?->preferencia ?? 'ambos') === 'ambos') ? 'checked' : '' }}>
                                    <div class="selector-content">
                                        <span class="selector-emoji">🔥</span>
                                        <span class="selector-text">Ambos</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div style="margin-top: 32px; display:flex; align-items:center;">
                            <button type="submit" class="btn-primary">Guardar Cambios</button>
                            @if (session('status') === 'profile-updated')
                                <span class="status-msg">✅ Guardado</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- COLUMNA DERECHA: SEGURIDAD --}}
            <div class="column-right">

                {{-- CAMBIAR CONTRASEÑA (Traducido y Estilizado) --}}
                <div class="profile-card">
                    <div class="card-header">
                        <h2>🔒 Seguridad</h2>
                        <p>Actualiza tu contraseña periódicamente.</p>
                    </div>

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label class="form-label" for="current_password">Contraseña Actual</label>
                            <input id="current_password" name="current_password" type="password" class="form-input"
                                autocomplete="current-password">
                            @error('current_password', 'updatePassword') <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password">Nueva Contraseña</label>
                            <input id="password" name="password" type="password" class="form-input"
                                autocomplete="new-password">
                            @error('password', 'updatePassword') <span class="error-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Confirmar Nueva</label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                class="form-input" autocomplete="new-password">
                        </div>

                        <div style="margin-top: 24px; display:flex; align-items:center;">
                            <button type="submit" class="btn-primary">Actualizar Clave</button>
                            @if (session('status') === 'password-updated')
                                <span class="status-msg">✅ Actualizada</span>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- BORRAR CUENTA (Traducido) --}}
                <div class="profile-card danger-zone">
                    <div class="card-header">
                        <h2>⚠️ Zona de Peligro</h2>
                        <p>Eliminar tu cuenta es una acción irreversible.</p>
                    </div>

                    <p style="font-size: 0.9rem; color: #7f1d1d; margin-bottom: 20px; line-height: 1.5;">
                        Una vez que elimines tu cuenta, todos tus recursos y datos se borrarán permanentemente. Por favor,
                        asegúrate antes de proceder.
                    </p>

                    <form method="post" action="{{ route('profile.destroy') }}"
                        onsubmit="return confirm('¿ESTÁS SEGURO? Esta acción borrará tu cuenta permanentemente.');">
                        @csrf
                        @method('delete')

                        {{-- Nota: Para simplificar, usamos un confirm de navegador.
                        Si usas el modal de Breeze, requeriría AlpineJS aquí. --}}
                        <div class="form-group">
                            <label class="form-label" style="color:#991b1b">Para confirmar, escribe tu contraseña:</label>
                            <input type="password" name="password" class="form-input" placeholder="Tu contraseña actual"
                                style="border-color: #fca5a5;">
                            @error('password', 'userDeletion') <span class="error-text">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn-danger">
                            Eliminar mi cuenta definitivamente
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection