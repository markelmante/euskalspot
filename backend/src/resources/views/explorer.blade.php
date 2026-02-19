@extends('layouts.app')

@section('title', 'Explorador de Spots')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/explorer.css') }}?v=8">
@endpush

@section('content')
    <div class="explorer-container">
        {{-- SIDEBAR --}}
        <aside class="sidebar-map">
            <div class="filters-section">
                <h3>Explorar</h3>

                {{-- BUSCADOR --}}
                <div class="search-container">
                    <input type="text" id="searchInput" class="search-input" placeholder="Buscar por nombre o municipio...">
                </div>

                {{-- FILTROS DE TIPO --}}
                <div class="filter-group mb-3">
                    <button class="btn-filter active" onclick="filterType('all', this)">
                        Todos
                    </button>

                    <button class="btn-filter" onclick="filterType('playa', this)">
                        <svg viewBox="0 0 24 24" class="text-playa" fill="none" stroke="currentColor" stroke-width="2"
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

                    <button class="btn-filter" onclick="filterType('monte', this)">
                        <svg viewBox="0 0 24 24" class="text-monte" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                        </svg>
                        Montes
                    </button>
                </div>

                {{-- NUEVO: FILTROS DE ETIQUETAS --}}
                <div class="tags-container">
                    <p class="filter-label">Filtrar por etiquetas:</p>
                    <div class="tags-scroll">
                        @foreach($etiquetas as $etiqueta)
                            <button class="btn-tag" onclick="filterTag({{ $etiqueta->id }}, this)">
                                {{ $etiqueta->nombre }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="spots-list" id="spots-list-container">
                <p style="text-align:center; padding:20px; color:#94a3b8;">Cargando spots...</p>
            </div>
        </aside>

        {{-- MAPA --}}
        <div id="map"></div>

        {{-- DRAWER DE DETALLES --}}
        <div id="spotDrawer" class="spot-drawer">
            {{-- El contenido se inyecta vía JS --}}
        </div>
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

    <script src="{{ asset('js/explorer.js') }}?v=8"></script>
@endpush