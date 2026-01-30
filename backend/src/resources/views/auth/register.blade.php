@extends('layouts.app')

@section('title', 'Únete a EuskalSpot')

@section('content')
    <section class="auth-section">
        <div class="auth-card" style="max-width: 550px;">
            <div class="text-center">
                <h2>Crea tu Perfil</h2>
                <p>Personaliza tu experiencia en EuskalSpot</p>
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <div style="display: grid; grid-template-columns: 1fr; gap: 15px;">
                    {{-- NOMBRE --}}
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                            placeholder="Tu nombre">
                        @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    {{-- SELECCIÓN DE UBICACIÓN (CASCADA) --}}
                    <div class="form-group"
                        style="background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #eee;">
                        <label style="margin-bottom: 10px; display: block; font-weight: bold; color: #555;">Tu
                            Ubicación</label>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            {{-- 1. PROVINCIA --}}
                            <div>
                                <select id="provincia"
                                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                    <option value="">Territorio...</option>
                                    @foreach($provincias as $prov)
                                        <option value="{{ $prov }}">{{ $prov }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 2. MUNICIPIO --}}
                            <div>
                                <select id="municipio" name="municipio" disabled
                                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; background-color: #eee; cursor: not-allowed;">
                                    <option value="">Municipio...</option>
                                    {{-- Se rellena con JS --}}
                                </select>
                            </div>
                        </div>
                        @error('municipio') <span class="error-msg"
                        style="display:block; margin-top:5px;">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- EMAIL --}}
                <div class="form-group" style="margin-top: 15px;">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        placeholder="correo@ejemplo.com">
                    @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                {{-- PREFERENCIAS --}}
                <div class="form-group">
                    <label>¿Qué aventura prefieres?</label>
                    <div style="display: flex; gap: 10px; margin-top: 8px;">
                        <label class="pref-card">
                            <input type="radio" name="preferencia" value="surf" {{ old('preferencia') == 'surf' ? 'checked' : '' }}>
                            <div class="pref-content"><span class="icon">🏄‍♂️</span><span class="text">Surf</span></div>
                        </label>
                        <label class="pref-card">
                            <input type="radio" name="preferencia" value="monte" {{ old('preferencia') == 'monte' ? 'checked' : '' }}>
                            <div class="pref-content"><span class="icon">🥾</span><span class="text">Monte</span></div>
                        </label>
                        <label class="pref-card">
                            <input type="radio" name="preferencia" value="ambos" {{ old('preferencia') == 'ambos' || old('preferencia') == null ? 'checked' : '' }}>
                            <div class="pref-content"><span class="icon">🔄</span><span class="text">Ambos</span></div>
                        </label>
                    </div>
                    @error('preferencia') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                {{-- PASSWORD --}}
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

                <button type="submit" class="btn-cta-auth" style="margin-top: 20px;">Comenzar la aventura</button>
            </form>

            <div class="auth-footer">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
            </div>
        </div>
    </section>

    {{-- SCRIPT DE FILTRADO --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Pasamos los datos de PHP a JS
            const allMunicipios = @json($municipios);

            const selectProvincia = document.getElementById('provincia');
            const selectMunicipio = document.getElementById('municipio');

            selectProvincia.addEventListener('change', function () {
                const provincia = this.value;

                // Resetear municipio
                selectMunicipio.innerHTML = '<option value="">Selecciona Municipio...</option>';

                if (provincia) {
                    // Habilitar
                    selectMunicipio.disabled = false;
                    selectMunicipio.style.backgroundColor = '#fff';
                    selectMunicipio.style.cursor = 'pointer';

                    // Filtrar
                    const filtrados = allMunicipios.filter(m => m.provincia === provincia);

                    // Rellenar
                    filtrados.forEach(m => {
                        const option = document.createElement('option');
                        option.value = m.nombre;
                        option.textContent = m.nombre;
                        selectMunicipio.appendChild(option);
                    });
                } else {
                    // Deshabilitar
                    selectMunicipio.disabled = true;
                    selectMunicipio.style.backgroundColor = '#eee';
                    selectMunicipio.style.cursor = 'not-allowed';
                }
            });
        });
    </script>
@endsection