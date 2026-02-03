document.addEventListener('DOMContentLoaded', () => {
    const spots = window.explorerData.spots;
    let favorites = window.explorerData.favorites;
    const csrfToken = window.explorerData.csrfToken;

    // --- CONFIGURACIÓN DE ICONOS ---
    const icons = {
        playa: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:100%; height:100%;"><path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 6c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/></svg>`,
        monte: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:100%; height:100%;"><path d="m8 3 4 8 5-5 5 15H2L8 3z" /></svg>`
    };

    // --- INICIALIZAR MAPA ---
    const map = L.map('map').setView([43.1, -2.5], 9);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap',
        maxZoom: 19
    }).addTo(map);

    const markersLayer = L.layerGroup().addTo(map);

    // --- ESTADO ---
    let currentFilter = 'all';
    let currentSearch = '';

    // ==========================================
    // 1. RENDERIZADO DEL MAPA Y LISTA
    // ==========================================
    window.renderMap = function () {
        markersLayer.clearLayers();
        const listContainer = document.getElementById('spots-list-container');
        listContainer.innerHTML = '';

        // Filtrado Combinado (Tipo + Búsqueda)
        const filteredSpots = spots.filter(spot => {
            const matchesType = (currentFilter === 'all' || spot.tipo === currentFilter);
            const searchLower = currentSearch.toLowerCase();
            const matchesSearch = spot.nombre.toLowerCase().includes(searchLower) ||
                spot.municipio.nombre.toLowerCase().includes(searchLower);
            return matchesType && matchesSearch;
        });

        if (filteredSpots.length === 0) {
            listContainer.innerHTML = '<div style="text-align:center; margin-top:30px; color:#94a3b8;"><p>No se encontraron spots.</p></div>';
            return;
        }

        filteredSpots.forEach(spot => {
            // Datos básicos
            const isFav = favorites.includes(spot.id);
            const heartClass = isFav ? 'is-favorite' : '';
            const heartIcon = `<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>`;

            const isPlaya = spot.tipo === 'playa';
            const iconSvg = isPlaya ? icons.playa : icons.monte;
            const mapPinClass = isPlaya ? 'marker-playa' : 'marker-monte';
            const listIconBg = isPlaya ? 'bg-playa-soft' : 'bg-monte-soft';

            // --- MARCADOR EN MAPA ---
            const customIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `
                    <div class="custom-marker ${mapPinClass}" 
                         style="display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 50%; border: 2px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.3); color: #ffffff;">
                        <div style="width: 20px; height: 20px;">${iconSvg}</div>
                    </div>
                `,
                iconSize: [38, 38],
                iconAnchor: [19, 19],
                popupAnchor: [0, -20]
            });

            const marker = L.marker([spot.latitud, spot.longitud], {
                icon: customIcon
            });

            // AL CLICAR MARCADOR -> ABRIR DRAWER
            marker.on('click', () => {
                openSpotDrawer(spot);
            });
            markersLayer.addLayer(marker);

            // --- ELEMENTO DE LISTA SIDEBAR (Ahora con onclick en todo el item) ---
            const item = document.createElement('div');
            item.className = 'spot-item';

            // Evento click en toda la tarjeta
            item.onclick = (e) => {
                // Si el clic fue en el botón de favoritos, no abrimos el drawer
                if (e.target.closest('.btn-fav-action')) return;
                focusOnSpot(spot.id);
            };

            item.innerHTML = `
                <div class="spot-info-left" style="flex:1;">
                    <div class="spot-icon-wrapper ${listIconBg}">
                        ${iconSvg}
                    </div>
                    <div class="spot-text">
                        <h4>${spot.nombre}</h4>
                        <p>${spot.municipio.nombre}</p>
                    </div>
                </div>
                <button class="btn-fav-action ${heartClass}" onclick="toggleFav(${spot.id}, this)">${heartIcon}</button>
            `;
            listContainer.appendChild(item);
        });
    };

    // ==========================================
    // 2. LÓGICA DEL DRAWER (PANEL LATERAL)
    // ==========================================
    window.openSpotDrawer = function (spot) {
        const drawer = document.getElementById('spotDrawer');
        const isPlaya = spot.tipo === 'playa';

        // Foto de fondo (fallback a gradiente)
        const bgStyle = spot.foto ?
            `background-image: url('/storage/${spot.foto}');` :
            `background: linear-gradient(135deg, ${isPlaya ? '#DBEAFE' : '#D1FAE5'} 0%, #F1F5F9 100%);`;

        const typeLabel = isPlaya ? 'Playa / Surf' : 'Montaña / Hike';

        drawer.innerHTML = `
            <div class="drawer-header" style="${bgStyle}">
                <button class="btn-close-drawer" onclick="closeSpotDrawer()">×</button>
            </div>
            <div class="drawer-body">
                <h2 class="drawer-title">${spot.nombre}</h2>
                <div class="drawer-subtitle">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    ${spot.municipio.nombre} • ${typeLabel}
                </div>

                <div id="weather-widget-${spot.id}" class="weather-widget">
                    <div style="flex:1; display:flex; align-items:center; gap:10px;">
                        <span class="weather-temp">--°</span>
                        <div class="weather-info">Cargando<br>tiempo...</div>
                    </div>
                </div>

                <div class="drawer-desc">
                    <strong>Sobre este lugar:</strong><br>
                    ${spot.descripcion || 'Sin descripción disponible.'}
                </div>

                <div class="drawer-actions">
                    <a href="/spots/${spot.id}" class="btn-details-full">
                        Ver Ficha Completa
                    </a>
                    
                    <a href="https://www.google.com/maps?q=${spot.latitud},${spot.longitud}" 
                       target="_blank" class="btn-directions-map">
                       📍 Mapa
                    </a>
                </div>

            </div>
        `;

        // Abrir Drawer y mover mapa
        drawer.classList.add('open');
        map.flyTo([spot.latitud, spot.longitud], 14, {
            duration: 1.2
        });

        // Llamar a API Tiempo
        fetchWeather(spot.latitud, spot.longitud, `weather-widget-${spot.id}`);
    };

    window.closeSpotDrawer = function () {
        document.getElementById('spotDrawer').classList.remove('open');
    };

    window.focusOnSpot = function (id) {
        const spot = spots.find(s => s.id === id);
        if (spot) openSpotDrawer(spot);
    };

    // ==========================================
    // 3. API DEL TIEMPO (Open-Meteo)
    // ==========================================
    async function fetchWeather(lat, lng, elementId) {
        try {
            const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current_weather=true`;
            const response = await fetch(url);
            const data = await response.json();

            const temp = Math.round(data.current_weather.temperature);
            const wind = data.current_weather.windspeed;
            const code = data.current_weather.weathercode;

            // Iconos simples segun codigo WMO
            let emoji = '☀️';
            if (code > 3) emoji = '☁️';
            if (code > 45) emoji = '🌫️';
            if (code > 50) emoji = '🌧️';
            if (code > 90) emoji = '⚡';

            const widget = document.getElementById(elementId);
            if (widget) {
                widget.innerHTML = `
                    <div style="font-size:2.5rem;">${emoji}</div>
                    <div>
                        <div class="weather-temp">${temp}°C</div>
                        <div class="weather-info">Viento: ${wind} km/h</div>
                    </div>
                `;
            }
        } catch (e) {
            console.error("Error API tiempo", e);
        }
    }

    // ==========================================
    // 4. EVENT LISTENERS Y UTILIDADES
    // ==========================================

    // Filtrar por Tipo
    window.filterType = function (type, btn) {
        document.querySelectorAll('.btn-filter').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentFilter = type;
        renderMap();
    };

    // Buscador
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            currentSearch = e.target.value;
            renderMap();
        });
    }

    // Toggle Favorito
    window.toggleFav = function (spotId, btn) {
        if (event) event.stopPropagation(); // Evita que se abra el drawer si hay conflicto
        fetch(`/favoritos/toggle/${spotId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        }).then(res => res.json()).then(data => {
            if (data.status === 'added') {
                btn.classList.add('is-favorite');
                if (!favorites.includes(spotId)) favorites.push(spotId);
            } else {
                btn.classList.remove('is-favorite');
                favorites = favorites.filter(id => id !== spotId);
            }
        });
    };

    // Renderizado Inicial
    renderMap();
});