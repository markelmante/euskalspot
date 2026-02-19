@extends('layouts.app')

@section('title', $spot->nombre)

@push('styles')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/spot-detail.css') }}?v=9">
@endpush

@section('content')

    @php
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
            if (count($galleryImages) > 0) {
                $bgImage = $galleryImages[0];
            }
        }
    @endphp

    <main class="spot-detail-wrapper">

        <section class="spot-hero" style="background-image: url('{{ $bgImage }}');" aria-labelledby="spot-title">
            <div class="hero-overlay" aria-hidden="true"></div>

            <button class="btn-fav-hero {{ $isFavorite ? 'active' : '' }}" id="btn-fav" 
                aria-label="{{ $isFavorite ? 'Quitar de favoritos' : 'Guardar en favoritos' }}" 
                aria-pressed="{{ $isFavorite ? 'true' : 'false' }}">
                <svg aria-hidden="true" viewBox="0 0 24 24" stroke-width="2">
                    <path
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                </svg>
            </button>

            <div class="spot-container hero-content">
                <div class="hero-badges">
                    <span class="badge-spot {{ $spot->tipo }}" aria-label="Tipo de lugar: {{ $spot->tipo }}">
                        <span aria-hidden="true">{{ $spot->tipo == 'playa' ? '🌊' : '🏔️' }}</span> {{ ucfirst($spot->tipo) }}
                    </span>
                </div>

                <h1 id="spot-title" class="text-white drop-shadow-lg">{{ $spot->nombre }}</h1>

                <div class="location-text">
                    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    {{ $spot->municipio->nombre ?? 'Euskadi' }}
                </div>
            </div>
        </section>

        <section class="spot-container spot-tabs-container">
            <h2 class="visually-hidden">Detalles del lugar</h2>
            <div id="tabs">
                <ul>
                    <li><a href="#tab-meteo">Previsión</a></li>
                    <li><a href="#tab-fotos">Galería</a></li>
                    <li><a href="#tab-info">Información</a></li>
                </ul>

                <div id="tab-meteo">
                    <div class="weather-dashboard">
                        <div class="weather-main-card">
                            <h3 class="w-header" id="current-weather-heading">AHORA</h3>
                            <div class="w-body" aria-labelledby="current-weather-heading">
                                <div id="main-icon-container" class="w-icon" aria-hidden="true">
                                    <div class="loading-spinner"></div>
                                </div>
                                <div class="w-temp-box" aria-live="polite">
                                    <span id="main-temp">--</span><span class="unit" aria-label="grados Celsius">°C</span>
                                </div>
                            </div>
                            <div class="w-desc" id="weather-desc" aria-live="polite">Cargando...</div>
                        </div>

                        <div class="weather-stats-grid" aria-label="Estadísticas del clima actual">
                            @for ($i = 1; $i <= 4; $i++)
                                <div class="stat-card">
                                    <div class="s-icon" id="s{{$i}}-icon" aria-hidden="true"></div>
                                    <div class="s-data">
                                        <span class="s-val" id="s{{$i}}-val" aria-live="polite">--</span>
                                        <span class="s-label" id="s{{$i}}-label">--</span>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="forecast-section">
                        <h3 class="mb-4 text-lg font-bold text-gray-700">Próximos 7 días</h3>
                        <div id="weekly-forecast" class="forecast-scroll" aria-live="polite">
                        </div>
                    </div>
                </div>

                <div id="tab-fotos">
                    <div class="gallery-grid">
                        @forelse($galleryImages as $index => $img)
                            <div class="gallery-item">
                                <img src="{{ $img }}" alt="Fotografía {{ $index + 1 }} de {{ $spot->nombre }}" loading="lazy">
                            </div>
                        @empty
                            <div class="empty-state text-center text-gray-500 py-10" role="status">
                                <p>No hay imágenes disponibles.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div id="tab-info">
                    <div class="info-layout">
                        <div class="info-content">
                            <h3 class="text-xl font-bold mb-3 text-gray-800">Sobre este lugar</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $spot->descripcion }}</p>
                            <div class="tags-wrapper mt-6" aria-label="Etiquetas">
                                @foreach($spot->etiquetas as $tag)
                                    <span class="tag-pill">#{{ $tag->nombre }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="map-wrapper">
                            <div id="mini-map" aria-label="Mapa interactivo de la ubicación" role="region"></div>
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $spot->latitud }},{{ $spot->longitud }}"
                                target="_blank" rel="noopener noreferrer" class="btn-maps">
                                <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg" style="width: 20px; display: inline-block;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Ver en Google Maps
                                <span class="visually-hidden">(se abre en una ventana nueva)</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
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