@extends('layouts.app')

@section('content')
    <div class="municipios-wrapper">
        <div class="muni-container">

            {{-- ENCABEZADO --}}
            <div class="section-header">
                <h1>Municipios</h1>
                <p>Descubre los {{ $municipios->count() }} municipios donde ya tenemos spots registrados.</p>
            </div>

            {{-- BUSCADOR --}}
            <div class="search-toolbar">
                <div class="search-input-group">
                    <svg aria-hidden="true" class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    {{-- Accesibilidad: aria-label añadido al input para lectores de pantalla --}}
                    <input type="text" class="muni-search-input js-search-input"
                        placeholder="Buscar municipio (ej: Bakio, Zarautz)..."
                        aria-label="Buscar municipio por nombre">
                </div>
            </div>

            {{-- GRID DINÁMICO --}}
            {{-- Accesibilidad: aria-live informa automáticamente si la lista cambia por la búsqueda --}}
            <div class="municipios-grid" id="municipios-grid" aria-live="polite">

                @forelse($municipios as $muni)
                    {{-- Accesibilidad: aria-label agrupa toda la info de la tarjeta para que el lector no salte entre elementos sueltos --}}
                    <a href="{{ route('municipios.show', $muni) }}" class="muni-card js-muni-card"
                        data-name="{{ strtolower($muni->nombre) }}"
                        aria-label="Ver los {{ $muni->spots_count }} spots en el municipio de {{ $muni->nombre }}, {{ $muni->provincia ?? 'Euskadi' }}">
                        
                        <div class="card-image-wrapper">
                            @php
                                $imgSrc = "https://images.unsplash.com/photo-1549488347-1f92e7614210?auto=format&fit=crop&w=800";

                                if ($muni->spots->isNotEmpty() && $muni->spots->first()->foto) {
                                    $rawFoto = $muni->spots->first()->foto;
                                    if (is_string($rawFoto)) {
                                        $decoded = json_decode($rawFoto, true);
                                        $path = is_array($decoded) ? ($decoded[0] ?? null) : $rawFoto;
                                    } elseif (is_array($rawFoto)) {
                                        $path = $rawFoto[0] ?? null;
                                    }
                                    if (isset($path)) {
                                        $path = str_replace('\\', '/', $path);
                                        $imgSrc = asset('storage/' . $path);
                                    }
                                }
                            @endphp

                            {{-- Accesibilidad: alt vacío porque el enlace ya tiene el nombre en aria-label --}}
                            <img src="{{ $imgSrc }}" alt="" aria-hidden="true">
                            <span class="province-badge" aria-hidden="true">{{ $muni->provincia ?? 'Euskadi' }}</span>
                        </div>

                        {{-- Accesibilidad: Se oculta el contenido interno al lector porque el aria-label del <a> ya lo lee todo de una vez --}}
                        <div class="card-content" aria-hidden="true">
                            <h3 class="muni-name">{{ $muni->nombre }}</h3>

                            <div class="muni-stats">
                                <div class="stat-item">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $muni->spots_count }} Spots</span>
                                </div>
                            </div>

                            <div class="card-action">
                                <div class="btn-arrow">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-center">No hay municipios con spots activos todavía.</p>
                @endforelse

            </div>

            {{-- MENSAJE DE "NO RESULTADOS" --}}
            <div id="no-results-message" class="empty-state" style="display: none;" aria-live="assertive">
                <div class="empty-icon-wrapper">
                    <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                    </svg>
                </div>
                <h3>Sin resultados</h3>
                <p>No encontramos ningún municipio con ese nombre.</p>
                <button class="btn-reset"
                    onclick="document.querySelector('.js-search-input').value = ''; document.querySelector('.js-search-input').dispatchEvent(new Event('input'));"
                    aria-label="Borrar búsqueda y mostrar todos los municipios">
                    Borrar búsqueda
                </button>
            </div>

        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/municipios.css') }}?v={{ time() }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/municipios.js') }}?v={{ time() }}"></script>
@endpush