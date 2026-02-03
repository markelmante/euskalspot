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
                        <h2>
                            <svg class="section-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            Datos Personales
                        </h2>
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
                                {{-- OPCIÓN SURF --}}
                                <label class="selector-option">
                                    <input type="radio" name="preferencia" value="surf" {{ (old('preferencia', $user->profile?->preferencia) === 'surf') ? 'checked' : '' }}>
                                    <div class="selector-content">
                                        <svg class="selector-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" />
                                            <path
                                                d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" />
                                            <path
                                                d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" />
                                        </svg>
                                        <span class="selector-text">Surf</span>
                                    </div>
                                </label>

                                {{-- OPCIÓN MONTE --}}
                                <label class="selector-option">
                                    <input type="radio" name="preferencia" value="monte" {{ (old('preferencia', $user->profile?->preferencia) === 'monte') ? 'checked' : '' }}>
                                    <div class="selector-content">
                                        <svg class="selector-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                                        </svg>
                                        <span class="selector-text">Monte</span>
                                    </div>
                                </label>

                                {{-- OPCIÓN AMBOS --}}
                                <label class="selector-option">
                                    <input type="radio" name="preferencia" value="ambos" {{ (old('preferencia', $user->profile?->preferencia ?? 'ambos') === 'ambos') ? 'checked' : '' }}>
                                    <div class="selector-content">
                                        <svg class="selector-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.1.2-2.2.5-3.27.57 1.07 1.08 2.17 2.5 2.77z" />
                                        </svg>
                                        <span class="selector-text">Ambos</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div style="margin-top: 32px; display:flex; align-items:center;">
                            <button type="submit" class="btn-primary">Guardar Cambios</button>
                            @if (session('status') === 'profile-updated')
                                <div class="status-msg">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                    Guardado
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- COLUMNA DERECHA: SEGURIDAD --}}
            <div class="column-right">

                {{-- CAMBIAR CONTRASEÑA --}}
                <div class="profile-card">
                    <div class="card-header">
                        <h2>
                            <svg class="section-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            Seguridad
                        </h2>
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
                                <div class="status-msg">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                    Actualizada
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- BORRAR CUENTA --}}
                <div class="profile-card danger-zone">
                    <div class="card-header">
                        <h2>
                            <svg class="section-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                                <line x1="12" y1="9" x2="12" y2="13" />
                                <line x1="12" y1="17" x2="12.01" y2="17" />
                            </svg>
                            Zona de Peligro
                        </h2>
                        <p>Eliminar tu cuenta es una acción irreversible.</p>
                    </div>

                    <p style="font-size: 0.9rem; color: #7f1d1d; margin-bottom: 20px; line-height: 1.5;">
                        Una vez que elimines tu cuenta, todos tus recursos y datos se borrarán permanentemente.
                    </p>

                    <form method="post" action="{{ route('profile.destroy') }}"
                        onsubmit="return confirm('¿ESTÁS SEGURO? Esta acción borrará tu cuenta permanentemente.');">
                        @csrf
                        @method('delete')

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