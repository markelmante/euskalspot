/**
 * DASHBOARD.JS
 * Ubicación: public/js/dashboard.js
 * Versión: Pro + SVG Icons + Modales
 */

document.addEventListener('DOMContentLoaded', function () {
    console.log("✅ Dashboard JS Cargado (Versión SVG)");

    // 1. INYECTAR MODALES (Confirmación y Selector de Día)
    injectConfirmModal();
    injectDaySelectorModal();

    // ==========================================
    // DEFINICIÓN DE ICONOS SVG
    // ==========================================
    const SVGS = {
        // CLIMA
        sun: `<svg fill="none" viewBox="0 0 24 24" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>`,
        cloud: `<svg fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path></svg>`,
        rain: `<svg fill="none" viewBox="0 0 24 24" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="16" y1="13" x2="16" y2="21"></line><line x1="8" y1="13" x2="8" y2="21"></line><line x1="12" y1="15" x2="12" y2="23"></line><path d="M20 16.58A5 5 0 0 0 18 7h-1.26A8 8 0 1 0 4 15.25"></path></svg>`,
        snow: `<svg fill="none" viewBox="0 0 24 24" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="8" x2="16" y2="16"></line><line x1="8" y1="16" x2="16" y2="8"></line><line x1="12" y1="2" x2="12" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line></svg>`,
        bolt: `<svg fill="none" viewBox="0 0 24 24" stroke="#eab308" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>`,
        wind: `<svg fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2"></path></svg>`,
        drop: `<svg fill="none" viewBox="0 0 24 24" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path></svg>`,
        arrow: `<svg class="wind-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>`,

        // UI ICONS
        calendarPlus: `<svg fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><line x1="12" y1="14" x2="12" y2="14"></line><line x1="12" y1="18" x2="12" y2="18"></line></svg>`,
        check: `<svg fill="none" viewBox="0 0 24 24" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>`,
        plus: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>`,
        close: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`,
        trash: `<svg fill="none" viewBox="0 0 24 24" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:40px;height:40px;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>`,
        warning: `<svg fill="none" viewBox="0 0 24 24" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:40px;height:40px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>`,
        heartBroken: `<svg fill="none" viewBox="0 0 24 24" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:40px;height:40px;"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path><line x1="12" y1="5" x2="12" y2="21"></line></svg>`
    };

    // Exponer iconos globalmente para los modales
    window.DashboardIcons = SVGS;

    // ==========================================
    // 2. FLATPICKR (Calendario Selector)
    // ==========================================
    if (window.dashboardConfig && document.getElementById("modernDatepicker")) {
        flatpickr("#modernDatepicker", {
            locale: "es",
            defaultDate: window.dashboardConfig.currentDate,
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "j F Y",
            disableMobile: "true",
            onChange: function (selectedDates, dateStr) {
                window.location.href = '?date=' + dateStr;
            }
        });
    }

    // ==========================================
    // 3. GESTIÓN DE FECHAS (BLOQUEO PASADO)
    // ==========================================
    const dropzones = document.querySelectorAll('.day-column');

    window.isDateInPast = function (dateStr) {
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Resetear hora para comparar solo fecha
        const targetDate = new Date(dateStr);
        targetDate.setHours(0, 0, 0, 0);
        return targetDate < today;
    };

    // Marcar visualmente días pasados al cargar
    dropzones.forEach(zone => {
        const dateStr = zone.dataset.date;
        if (isDateInPast(dateStr)) {
            zone.classList.add('past-day');
            zone.title = "No puedes planificar en el pasado";
        }
    });

    // ==========================================
    // 4. DRAG & DROP
    // ==========================================
    const draggables = document.querySelectorAll('.draggable-item');

    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', (e) => {
            draggable.classList.add('dragging');

            let type = draggable.dataset.type || 'monte';
            if (draggable.classList.contains('type-playa')) type = 'playa';

            const dragData = {
                id: draggable.dataset.id,
                planId: draggable.dataset.planId,
                origin: draggable.dataset.origin,
                type: type,
                lat: draggable.dataset.lat,
                lon: draggable.dataset.lon,
                nombre: draggable.querySelector('strong') ? draggable.querySelector('strong').innerText : ''
            };
            e.dataTransfer.setData('application/json', JSON.stringify(dragData));
            e.dataTransfer.effectAllowed = "copyMove";
        });

        draggable.addEventListener('dragend', () => {
            draggable.classList.remove('dragging');
        });
    });

    dropzones.forEach(zone => {
        zone.addEventListener('dragover', (e) => {
            e.preventDefault();
            if (zone.classList.contains('past-day')) {
                e.dataTransfer.dropEffect = "none";
                return;
            }
            zone.classList.add('drag-over');
        });

        zone.addEventListener('dragleave', () => {
            zone.classList.remove('drag-over');
        });

        zone.addEventListener('drop', (e) => {
            e.preventDefault();
            zone.classList.remove('drag-over');

            const fechaDestino = zone.dataset.date;
            if (isDateInPast(fechaDestino)) {
                showSimpleAlert("Fecha Inválida", "No puedes viajar al pasado. Elige una fecha futura.");
                return;
            }

            const targetZone = e.target.closest('.dropzone');
            if (!targetZone) return;

            const rawData = e.dataTransfer.getData('application/json');
            if (!rawData) return;
            const data = JSON.parse(rawData);

            if (data.id && fechaDestino) {
                // UI temporal para feedback instantáneo
                const dayBody = targetZone.querySelector('.day-body');
                const tempCard = document.createElement('div');
                const colorClass = (data.type === 'playa' || data.type === 'surf') ? 'type-playa' : 'type-monte';

                tempCard.className = `spot-card-calendar draggable-item temp-loading ${colorClass}`;
                tempCard.innerHTML = `<div class="spot-content"><strong>${data.nombre}</strong></div><small>Guardando...</small>`;
                dayBody.appendChild(tempCard);

                if (data.origin === 'plan' && data.planId) {
                    movePlanToDate(data.planId, fechaDestino);
                } else {
                    savePlanToBackend(data.id, fechaDestino);
                }
            }
        });
    });

    // ==========================================
    // 5. API DEL TIEMPO (Weather Widget)
    // ==========================================
    function updateWeatherWidget(widget) {
        const lat = widget.dataset.lat;
        const lon = widget.dataset.lon;
        const date = widget.dataset.date;
        const spotType = widget.dataset.type ? widget.dataset.type.toLowerCase() : 'monte';

        if (!lat || !lon || !date) {
            widget.innerHTML = '<small style="color:#999">Faltan datos</small>';
            return;
        }

        const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&daily=weather_code,temperature_2m_max,temperature_2m_min,wind_speed_10m_max,wind_direction_10m_dominant,precipitation_probability_max&timezone=auto&start_date=${date}&end_date=${date}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.daily && data.daily.time && data.daily.time.length > 0) {
                    const tempMax = Math.round(data.daily.temperature_2m_max[0]);
                    const tempMin = Math.round(data.daily.temperature_2m_min[0]);
                    const weatherCode = data.daily.weather_code[0];
                    const iconSvg = getWeatherSVG(weatherCode);
                    const windSpeed = Math.round(data.daily.wind_speed_10m_max[0]);
                    const windDir = data.daily.wind_direction_10m_dominant[0];
                    const rainProb = data.daily.precipitation_probability_max[0];
                    let bottomRowHtml = '';

                    if (spotType === 'playa' || spotType === 'surf') {
                        const arrowStyle = `transform: rotate(${windDir + 180}deg); display:inline-block; width:12px; height:12px; transition:transform 0.5s;`;
                        bottomRowHtml = `
                            <div class="weather-row-detail" title="Viento: ${windSpeed} km/h">
                                <span class="detail-label">VIENTO</span>
                                <div class="detail-value">
                                    <div style="${arrowStyle}">${SVGS.arrow}</div>
                                    <span>${windSpeed}<small>km/h</small></span>
                                </div>
                            </div>
                        `;
                    } else {
                        const precipColor = rainProb > 30 ? '#3b82f6' : '#94a3b8';
                        bottomRowHtml = `
                            <div class="weather-row-detail" title="Probabilidad Precipitación">
                                <span class="detail-label">LLUVIA</span>
                                <div class="detail-value" style="color:${precipColor}">
                                    <div style="width:12px; height:12px;">${SVGS.drop}</div>
                                    <span>${rainProb}<small>%</small></span>
                                </div>
                            </div>
                        `;
                    }

                    widget.innerHTML = `
                        <div class="weather-main-row">
                            <div class="weather-icon-box">${iconSvg}</div>
                            <div class="temp-column">
                                <span class="t-max">${tempMax}°</span>
                                <span class="t-min">${tempMin}°</span>
                            </div>
                        </div>
                        <div class="weather-divider"></div>
                        ${bottomRowHtml}
                    `;
                    widget.classList.remove('loading-pulse');
                } else {
                    widget.innerHTML = '<span style="font-size:0.7rem; color:#ccc;">Sin datos</span>';
                }
            })
            .catch(err => {
                console.error("Error clima:", err);
                widget.innerHTML = '<span style="font-size:0.8rem; color:#cbd5e1;">N/A</span>';
            });
    }

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

    const weatherWidgets = document.querySelectorAll('.weather-widget');
    weatherWidgets.forEach(widget => updateWeatherWidget(widget));

}); // FIN DOM CONTENT LOADED

