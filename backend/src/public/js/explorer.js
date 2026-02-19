document.addEventListener('DOMContentLoaded', () => {
    // Recuperamos datos
    const spots = window.explorerData.spots;
    let favorites = window.explorerData.favorites;
    const csrfToken = window.explorerData.csrfToken;

    // ==========================================
    // 0. DEFINICIÓN DE ICONOS SVG
    // ==========================================
    const SVGS = {
        sun: `<svg fill="none" viewBox="0 0 24 24" stroke="orange" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:100%;height:100%"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>`,
        cloud: `<svg fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:100%;height:100%"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path></svg>`,
        rain: `<svg fill="none" viewBox="0 0 24 24" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:100%;height:100%"><line x1="16" y1="13" x2="16" y2="21"></line><line x1="8" y1="13" x2="8" y2="21"></line><line x1="12" y1="15" x2="12" y2="23"></line><path d="M20 16.58A5 5 0 0 0 18 7h-1.26A8 8 0 1 0 4 15.25"></path></svg>`,
        snow: `<svg fill="none" viewBox="0 0 24 24" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:100%;height:100%"><line x1="8" y1="8" x2="16" y2="16"></line><line x1="8" y1="16" x2="16" y2="8"></line><line x1="12" y1="2" x2="12" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line></svg>`,
        bolt: `<svg fill="none" viewBox="0 0 24 24" stroke="#eab308" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:100%;height:100%"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>`,

        // UI Icons
        playa: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:100%; height:100%;"><path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 6c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/></svg>`,
        monte: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:100%; height:100%;"><path d="m8 3 4 8 5-5 5 15H2L8 3z" /></svg>`,
        mapPin: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:100%; height:100%;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>`,
        heart: `<svg viewBox="0 0 24 24" fill="currentColor" stroke="none" style="width:100%;height:100%;"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>`
    };

    function getWeatherSVG(code) {
        if (code === 0) return SVGS.sun;
        if (code >= 1 && code <= 3) return SVGS.cloud;
        if (code >= 45 && code <= 48) return SVGS.cloud;
        if (code >= 51 && code <= 67) return SVGS.rain;
        if (code >= 71 && code <= 77) return SVGS.snow;
        if (code >= 80 && code <= 82) return SVGS.rain;
        if (code >= 95 && code <= 99) return SVGS.bolt;
        return SVGS.sun;
    }

    // --- INICIALIZAR MAPA ---
    const map = L.map('map').setView([43.1, -2.5], 9);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap',
        maxZoom: 19
    }).addTo(map);

    const markersLayer = L.layerGroup().addTo(map);

    // --- ESTADO ---
    let currentFilterType = 'all'; // 'all', 'playa', 'monte'
    let currentFilterTags = []; // <--- CAMBIO: Ahora es un array para múltiples etiquetas
    let currentSearch = '';

    // ==========================================
    // PROCESAR IMAGEN
    // ==========================================
    function resolveSpotImage(spot) {
        let imgSrc = null;
        if (spot.foto) {
            let rawFoto = spot.foto;
            if (typeof rawFoto === 'string') {
                try {
                    const parsed = JSON.parse(rawFoto);
                    rawFoto = Array.isArray(parsed) ? parsed[0] : parsed;
                } catch (e) { }
            } else if (Array.isArray(rawFoto)) {
                rawFoto = rawFoto[0];
            }
            if (rawFoto) imgSrc = `/storage/${rawFoto.replace(/\\/g, '/')}`;
        }
        if (!imgSrc) {
            const isPlaya = spot.tipo && spot.tipo.toLowerCase() === 'playa';
            imgSrc = isPlaya ?
                "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80" :
                "https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=800&q=80";
        }
        return imgSrc;
    }

    // ==========================================
    // 1. RENDERIZADO DEL MAPA Y LISTA
    // ==========================================
    window.renderMap = function () {
        markersLayer.clearLayers();
        const listContainer = document.getElementById('spots-list-container');
        listContainer.innerHTML = '';

        // --- LÓGICA DE FILTRADO COMBINADA ---
        const filteredSpots = spots.filter(spot => {
            // 1. Filtro Tipo
            const matchesType = (currentFilterType === 'all' || spot.tipo === currentFilterType);

            // 2. Filtro Buscador
            const searchLower = currentSearch.toLowerCase();
            const matchesSearch = spot.nombre.toLowerCase().includes(searchLower) ||
                spot.municipio.nombre.toLowerCase().includes(searchLower);

            // 3. Filtro Múltiples Etiquetas (NUEVO)
            let matchesTag = true;
            if (currentFilterTags.length > 0) {
                if (!spot.etiquetas || !Array.isArray(spot.etiquetas)) {
                    matchesTag = false;
                } else {
                    // .every() asegura que el spot tenga TODAS las etiquetas seleccionadas
                    matchesTag = currentFilterTags.every(selectedTagId =>
                        spot.etiquetas.some(spotTag => spotTag.id === selectedTagId)
                    );
                }
            }

            return matchesType && matchesSearch && matchesTag;
        });

        if (filteredSpots.length === 0) {
            listContainer.innerHTML = '<div style="text-align:center; margin-top:30px; color:#94a3b8;"><p>No se encontraron spots con estos filtros.</p></div>';
            return;
        }

        filteredSpots.forEach(spot => {
            const isFav = favorites.includes(spot.id);
            const heartClass = isFav ? 'is-favorite' : '';

            const isPlaya = spot.tipo === 'playa';
            const iconSvg = isPlaya ? SVGS.playa : SVGS.monte;
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

            marker.on('click', () => {
                openSpotDrawer(spot);
            });
            markersLayer.addLayer(marker);

            // --- LISTA LATERAL ---
            const item = document.createElement('div');
            item.className = 'spot-item';
            item.onclick = (e) => {
                if (e.target.closest('.btn-fav-action')) return;
                focusOnSpot(spot.id);
            };

            let tagsHtml = '';
            if (spot.etiquetas && spot.etiquetas.length > 0) {
                const tagsToShow = spot.etiquetas.slice(0, 2);
                tagsHtml = `<div style="display:flex; gap:4px; margin-top:4px;">
                    ${tagsToShow.map(t => `<span style="font-size:0.65rem; background:#f1f5f9; padding:2px 6px; border-radius:4px; color:#64748b;">${t.nombre}</span>`).join('')}
                    ${spot.etiquetas.length > 2 ? '<span style="font-size:0.65rem; color:#94a3b8;">+</span>' : ''}
                 </div>`;
            }

            item.innerHTML = `
                <div class="spot-info-left" style="flex:1;">
                    <div class="spot-icon-wrapper ${listIconBg}">
                        ${iconSvg}
                    </div>
                    <div class="spot-text">
                        <h4>${spot.nombre}</h4>
                        <p>${spot.municipio.nombre}</p>
                        ${tagsHtml}
                    </div>
                </div>
                <button class="btn-fav-action ${heartClass}" onclick="toggleFav(${spot.id}, this)" data-spot-id="${spot.id}">
                    <div style="width: 18px; height: 18px;">${SVGS.heart}</div>
                </button>
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
        const imageUrl = resolveSpotImage(spot);
        const typeLabel = isPlaya ? 'Playa / Surf' : 'Montaña / Hike';

        const googleMapsUrl = `https://www.google.com/maps/search/?api=1&query=$$${spot.latitud},${spot.longitud}`;

        const isFav = favorites.includes(spot.id);
        const favClass = isFav ? 'is-favorite' : '';

        // Renderizar etiquetas en el drawer
        let tagsDrawerHtml = '';
        if (spot.etiquetas && spot.etiquetas.length > 0) {
            tagsDrawerHtml = `<div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:15px;">
                ${spot.etiquetas.map(t =>
                `<span style="background:#e2e8f0; padding:4px 10px; border-radius:15px; font-size:0.75rem; color:#475569;">#${t.nombre}</span>`
            ).join('')}
            </div>`;
        }

        drawer.innerHTML = `
            <div class="drawer-header" style="background-image: url('${imageUrl}');">
                <button class="btn-close-drawer" onclick="closeSpotDrawer()">×</button>
            </div>
            <div class="drawer-body">
                <h2 class="drawer-title">${spot.nombre}</h2>
                <div class="drawer-subtitle">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    ${spot.municipio.nombre} • ${typeLabel}
                </div>

                ${tagsDrawerHtml}

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
                    <button class="btn-drawer-fav ${favClass}" onclick="toggleFav(${spot.id}, this)" data-spot-id="${spot.id}">
                        <div style="width: 24px; height: 24px;">${SVGS.heart}</div>
                    </button>

                    <a href="/spots/${spot.id}" class="btn-details-full">
                        Ver Ficha
                    </a>
                      
                    <a href="${googleMapsUrl}" target="_blank" class="btn-directions-map">
                        <div style="width: 20px; height: 20px;">${SVGS.mapPin}</div>
                        Mapa
                    </a>
                </div>
            </div>
        `;

        drawer.classList.add('open');
        map.flyTo([spot.latitud, spot.longitud], 14, {
            duration: 1.2
        });
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
            const iconSvg = getWeatherSVG(code);

            const widget = document.getElementById(elementId);
            if (widget) {
                widget.innerHTML = `
                    <div style="width: 50px; height: 50px; flex-shrink: 0;">${iconSvg}</div>
                    <div>
                        <div class="weather-temp">${temp}°C</div>
                        <div class="weather-info">Viento: ${wind} km/h</div>
                    </div>
                `;
            }
        } catch (e) {
            console.error("Error API tiempo", e);
            const widget = document.getElementById(elementId);
            if (widget) widget.innerHTML = '<span style="color:red; font-size:0.8rem">Error clima</span>';
        }
    }

    // ==========================================
    // 4. EVENT LISTENERS (FILTROS)
    // ==========================================

    window.filterType = function (type, btn) {
        const typeButtons = btn.parentNode.querySelectorAll('.btn-filter');
        typeButtons.forEach(b => b.classList.remove('active'));

        btn.classList.add('active');
        currentFilterType = type;
        renderMap();
    };

    // Filtro por Múltiples Etiquetas (CAMBIO APLICADO)
    window.filterTag = function (tagId, btn) {
        const index = currentFilterTags.indexOf(tagId);

        if (index > -1) {
            // Si el tag ya estaba seleccionado, lo quitamos del array y le quitamos la clase active
            currentFilterTags.splice(index, 1);
            btn.classList.remove('active');
        } else {
            // Si no estaba seleccionado, lo añadimos al array y le ponemos la clase active
            currentFilterTags.push(tagId);
            btn.classList.add('active');
        }

        renderMap();
    };

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            currentSearch = e.target.value;
            renderMap();
        });
    }

    // ==========================================
    // 5. LÓGICA DE FAVORITOS
    // ==========================================
    window.toggleFav = function (spotId, btn) {
        if (event) event.stopPropagation();

        const urlTemplate = window.explorerData.urlFavTemplate;
        const url = urlTemplate.replace('PLACEHOLDER', spotId);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        }).then(res => res.json()).then(data => {

            if (data.status === 'added') {
                if (!favorites.includes(spotId)) favorites.push(spotId);
            } else {
                favorites = favorites.filter(id => id !== spotId);
            }

            const allButtons = document.querySelectorAll(`button[data-spot-id="${spotId}"]`);

            allButtons.forEach(button => {
                if (data.status === 'added') {
                    button.classList.add('is-favorite');
                } else {
                    button.classList.remove('is-favorite');
                }
            });

        }).catch(err => console.error(err));
    };

    renderMap();
});