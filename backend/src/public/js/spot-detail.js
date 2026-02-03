/* ==============================================
   SVG ICONS COLLECTION (Modern Line Style)
   ============================================== */
const SVGS = {
    sun: `<svg fill="none" viewBox="0 0 24 24" stroke="orange" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>`,
    cloud: `<svg fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path></svg>`,
    rain: `<svg fill="none" viewBox="0 0 24 24" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="16" y1="13" x2="16" y2="21"></line><line x1="8" y1="13" x2="8" y2="21"></line><line x1="12" y1="15" x2="12" y2="23"></line><path d="M20 16.58A5 5 0 0 0 18 7h-1.26A8 8 0 1 0 4 15.25"></path></svg>`,
    snow: `<svg fill="none" viewBox="0 0 24 24" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="8" x2="16" y2="16"></line><line x1="8" y1="16" x2="16" y2="8"></line><line x1="12" y1="2" x2="12" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line></svg>`,
    bolt: `<svg fill="none" viewBox="0 0 24 24" stroke="#eab308" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>`,
    wind: `<svg fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2"></path></svg>`,
    wave: `<svg fill="none" viewBox="0 0 24 24" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12c.6.5 1.2 1 2.5 1s2.5-.5 2.5-1 .6-1 2.5-1 2.5.5 2.5 1 .6 1 2.5 1 2.5-.5 2.5-1 .6-1 2.5-1 2.5.5 2.5 1 .6 1 2.5 1 2.5-.5 2.5-1"></path></svg>`,
    umbrella: `<svg fill="none" viewBox="0 0 24 24" stroke="#6366f1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12A10 10 0 0 0 12 2v10z"></path><path d="M2 12a10 10 0 0 1 10-10v10z"></path><path d="M12 22v-8"></path></svg>`,
    sun_alert: `<svg fill="none" viewBox="0 0 24 24" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><path d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.72 12.72 1.42 1.42M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path></svg>`,
    freezing: `<svg fill="none" viewBox="0 0 24 24" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18M5 10l14 0M5 14l14 0"></path></svg>`,
    compass: `<svg fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon></svg>`
};

$(document).ready(function () {
    const config = window.spotConfig;

    if (!config) {
        console.error("Configuración de spot no encontrada.");
        return;
    }

    // 1. Inicializar Tabs
    if ($("#tabs").length) {
        $("#tabs").tabs({
            activate: function (event, ui) {
                // Truco para que el mapa se pinte bien si estaba oculto
                if (ui.newPanel.attr('id') === 'tab-info' && window.spotMap) {
                    setTimeout(() => { window.spotMap.invalidateSize(); }, 200);
                }
            }
        });
    }

    // 2. Inicializar Mapa (Leaflet)
    if (document.getElementById('mini-map') && typeof L !== 'undefined') {
        window.spotMap = L.map('mini-map', { scrollWheelZoom: false }).setView([config.lat, config.lng], 13);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(window.spotMap);

        L.marker([config.lat, config.lng])
            .addTo(window.spotMap)
            .bindPopup(`<b>${config.nombre}</b><br>${config.tipo}`);
    }

    // 3. Cargar Datos del Clima
    fetchCompleteWeatherData(config);

    // 4. Lógica de Favoritos (AJAX)
    $('#btn-fav').click(function () {
        const btn = $(this);
        btn.toggleClass('active'); // Animación instantánea

        $.ajax({
            url: config.urlFav,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': config.csrf },
            error: () => {
                // Si falla, revertimos el cambio
                btn.toggleClass('active');
                alert('Hubo un problema al guardar en favoritos.');
            }
        });
    });
});

async function fetchCompleteWeatherData(config) {
    const lat = config.lat.toFixed(4);
    const lng = config.lng.toFixed(4);

    // Base URL
    let weatherUrl = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current_weather=true&daily=weathercode,temperature_2m_max,temperature_2m_min&timezone=auto`;
    let promises = [];

    if (config.tipo === 'playa') {
        // Datos extra para playas
        weatherUrl += `&hourly=windspeed_10m,winddirection_10m`;
        let marineUrl = `https://marine-api.open-meteo.com/v1/marine?latitude=${lat}&longitude=${lng}&current=wave_height,wave_direction,wave_period&daily=wave_height_max&timezone=auto`;

        promises.push($.getJSON(weatherUrl));
        promises.push($.getJSON(marineUrl));
    } else {
        // Datos extra para montes
        weatherUrl += `&daily=precipitation_probability_max,uv_index_max&hourly=freezinglevel_height`;
        promises.push($.getJSON(weatherUrl));
    }

    try {
        const results = await Promise.all(promises);
        const weatherData = results[0];
        const marineData = (config.tipo === 'playa' && results[1]) ? results[1] : null;

        renderUI(weatherData, marineData, config.tipo);

    } catch (error) {
        console.error("Error obteniendo clima:", error);
        $('.w-desc').text("Info no disponible");
        $('.loading-spinner').remove();
        $('#main-temp').text("--");
    }
}