// ==========================================
// 6. FUNCIONES GLOBALES (FUERA DEL DOM LOAD)
// ==========================================

/* --- INJECTION: Confirm Modal (Editado para SVG) --- */
function injectConfirmModal() {
    if (!document.getElementById('customConfirmModal')) {
        const modalHTML = `
        <div id="customConfirmModal" class="modal-overlay">
            <div class="modal-box">
                <button class="btn-close-modal" style="top:10px; right:15px;" onclick="closeConfirmModal()">
                    ${window.DashboardIcons ? window.DashboardIcons.close : '×'}
                </button>
                <div class="confirm-content">
                    <div id="confirmIcon" class="confirm-icon" style="margin-bottom:15px; display:flex; justify-content:center;">
                        </div>
                    <h3 id="confirmTitle" class="confirm-title">¿Estás seguro?</h3>
                    <p id="confirmText" class="confirm-text">Esta acción no se puede deshacer.</p>
                    <div class="confirm-actions" id="confirmActionsRow">
                        <button id="btnConfirmCancel" class="btn-confirm-cancel" onclick="closeConfirmModal()">Cancelar</button>
                        <button id="btnConfirmYes" class="btn-confirm-yes">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>`;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }
}

/* --- INJECTION: Day Selector Modal (Editado para SVG) --- */
function injectDaySelectorModal() {
    if (!document.getElementById('daySelectorModal')) {
        const closeIcon = window.DashboardIcons ? window.DashboardIcons.close : '×';
        const modalHTML = `
        <div id="daySelectorModal" class="modal-overlay">
            <div class="modal-box modal-box-lg">
                <div class="modal-header">
                    <div class="modal-icon-bg" id="modalDayIcon"></div>
                    <div>
                        <h3 class="modal-title">Planificar Aventura</h3>
                        <p class="modal-subtitle">Elige un día para: <strong id="modalSpotName">...</strong></p>
                    </div>
                    <button class="btn-close-modal" onclick="closeDayModal()">${closeIcon}</button>
                </div>
                
                <div class="day-selector-grid" id="daySelectorGrid">
                    </div>
            </div>
        </div>`;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }
}

