@extends('layouts.app')

@section('title', 'Explorador de Spots')

@push('styles')
    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        /* CONTENEDOR PRINCIPAL: Ocupa toda la altura menos el header */
        .explorer-container {
            display: flex;
            height: calc(100vh - 70px);
            /* Ajusta 70px según la altura de tu navbar */
            overflow: hidden;
        }

        /* BARRA LATERAL (Filtros y Lista) */
        .sidebar-map {
            width: 350px;
            background: white;
            border-right: 1px solid #E2E8F0;
            display: flex;
            flex-direction: column;
            z-index: 2;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }

        .filters-section {
            padding: 20px;
            border-bottom: 1px solid #E2E8F0;
            background: #F8FAFC;
        }

        .filter-group {
            margin-bottom: 15px;
        }

        .filter-group label {
            display: block;
            font-weight: 700;
            margin-bottom: 8px;
            color: #1E293B;
            font-size: 0.9rem;
        }

        .btn-filter {
            border: 1px solid #CBD5E1;
            background: white;
            padding: 6px 12px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.85rem;
            margin-right: 5px;
            transition: 0.2s;
            color: #64748B;
        }

        .btn-filter.active {
            background: #2563EB;
            color: white;
            border-color: #2563EB;
        }

        /* Lista de resultados (Scrollable) */
        .spots-list {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .spot-item {
            padding: 15px;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: 0.2s;
        }

        .spot-item:hover {
            border-color: #2563EB;
            background: #F1F5F9;
        }

        .spot-item h4 {
            margin: 0 0 5px 0;
            font-size: 1rem;
            color: #1E293B;
        }

        .spot-item p {
            margin: 0;
            font-size: 0.85rem;
            color: #64748B;
        }

        /* EL MAPA */
        #map {
            flex: 1;
            /* Ocupa el resto del espacio */
            height: 100%;
            width: 100%;
            z-index: 1;
        }

        /* CUSTOM MARKERS (CSS para los iconos del mapa) */
        .custom-marker {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
            font-size: 1.2rem;
        }

        .marker-playa {
            background-color: #2563EB;
        }

        /* Azul */
        .marker-monte {
            background-color: #10B981;
        }

        /* Verde */

        /* Responsive móvil */
        @media (max-width: 768px) {
            .explorer-container {
                flex-direction: column-reverse;
            }

            .sidebar-map {
                width: 100%;
                height: 40%;
            }

            #map {
                height: 60%;
            }
        }
    </style>
@endpush

@section('content')

    <div class="explorer-container">

        {{-- 1. SIDEBAR --}}
        <aside class="sidebar-map">
            <div class="filters-section">
                <h3>Filtrar Spots</h3>

                {{-- Filtro Tipo --}}
                <div class="filter-group">
                    <label>Tipo de Spot</label>
                    <button class="btn-filter active" onclick="filterType('all', this)">Todos</button>
                    <button class="btn-filter" onclick="filterType('playa', this)">🌊 Playas</button>
                    <button class="btn-filter" onclick="filterType('monte', this)">⛰️ Montes</button>
                </div>

                {{-- Filtro Etiquetas (Opcional, visualmente listo) --}}
                <div class="filter-group">
                    <label>Características</label>
                    @foreach($etiquetas->take(4) as $tag)
                        <span
                            style="font-size:0.8rem; background:#e2e8f0; padding:2px 6px; border-radius:4px; color:#475569; margin-right:4px;">
                            {{ $tag->nombre }}
                        </span>
                    @endforeach
                </div>
            </div>

            <div class="spots-list" id="spots-list-container">
                {{-- Aquí se rellenará con JS --}}
                <p style="text-align:center; color:#94a3b8;">Cargando spots...</p>
            </div>
        </aside>

        {{-- 2. MAPA --}}
        <div id="map"></div>

    </div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // 1. OBTENER DATOS DE LARAVEL (PASO CRUCIAL)
        const allSpots = @json($spots);

        // 2. INICIALIZAR MAPA (Centrado en Euskadi)
        var map = L.map('map').setView([43.1, -2.5], 9);

        // Capa base (OpenStreetMap - Gratuito y bonito)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>',
            maxZoom: 19
        }).addTo(map);

        var markersLayer = L.layerGroup().addTo(map); // Capa para guardar los marcadores
        let currentFilter = 'all';

        // 3. FUNCIÓN PARA PINTAR MARCADORES
        function renderMap(typeFilter) {
            markersLayer.clearLayers(); // Borrar anteriores
            const listContainer = document.getElementById('spots-list-container');
            listContainer.innerHTML = ''; // Limpiar lista lateral

            allSpots.forEach(spot => {
                // Filtro lógico
                if (typeFilter !== 'all' && spot.tipo !== typeFilter) return;

                // A. Crear Icono Personalizado
                const emoji = spot.tipo === 'playa' ? '🌊' : '⛰️';
                const colorClass = spot.tipo === 'playa' ? 'marker-playa' : 'marker-monte';

                const customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div class="custom-marker ${colorClass}">${emoji}</div>`,
                    iconSize: [30, 30],
                    iconAnchor: [15, 15],
                    popupAnchor: [0, -15]
                });

                // B. Añadir al Mapa
                const marker = L.marker([spot.latitud, spot.longitud], { icon: customIcon })
                    .bindPopup(`
                            <div style="text-align:center;">
                                <h4 style="margin:0; color:#1e293b;">${spot.nombre}</h4>
                                <span style="font-size:0.8rem; color:#64748b;">${spot.municipio.nombre}</span><br>
                                <a href="#" style="color:#2563EB; font-weight:bold; font-size:0.8rem;">Ver detalles</a>
                            </div>
                        `);

                markersLayer.addLayer(marker);

                // C. Añadir a la Lista Lateral
                const item = document.createElement('div');
                item.className = 'spot-item';
                item.innerHTML = `
                        <h4>${emoji} ${spot.nombre}</h4>
                        <p>📍 ${spot.municipio.nombre}</p>
                    `;
                // Al hacer clic en la lista, el mapa viaja al spot
                item.onclick = () => {
                    map.flyTo([spot.latitud, spot.longitud], 14);
                    marker.openPopup();
                };
                listContainer.appendChild(item);
            });
        }

        // 4. LÓGICA DE BOTONES DE FILTRO
        function filterType(type, btn) {
            currentFilter = type;

            // Actualizar estilo botones
            document.querySelectorAll('.btn-filter').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            renderMap(type);
        }

        // Iniciar
        document.addEventListener('DOMContentLoaded', () => {
            renderMap('all');
        });

    </script>
@endpush