function renderUI(weather, marine, tipo) {
    const current = weather.current_weather;
    const code = current.weathercode;

    // A. Main Card
    $('#main-temp').text(Math.round(current.temperature));
    $('#main-icon-container').html(getWeatherSVG(code));
    $('#weather-desc').text(getWeatherDesc(code));

    // B. Stats Grid
    if (tipo === 'playa' && marine && marine.current) {
        updateStat(1, SVGS.wave, `${marine.current.wave_height} m`, 'Altura Olas');
        updateStat(2, SVGS.wind, `${marine.current.wave_period} s`, 'Periodo');
        updateStat(3, SVGS.wind, `${current.windspeed} km/h`, 'Viento');
        updateStat(4, SVGS.compass, getDirection(current.winddirection), 'Dirección');
    } else {
        const daily = weather.daily;
        const rainProb = daily.precipitation_probability_max ? daily.precipitation_probability_max[0] : 0;
        const uv = daily.uv_index_max ? daily.uv_index_max[0] : 0;
        const hour = new Date().getHours();
        const freezing = weather.hourly && weather.hourly.freezinglevel_height ? Math.round(weather.hourly.freezinglevel_height[hour]) : '--';

        updateStat(1, SVGS.umbrella, `${rainProb}%`, 'Prob. Lluvia');
        updateStat(2, SVGS.sun_alert, uv, 'Índice UV');
        updateStat(3, SVGS.freezing, `${freezing} m`, 'Cota Nieve');
        updateStat(4, SVGS.wind, `${current.windspeed} km/h`, 'Viento');
    }

    // C. Forecast Semanal
    renderForecast(weather.daily);
}

function updateStat(id, svg, val, label) {
    $(`#s${id}-icon`).html(svg);
    $(`#s${id}-val`).text(val);
    $(`#s${id}-label`).text(label);
}

function renderForecast(daily) {
    let html = '';
    const days = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

    for (let i = 0; i < 7; i++) {
        if (!daily.time[i]) continue;
        const d = new Date(daily.time[i]);
        const dayName = days[d.getDay()];
        const icon = getWeatherSVG(daily.weathercode[i]);
        const max = Math.round(daily.temperature_2m_max[i]);
        const min = Math.round(daily.temperature_2m_min[i]);

        html += `
            <div class="day-card">
                <span class="day-name">${dayName}</span>
                <div class="day-icon" style="width:40px; margin:0 auto 10px auto">${icon}</div>
                <div class="day-temp">${max}°</div>
                <div class="day-sub">${min}°</div>
            </div>`;
    }
    $('#weekly-forecast').html(html);
}

function getWeatherSVG(code) {
    if (code === 0) return SVGS.sun;
    if (code >= 1 && code <= 3) return SVGS.cloud;
    if (code >= 45 && code <= 48) return SVGS.cloud;
    if (code >= 51 && code <= 67) return SVGS.rain;
    if (code >= 71 && code <= 77) return SVGS.snow;
    if (code >= 80 && code <= 82) return SVGS.rain;
    if (code >= 95) return SVGS.bolt;
    return SVGS.sun;
}

function getWeatherDesc(code) {
    const map = {
        0: 'Despejado', 1: 'Poco nuboso', 2: 'Nuboso', 3: 'Cubierto',
        45: 'Niebla', 51: 'Llovizna', 61: 'Lluvia', 63: 'Lluvia fuerte',
        71: 'Nieve', 95: 'Tormenta'
    };
    return map[code] || 'Variable';
}

function getDirection(deg) {
    const dirs = ['N', 'NE', 'E', 'SE', 'S', 'SO', 'O', 'NO'];
    return dirs[Math.round(deg / 45) % 8];
}