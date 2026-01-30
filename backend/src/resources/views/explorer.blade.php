@extends('layouts.app')

@section('title', 'Explorador de Spots')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    {{-- CSS Externo --}}
    <link rel="stylesheet" href="{{ asset('css/explorer.css') }}?v=1">
@endpush

@section('content')
    <div class="explorer-container">
        {{-- SIDEBAR --}}
        <aside class="sidebar-map">
            <div class="filters-section">
                <h3>Filtrar Spots</h3>
                <div class="filter-group">
                    <button class="btn-filter active" onclick="filterType('all', this)">Todos</button>
                    <button class="btn-filter" onclick="filterType('playa', this)">🌊 Playas</button>
                    <button class="btn-filter" onclick="filterType('monte', this)">⛰️ Montes</button>
                </div>
            </div>
            <div class="spots-list" id="spots-list-container">
                <p style="text-align:center; padding:20px; color:#94a3b8;">Cargando...</p>
            </div>
        </aside>

        {{-- MAPA --}}
        <div id="map"></div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- EL PUENTE DE DATOS: Pasamos variables de PHP a JS --}}
    <script>
        window.explorerData = {
            spots: @json($spots),
            favorites: @json($favoritosIds),
            csrfToken: '{{ csrf_token() }}'
        };
    </script>

    {{-- JS Externo (Carga después del puente) --}}
    <script src="{{ asset('js/explorer.js') }}?v=1"></script>
@endpush