@extends('layouts.app')

@section('title', 'Únete a EuskalSpot')

@section('content')
    <section class="auth-section">
        <div class="auth-card" style="max-width: 550px;">
            <div class="text-center">
                <h2>Crea tu Perfil</h2>
                <p>Personaliza tu experiencia en EuskalSpot</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                            placeholder="Tu nombre">
                        @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="municipio">Tu Municipio</label>
                        <input type="text" id="municipio" name="municipio" value="{{ old('municipio') }}"
                            placeholder="Ej: Zarautz">
                        @error('municipio') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        placeholder="correo@ejemplo.com">
                    @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>¿Qué aventura prefieres?</label>
                    <div style="display: flex; gap: 10px; margin-top: 8px;">

                        <label class="pref-card">
                            <input type="radio" name="preferencia" value="surf" {{ old('preferencia') == 'surf' ? 'checked' : '' }}>
                            <div class="pref-content">
                                <span class="icon">🏄‍♂️</span>
                                <span class="text">Surf</span>
                            </div>
                        </label>

                        <label class="pref-card">
                            <input type="radio" name="preferencia" value="monte" {{ old('preferencia') == 'monte' ? 'checked' : '' }}>
                            <div class="pref-content">
                                <span class="icon">🥾</span>
                                <span class="text">Monte</span>
                            </div>
                        </label>

                        <label class="pref-card">
                            <input type="radio" name="preferencia" value="ambos" {{ old('preferencia') == 'ambos' || old('preferencia') == null ? 'checked' : '' }}>
                            <div class="pref-content">
                                <span class="icon">🔄</span>
                                <span class="text">Ambos</span>
                            </div>
                        </label>

                    </div>
                    @error('preferencia') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required placeholder="••••••••">
                        @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmar</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn-cta-auth" style="margin-top: 10px;">Comenzar la aventura</button>
            </form>

            <div class="auth-footer">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
            </div>
        </div>
    </section>
@endsection