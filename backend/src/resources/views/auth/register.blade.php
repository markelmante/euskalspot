@extends('layouts.app')

@section('title', 'Únete a EuskalSpot')
@section('body-class', 'auth-page')

@section('content')
    <section class="auth-section">
        <div class="auth-card" style="max-width: 550px;">
            <h2>Crea tu Perfil</h2>
            <p>Personaliza tu experiencia en EuskalSpot</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                            placeholder="Tu nombre">
                    </div>

                    <div class="form-group">
                        <label for="municipio">Tu Municipio</label>
                        <input type="text" id="municipio" name="municipio" value="{{ old('municipio') }}"
                            placeholder="Ej: Irun, Sopela...">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        placeholder="correo@ejemplo.com">
                    @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>¿Qué aventura prefieres?</label>
                    <div style="display: flex; gap: 10px; margin-top: 8px;">
                        <label class="pref-card">
                            <input type="radio" name="preferencia" value="surf" checked>
                            <div class="pref-content">
                                <span class="icon">🏄‍♂️</span>
                                <span class="text">Surf</span>
                            </div>
                        </label>
                        <label class="pref-card">
                            <input type="radio" name="preferencia" value="monte">
                            <div class="pref-content">
                                <span class="icon">🥾</span>
                                <span class="text">Monte</span>
                            </div>
                        </label>
                        <label class="pref-card">
                            <input type="radio" name="preferencia" value="ambos">
                            <div class="pref-content">
                                <span class="icon">🔄</span>
                                <span class="text">Ambos</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required placeholder="••••••••">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmar</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn-cta-auth" style="margin-top: 10px;">Comenzar la aventura</button>
            </form>

            <p class="auth-footer">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
        </div>
    </section>

    <style>
        /* Estilos extra para las tarjetas de preferencia */
        .pref-card {
            flex: 1;
            cursor: pointer;
        }

        .pref-card input {
            display: none;
        }

        .pref-content {
            border: 2px solid #e2e8f0;
            padding: 12px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .pref-content .icon {
            font-size: 1.4rem;
            display: block;
        }

        .pref-content .text {
            font-size: 0.8rem;
            font-weight: bold;
            color: #64748b;
        }

        .pref-card input:checked+.pref-content {
            border-color: var(--azul);
            background: #eff6ff;
        }

        .pref-card input:checked+.pref-content .text {
            color: var(--azul);
        }
    </style>
@endsection