// --- LOGIC: Day Selector (Botón Sidebar) ---
window.currentSpotToAdd = null;

// Esta función es la que llamas desde el onclick="openModal(...)"
window.openModal = function (spotName, spotId) {
    window.currentSpotToAdd = spotId;

    // Asegurar que el modal existe
    if (!document.getElementById('daySelectorModal')) injectDaySelectorModal();

    // Configurar textos
    document.getElementById('modalSpotName').innerText = spotName;
    const iconContainer = document.getElementById('modalDayIcon');
    if (window.DashboardIcons) iconContainer.innerHTML = window.DashboardIcons.calendarPlus;

    // Limpiar grid
    const grid = document.getElementById('daySelectorGrid');
    grid.innerHTML = '';

    // Buscar las columnas de días visibles en el calendario
    const visibleDays = document.querySelectorAll('.day-column');

    if (visibleDays.length === 0) {
        grid.innerHTML = '<p style="text-align:center; color: #94a3b8;">No hay días visibles. Cambia de vista.</p>';
    }

    visibleDays.forEach(dayColumn => {
        const dateStr = dayColumn.dataset.date;
        const dayName = dayColumn.querySelector('.day-name') ? dayColumn.querySelector('.day-name').innerText : '';
        const dayNumber = dayColumn.querySelector('.day-number') ? dayColumn.querySelector('.day-number').innerText : '';
        const isPast = window.isDateInPast ? window.isDateInPast(dateStr) : false;

        const btn = document.createElement('button');
        btn.className = `btn-select-day ${isPast ? 'disabled' : ''}`;

        // Contenido del botón (Usa SVG de Plus o Check)
        const actionIcon = window.DashboardIcons ? window.DashboardIcons.plus : '+';

        btn.innerHTML = `
            <span class="d-name">${dayName}</span>
            <span class="d-num">${dayNumber}</span>
            ${!isPast ? '<div class="hover-icon">' + actionIcon + '</div>' : ''}
        `;

        if (!isPast) {
            btn.onclick = function () {
                savePlanToBackend(window.currentSpotToAdd, dateStr);
                closeDayModal();
            };
        } else {
            btn.title = "Día pasado";
            btn.disabled = true;
        }

        grid.appendChild(btn);
    });

    // Mostrar modal
    document.getElementById('daySelectorModal').classList.add('active');
};

window.closeDayModal = function () {
    const modal = document.getElementById('daySelectorModal');
    if (modal) modal.classList.remove('active');
    window.currentSpotToAdd = null;
};

