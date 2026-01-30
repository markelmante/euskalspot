/* public/js/explorer.js */

document.addEventListener('DOMContentLoaded', () => {

    // Leer datos desde el objeto global
    const spots = window.explorerData.spots;
    let favorites = window.explorerData.favorites; // Mutable para actualizar localmente
    const csrfToken = window.explorerData.csrfToken;

    // Inicializar Mapa
    const map = L.map('map').setView([43.1, -2.5], 9);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap', maxZoom: 19
    }).addTo(map);

    const markersLayer = L.layerGroup().addTo(map);

    // Renderizar
    window.renderMap = function (typeFilter) {
        markersLayer.clearLayers();
        const listContainer = document.getElementById('spots-list-container');
        listContainer.innerHTML = '';

        spots.forEach(spot => {
            if (typeFilter !== 'all' && spot.tipo !== typeFilter) return;

            const isFav = favorites.includes(spot.id);
            const heartClass = isFav ? 'is-favorite' : '';
            const heartIcon = `<svg width="18" height="18" viewBox="0 0 24 24" fill="${isFav ? 'currentColor' : 'none'}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>`;

            const emoji = spot.tipo === 'playa' ? '🌊' : '⛰️';
            const colorClass = spot.tipo === 'playa' ? 'marker-playa' : 'marker-monte';

            // Marcador Mapa
            const customIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div class="custom-marker ${colorClass}">${emoji}</div>`,
                iconSize: [30, 30], iconAnchor: [15, 15], popupAnchor: [0, -15]
            });

            const marker = L.marker([spot.latitud, spot.longitud], { icon: customIcon })
                .bindPopup(`
                    <div style="text-align:center; min-width:140px;">
                        <h4 style="margin:0; color:#1e293b;">${spot.nombre}</h4>
                        <span style="font-size:0.8rem; color:#64748b;">${spot.municipio.nombre}</span><br>
                        <button class="btn-fav-action ${heartClass}" onclick="toggleFav(${spot.id}, this)" style="margin:5px auto 0 auto;">${heartIcon}</button>
                    </div>
                `);
            markersLayer.addLayer(marker);

            // Item Lista
            const item = document.createElement('div');
            item.className = 'spot-item';
            item.innerHTML = `
                <div style="display:flex; justify-content:space-between; align-items:start;">
                    <div onclick="flyToSpot(${spot.latitud}, ${spot.longitud})" style="flex:1;">
                        <h4>${emoji} ${spot.nombre}</h4>
                        <p>📍 ${spot.municipio.nombre}</p>
                    </div>
                    <button class="btn-fav-action ${heartClass}" onclick="toggleFav(${spot.id}, this)">${heartIcon}</button>
                </div>
            `;
            listContainer.appendChild(item);
        });
    };

    // Funciones globales para que el HTML pueda llamarlas
    window.flyToSpot = function (lat, lng) {
        map.flyTo([lat, lng], 14);
    };

    window.filterType = function (type, btn) {
        document.querySelectorAll('.btn-filter').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        renderMap(type);
    };

    window.toggleFav = function (spotId, btn) {
        // Detener propagación para no activar el click de "volar al mapa"
        if (event) event.stopPropagation();

        fetch(`/favoritos/toggle/${spotId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'added') {
                    btn.classList.add('is-favorite');
                    btn.querySelector('svg').setAttribute('fill', 'currentColor');
                    if (!favorites.includes(spotId)) favorites.push(spotId);
                } else {
                    btn.classList.remove('is-favorite');
                    btn.querySelector('svg').setAttribute('fill', 'none');
                    favorites = favorites.filter(id => id !== spotId);
                }
            })
            .catch(err => console.error(err));
    };

    // Render inicial
    renderMap('all');
});