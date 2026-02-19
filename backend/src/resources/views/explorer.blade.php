@extends('layouts.app')

@section('title', 'Explorador de Spots')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/explorer.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="explorer-container">
        {{-- SIDEBAR --}}
        <aside class="sidebar-map" aria-label="Filtros y lista de spots">
            <div class="filters-section">
                {{-- CORRECCIÓN: H1 en lugar de H2 para cumplir la jerarquía --}}
                <h1 class="main-explorer-title">Explorar Spots</h1>

                {{-- BUSCADOR --}}
                <div class="search-container">
                    <label for="searchInput" class="visually-hidden">Buscar spot por nombre o municipio</label>
                    <input type="text" id="searchInput" class="search-input" placeholder="Buscar por nombre o municipio...">
                </div>

                {{-- FILTROS DE TIPO --}}
                <fieldset class="filter-group mb-3" style="border: none; padding: 0; margin: 0;">
                    <legend class="visually-hidden">Filtrar por tipo de spot</legend>
                    <button class="btn-filter active" aria-pressed="true" onclick="filterType('all', this)" aria-label="Mostrar todos los spots">
                        Todos
                    </button>

                    <button class="btn-filter" aria-pressed="false" onclick="filterType('playa', this)" aria-label="Filtrar por playas">
                        <svg aria-hidden="true" viewBox="0 0 24 24" class="text-playa" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" />
                            <path
                                d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" />
                            <path
                                d="M2 6c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1" />
                        </svg>
                        Playas
                    </button>

                    <button class="btn-filter" aria-pressed="false" onclick="filterType('monte', this)" aria-label="Filtrar por montes">
                        <svg aria-hidden="true" viewBox="0 0 24 24" class="text-monte" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                        </svg>
                        Montes
                    </button>
                </fieldset>

                {{-- FILTROS DE ETIQUETAS --}}
                <fieldset class="tags-container" style="border: none; padding: 0; margin: 0;">
                    <legend class="filter-label">Filtrar por etiquetas:</legend>
                    <div class="tags-scroll">
                        @foreach($etiquetas as $etiqueta)
                            <button class="btn-tag" aria-pressed="false" onclick="filterTag({{ $etiqueta->id }}, this)" aria-label="Filtrar por etiqueta {{ $etiqueta->nombre }}">
                                {{ $etiqueta->nombre }}
                            </button>
                        @endforeach
                    </div>
                </fieldset>
            </div>

            <div class="spots-list" id="spots-list-container" aria-live="polite" aria-busy="true">
                {{-- CORRECCIÓN: Color oscurecido para contraste --}}
                <p style="text-align:center; padding:20px; color:#475569;">Cargando spots...</p>
            </div>
        </aside>

        {{-- MAPA --}}
        <div id="map" aria-label="Mapa interactivo de spots" role="region"></div>

        {{-- DRAWER DE DETALLES --}}
        <section id="spotDrawer" class="spot-drawer" aria-hidden="true" aria-label="Detalles del spot seleccionado">
            {{-- El contenido se inyecta vía JS --}}
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        window.explorerData = {
            spots: @json($spots),
            favorites: @json($favoritosIds ?? []),
            csrfToken: '{{ csrf_token() }}',
            urlFavTemplate: "{{ route('favoritos.toggle', ['id' => 'PLACEHOLDER']) }}"
        };
    </script>

    <script src="{{ asset('js/explorer.js') }}?v={{ time() }}"></script>
@endpush