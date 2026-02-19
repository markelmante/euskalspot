@extends('layouts.auth')

@section('title', 'Únete a EuskalSpot')

@section('content')
    <section class="auth-section" style="width: 100%;">
        <div class="auth-card" style="max-width: 550px; margin: 0 auto;">
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
                    <label style="margin-bottom:12px; display: block; font-weight: bold; color: #555;">¿Qué aventura
                        prefieres?</label>
                    <div class="visual-selector" style="display: flex; gap: 10px;">

                        {{-- OPCIÓN SURF --}}
                        <label class="selector-option" style="flex: 1;">
                            <input type="radio" name="preferencia" value="surf" {{ old('preferencia') == 'surf' ? 'checked' : '' }}>
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
                        <label class="selector-option" style="flex: 1;">
                            <input type="radio" name="preferencia" value="monte" {{ old('preferencia') == 'monte' ? 'checked' : '' }}>
                            <div class="selector-content">
                                <svg class="selector-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                                </svg>
                                <span class="selector-text">Monte</span>
                            </div>
                        </label>

                        {{-- OPCIÓN AMBOS --}}
                        <label class="selector-option" style="flex: 1;">
                            <input type="radio" name="preferencia" value="ambos" {{ old('preferencia') == 'ambos' || old('preferencia') == null ? 'checked' : '' }}>
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