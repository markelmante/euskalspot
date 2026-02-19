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

// Variables globales para almacenar los datos descargados
let globalWeatherData = null;
let globalMarineData = null;
let globalSpotType = null;

$(document).ready(function () {
    const config = window.spotConfig;

    if (!config) {
        console.error("Configuración de spot no encontrada.");
        return;
    }

    globalSpotType = config.tipo;

    // 1. Inicializar Tabs
    if ($("#tabs").length) {
        $("#tabs").tabs({
            activate: function (event, ui) {
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
        L.marker([config.lat, config.lng]).addTo(window.spotMap)
            .bindPopup(`<b>${config.nombre}</b><br>${config.tipo}`);
    }

    // 3. Cargar Datos del Clima
    fetchCompleteWeatherData(config);

    // 4. Lógica de Favoritos
    $('#btn-fav').click(function () {
        const btn = $(this);
        btn.toggleClass('active');
        $.ajax({
            url: config.urlFav,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': config.csrf },
            error: () => {
                btn.toggleClass('active');
                alert('Hubo un problema al guardar en favoritos.');
            }
        });
    });
});

async function fetchCompleteWeatherData(config) {
    const lat = config.lat.toFixed(4);
    const lng = config.lng.toFixed(4);

    // API Tiempo: Añadimos parámetros diarios de viento para la predicción futura
    let weatherUrl = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current_weather=true&daily=weathercode,temperature_2m_max,temperature_2m_min,precipitation_probability_max,uv_index_max,wind_speed_10m_max,wind_direction_10m_dominant&hourly=freezinglevel_height&timezone=auto`;

    let promises = [];
    promises.push($.getJSON(weatherUrl));

    if (config.tipo === 'playa') {
        // API Marina: Añadimos parámetros diarios de olas para la predicción futura
        let marineUrl = `https://marine-api.open-meteo.com/v1/marine?latitude=${lat}&longitude=${lng}&current=wave_height,wave_direction,wave_period&daily=wave_height_max,wave_direction_dominant,wave_period_max&timezone=auto`;
        promises.push($.getJSON(marineUrl));
    }

    try {
        const results = await Promise.all(promises);
        globalWeatherData = results[0];
        globalMarineData = (config.tipo === 'playa' && results[1]) ? results[1] : null;

        // Renderizamos el día 0 (Hoy) por defecto
        renderDashboard(0);
        renderForecastList();

    } catch (error) {
        console.error("Error obteniendo clima:", error);
        $('.w-desc').text("Info no disponible");
        $('.loading-spinner').remove();
        $('#main-temp').text("--");
    }
}

// Función principal que actualiza el Dashboard según el día seleccionado (index)
function renderDashboard(index) {
    const dailyW = globalWeatherData.daily;
    const currentW = globalWeatherData.current_weather;

    // Determinar si mostramos datos "en vivo" (Current) o predicción diaria
    // Si index es 0 (hoy), intentamos usar 'current' para mayor precisión, 
    // pero mezclado con daily para máximas.

    let temp, code, windSpeed, windDir, waveH, waveP, rainProb, uvIndex, freezingLvl;
    let titleText = "AHORA";

    // Fecha del día seleccionado
    const date = new Date(dailyW.time[index]);
    if (index === 0) {
        titleText = "AHORA";
        // Datos actuales (más precisos para el momento)
        temp = Math.round(currentW.temperature);
        code = currentW.weathercode;
        windSpeed = currentW.windspeed;
        windDir = currentW.winddirection;

        if (globalSpotType === 'playa' && globalMarineData && globalMarineData.current) {
            waveH = globalMarineData.current.wave_height;
            waveP = globalMarineData.current.wave_period;
        }
    } else {
        // Datos futuros (Usamos máximos diarios)
        const days = ['DOMINGO', 'LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO'];
        titleText = days[date.getDay()]; // Nombre del día

        temp = Math.round(dailyW.temperature_2m_max[index]);
        code = dailyW.weathercode[index];
        windSpeed = dailyW.wind_speed_10m_max[index];
        windDir = dailyW.wind_direction_10m_dominant[index];

        if (globalSpotType === 'playa' && globalMarineData) {
            waveH = globalMarineData.daily.wave_height_max[index];
            waveP = globalMarineData.daily.wave_period_max[index];
        }
    }

    // Datos comunes diarios (Rain, UV)
    rainProb = dailyW.precipitation_probability_max ? dailyW.precipitation_probability_max[index] : 0;
    uvIndex = dailyW.uv_index_max ? dailyW.uv_index_max[index] : 0;

    // Cota nieve: Es horaria. Aproximación: Tomamos el valor a las 12:00 del día (index * 24 + 12)
    const hourIndex = (index * 24) + 12;
    freezingLvl = (globalWeatherData.hourly && globalWeatherData.hourly.freezinglevel_height[hourIndex])
        ? Math.round(globalWeatherData.hourly.freezinglevel_height[hourIndex])
        : '--';

    // --- ACTUALIZAR DOM ---

    // 1. Cabecera Tarjeta
    $('.w-header').text(titleText);
    $('#main-temp').text(temp);
    $('#main-icon-container').html(getWeatherSVG(code));
    $('#weather-desc').text(getWeatherDesc(code));

    // 2. Stats Grid
    if (globalSpotType === 'playa' && globalMarineData) {
        updateStat(1, SVGS.wave, `${waveH} m`, 'Altura Olas');
        updateStat(2, SVGS.wind, `${waveP} s`, 'Periodo');
        updateStat(3, SVGS.wind, `${Math.round(windSpeed)} km/h`, 'Viento');
        updateStat(4, SVGS.compass, getDirection(windDir), 'Dirección');
    } else {
        updateStat(1, SVGS.umbrella, `${rainProb}%`, 'Prob. Lluvia');
        updateStat(2, SVGS.sun_alert, uvIndex, 'Índice UV');
        updateStat(3, SVGS.freezing, `${freezingLvl} m`, 'Cota Nieve');
        updateStat(4, SVGS.wind, `${Math.round(windSpeed)} km/h`, 'Viento');
    }
}

// Genera la lista de días abajo y añade el evento CLICK
function renderForecastList() {
    const daily = globalWeatherData.daily;
    let html = '';
    const days = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

    for (let i = 0; i < 7; i++) {
        if (!daily.time[i]) continue;
        const d = new Date(daily.time[i]);
        const dayName = days[d.getDay()];
        const icon = getWeatherSVG(daily.weathercode[i]);
        const max = Math.round(daily.temperature_2m_max[i]);
        const min = Math.round(daily.temperature_2m_min[i]);

        // Añadimos onclick="selectDay(i)" y una clase dinámica para el seleccionado
        html += `
            <div class="day-card" id="day-card-${i}" onclick="selectDay(${i})">
                <span class="day-name">${dayName}</span>
                <div class="day-icon" style="width:40px; margin:0 auto 10px auto">${icon}</div>
                <div class="day-temp">${max}°</div>
                <div class="day-sub">${min}°</div>
            </div>`;
    }
    $('#weekly-forecast').html(html);

    // Marcar el primero como seleccionado
    selectDay(0);
}

// Función que se llama al hacer click en un día
window.selectDay = function (index) {
    // 1. Actualizar Dashboard
    renderDashboard(index);

    // 2. Actualizar estilos visuales (borde azul)
    $('.day-card').removeClass('selected');
    $(`#day-card-${index}`).addClass('selected');
}

function updateStat(id, svg, val, label) {
    $(`#s${id}-icon`).html(svg);
    $(`#s${id}-val`).text(val);
    $(`#s${id}-label`).text(label);
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
    const dirs = ['Norte', 'Noreste', 'Este', 'Sureste', 'Sur', 'Suroeste', 'Oeste', 'Noroeste'];
    return dirs[Math.round(deg / 45) % 8];
}