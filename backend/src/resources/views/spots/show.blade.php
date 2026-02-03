@extends('layouts.app')

@section('title', $spot->nombre)

@push('styles')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/spot-detail.css') }}?v=9">
@endpush

@section('content')

    @php
        // Lógica segura para galería e imagen de fondo
        $galleryImages = [];
        $defaultBg = $spot->tipo == 'monte'
            ? 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1200'
            : 'https://images.unsplash.com/photo-1471922694854-ff1b63b20054?q=80&w=1200';

        $bgImage = $defaultBg;

        if (!empty($spot->foto)) {
            $decoded = json_decode($spot->foto, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                foreach ($decoded as $path) {
                    $galleryImages[] = asset('storage/' . str_replace('\\', '/', $path));
                }
            } else {
                $galleryImages[] = asset('storage/' . str_replace('\\', '/', $spot->foto));
            }
            // Si hay fotos propias, usamos la primera de fondo
            if (count($galleryImages) > 0) {
                $bgImage = $galleryImages[0];
            }
        }
    @endphp

    <div class="spot-detail-wrapper">

        {{-- HERO SECTION --}}
        <div class="spot-hero" style="background-image: url('{{ $bgImage }}');">
            <div class="hero-overlay"></div>

            <button class="btn-fav-hero {{ $isFavorite ? 'active' : '' }}" id="btn-fav" title="Guardar en favoritos">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                </svg>
            </button>

            <div class="spot-container hero-content">
                <div class="hero-badges">
                    <span class="badge-spot {{ $spot->tipo }}">
                        {{ $spot->tipo == 'playa' ? '🌊' : '🏔️' }} {{ ucfirst($spot->tipo) }}
                    </span>
                </div>

                <h1 id="spot-title" class="text-white drop-shadow-lg">{{ $spot->nombre }}</h1>

                <div class="location-text">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    {{ $spot->municipio->nombre ?? 'Euskadi' }}
                </div>
            </div>
        </div>

        {{-- TABS SECTION --}}
        <div class="spot-container spot-tabs-container">
            <div id="tabs">
                <ul>
                    <li><a href="#tab-meteo">Previsión</a></li>
                    <li><a href="#tab-fotos">Galería</a></li>
                    <li><a href="#tab-info">Información</a></li>
                </ul>

                {{-- TAB 1: METEO --}}
                <div id="tab-meteo">
                    <div class="weather-dashboard">
                        {{-- Tarjeta Principal --}}
                        <div class="weather-main-card">
                            <div class="w-header">AHORA</div>
                            <div class="w-body">
                                <div id="main-icon-container" class="w-icon">
                                    <div class="loading-spinner"></div>
                                </div>
                                <div class="w-temp-box">
                                    <span id="main-temp">--</span><span class="unit">°C</span>
                                </div>
                            </div>
                            <div class="w-desc" id="weather-desc">Cargando...</div>
                        </div>

                        {{-- Stats Grid --}}
                        <div class="weather-stats-grid">
                            @for ($i = 1; $i <= 4; $i++)
                                <div class="stat-card">
                                    <div class="s-icon" id="s{{$i}}-icon"></div>
                                    <div class="s-data">
                                        <span class="s-val" id="s{{$i}}-val">--</span>
                                        <span class="s-label" id="s{{$i}}-label">--</span>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="forecast-section">
                        <h3 class="mb-4 text-lg font-bold text-gray-700">Próximos 7 días</h3>
                        <div id="weekly-forecast" class="forecast-scroll">
                            {{-- Se llena con JS --}}
                        </div>
                    </div>
                </div>

                {{-- TAB 2: FOTOS --}}
                <div id="tab-fotos">
                    <div class="gallery-grid">
                        @forelse($galleryImages as $img)
                            <div class="gallery-item">
                                <img src="{{ $img }}" alt="{{ $spot->nombre }}" loading="lazy">
                            </div>
                        @empty
                            <div class="empty-state text-center text-gray-500 py-10">
                                <p>No hay imágenes disponibles.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- TAB 3: INFO --}}
                <div id="tab-info">
                    <div class="info-layout">
                        <div class="info-content">
                            <h3 class="text-xl font-bold mb-3 text-gray-800">Sobre este lugar</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $spot->descripcion }}</p>
                            <div class="tags-wrapper mt-6">
                                @foreach($spot->etiquetas as $tag)
                                    <span class="tag-pill">#{{ $tag->nombre }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="map-wrapper">
                            <div id="mini-map"></div>
                            {{-- URL de Google Maps corregida --}}
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $spot->latitud }},{{ $spot->longitud }}"
                                target="_blank" class="btn-maps">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg" style="width: 20px; display: inline-block;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Ver en Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        window.spotConfig = {{ Js::from([
        'id' => $spot->id,
        'lat' => (float) $spot->latitud,
        'lng' => (float) $spot->longitud,
        'nombre' => $spot->nombre,
        'tipo' => $spot->tipo,
        'csrf' => csrf_token(),
        'urlFav' => route('favoritos.toggle', $spot->id)
    ]) }};
    </script>
    <script src="{{ asset('js/spot-detail.js') }}?v=9"></script>
@endpush