// --- LOGIC: Confirm Modal (Ahora soporta SVG Strings) ---
window.showConfirmModal = function (title, text, iconSvg, onConfirm) {
    injectConfirmModal(); // Asegurar existencia
    const modal = document.getElementById('customConfirmModal');

    document.getElementById('confirmTitle').innerText = title;
    document.getElementById('confirmText').innerText = text;

    // Inyectar SVG en lugar de Texto
    const iconContainer = document.getElementById('confirmIcon');
    iconContainer.innerHTML = iconSvg || (window.DashboardIcons ? window.DashboardIcons.warning : '⚠️');

    // Restaurar botones (por si venimos de un alert simple)
    const btnCancel = document.getElementById('btnConfirmCancel');
    const btnYes = document.getElementById('btnConfirmYes');

    btnCancel.style.display = 'inline-block';
    btnYes.style.display = 'inline-block';
    btnYes.innerText = 'Confirmar';

    // Clonar para limpiar eventos anteriores
    const newBtn = btnYes.cloneNode(true);
    btnYes.parentNode.replaceChild(newBtn, btnYes);

    newBtn.addEventListener('click', function () {
        if (onConfirm) onConfirm();
        closeConfirmModal();
    });

    modal.classList.add('active');
};

// Nueva función para reemplazar alert() nativo con el Modal bonito
window.showSimpleAlert = function (title, text) {
    injectConfirmModal();
    const modal = document.getElementById('customConfirmModal');

    document.getElementById('confirmTitle').innerText = title;
    document.getElementById('confirmText').innerText = text;

    // Icono de Warning por defecto
    document.getElementById('confirmIcon').innerHTML = window.DashboardIcons ? window.DashboardIcons.warning : '⚠️';

    // Ocultar botón cancelar y cambiar texto del botón aceptar
    document.getElementById('btnConfirmCancel').style.display = 'none';

    const btnYes = document.getElementById('btnConfirmYes');
    const newBtn = btnYes.cloneNode(true);
    btnYes.parentNode.replaceChild(newBtn, btnYes);

    newBtn.innerText = 'Entendido';
    newBtn.addEventListener('click', closeConfirmModal);

    modal.classList.add('active');
}

window.closeConfirmModal = function () {
    const modal = document.getElementById('customConfirmModal');
    if (modal) modal.classList.remove('active');
};

// --- ACCIONES BACKEND (FETCH) ---

window.savePlanToBackend = function (spotId, fecha) {
    if (!window.dashboardConfig) { console.error("Falta dashboardConfig"); return; }
    const { csrfToken, storeUrl } = window.dashboardConfig;

    fetch(storeUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ spot_id: spotId, fecha: fecha })
    })
        .then(r => r.json())
        .then(data => {
            if (data.success || data.plan) window.location.reload();
            else {
                showSimpleAlert('Error', 'No se pudo guardar el plan.');
                setTimeout(() => window.location.reload(), 1500);
            }
        })
        .catch(err => { console.error(err); showSimpleAlert('Error', 'Error de conexión'); });
};

window.movePlanToDate = function (planId, nuevaFecha) {
    if (!window.dashboardConfig) return;
    const { csrfToken, updateBaseUrl } = window.dashboardConfig;

    fetch(`${updateBaseUrl}/${planId}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ fecha: nuevaFecha })
    })
        .then(r => r.json())
        .then(data => window.location.reload())
        .catch(err => window.location.reload());
};

window.deletePlan = function (planId) {
    showConfirmModal(
        "¿Eliminar Plan?",
        "Este plan se eliminará de tu calendario permanentemente.",
        window.DashboardIcons.trash, // SVG Papelera
        function () {
            if (!window.dashboardConfig) return;
            const { csrfToken, updateBaseUrl } = window.dashboardConfig;

            fetch(`${updateBaseUrl}/${planId}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const card = document.getElementById(`plan-${planId}`);
                        if (card) {
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.8)';
                            setTimeout(() => card.remove(), 300);
                        } else {
                            window.location.reload();
                        }
                    } else {
                        showSimpleAlert("Error", "No se pudo eliminar el plan.");
                    }
                })
                .catch(err => console.error(err));
        }
    );
};

window.removeFavorite = function (spotId) {
    showConfirmModal(
        "¿Quitar Favorito?",
        "Dejarás de tener acceso rápido a este spot en la barra lateral.",
        window.DashboardIcons.heartBroken, // SVG Corazón Roto
        function () {
            if (!window.dashboardConfig) return;
            const { csrfToken } = window.dashboardConfig;

            fetch(`/favoritos/toggle/${spotId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.status === 'removed') {
                        const card = document.getElementById(`fav-card-${spotId}`);
                        if (card) {
                            card.style.opacity = '0';
                            card.style.transform = 'translateX(-20px)';
                            setTimeout(() => card.remove(), 300);
                        } else {
                            window.location.reload();
                        }
                    }
                })
                .catch(err => console.error(err));
        }
    );
};