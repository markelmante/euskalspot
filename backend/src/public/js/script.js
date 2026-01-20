let map;
let markersGroup;
let allSpots = [];

document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar el mapa
    if (document.getElementById('map')) {
        map = L.map('map').setView([43.263, -2.935], 9); // Centrado en Bilbao/Euskadi
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '©OpenStreetMap'
        }).addTo(map);
        markersGroup = L.layerGroup().addTo(map);
    }

    // 2. Cargar datos
    if (document.getElementById('lista-spots')) {
        cargarSpots();
    }
});

async function cargarSpots() {
    try {
        const response = await fetch('/api/spots', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });

        if (response.status === 401) { window.location.href = '/login'; return; }

        allSpots = await response.json();

        // Una vez tenemos los datos, activamos buscador y pintamos
        configurarBuscador();
        renderizarInterfaz('todos');

    } catch (e) {
        console.error("Error cargando spots:", e);
    }
}

function renderizarInterfaz(filtro) {
    const lista = document.getElementById('lista-spots');
    lista.innerHTML = '';
    markersGroup.clearLayers();

    const filtrados = filtro === 'todos' ? allSpots : allSpots.filter(s => s.tipo === filtro);

    filtrados.forEach(spot => {
        // --- ICONOS PERSONALIZADOS ---
        const iconoEmoji = spot.tipo === 'playa' ? '🌊' : '⛰️';
        const customIcon = L.divIcon({
            html: `<div class="custom-marker">${iconoEmoji}</div>`,
            className: 'dummy', // Leaflet necesita una clase
            iconSize: [30, 30],
            popupAnchor: [0, -15]
        });

        // Marcador en el mapa
        L.marker([spot.latitud, spot.longitud], { icon: customIcon })
            .addTo(markersGroup)
            .bindPopup(`<b>${spot.nombre}</b><br>${spot.municipio.nombre}`);

        // Tarjeta en el listado
        lista.innerHTML += `
            <div class="review-card" onclick="map.flyTo([${spot.latitud}, ${spot.longitud}], 14)">
                <div class="badge ${spot.tipo === 'playa' ? 'badge-surf' : 'badge-mountain'}">
                    ${spot.tipo.toUpperCase()}
                </div>
                <h3>${spot.nombre}</h3>
                <p class="review-text">📍 ${spot.municipio ? spot.municipio.nombre : 'Euskadi'}</p>
                <button class="btn-cta" style="width: 100%; margin-top: 15px;">Ver detalles</button>
            </div>`;
    });
}

function configurarBuscador() {
    // Obtenemos nombres de municipios únicos
    const municipios = [...new Set(allSpots.map(s => s.municipio.nombre))];

    $("#buscador-municipio").autocomplete({
        source: municipios,
        select: function (event, ui) {
            const elegido = ui.item.value;
            const spotsMunicipio = allSpots.filter(s => s.municipio.nombre === elegido);

            // Centrar mapa en el primer spot de ese municipio
            if (spotsMunicipio.length > 0) {
                map.flyTo([spotsMunicipio[0].latitud, spotsMunicipio[0].longitud], 13);
                // Opcional: filtrar las tarjetas para mostrar solo las de ese municipio
                renderizarPorMunicipio(elegido);
            }
        }
    });
}

function renderizarPorMunicipio(nombreMun) {
    const lista = document.getElementById('lista-spots');
    lista.innerHTML = '';
    const filtrados = allSpots.filter(s => s.municipio.nombre === nombreMun);

    filtrados.forEach(spot => {
        lista.innerHTML += `
            <div class="review-card" onclick="map.flyTo([${spot.latitud}, ${spot.longitud}], 14)">
                <div class="badge ${spot.tipo === 'playa' ? 'badge-surf' : 'badge-mountain'}">
                    ${spot.tipo.toUpperCase()}
                </div>
                <h3>${spot.nombre}</h3>
                <p class="review-text">📍 ${spot.municipio.nombre}</p>
                <button class="btn-cta" style="width: 100%; margin-top: 15px;">Ver detalles</button>
            </div>`;
    });
}

// Global para los botones de filtro
window.filtrarSpots = (tipo) => renderizarInterfaz(tipo);