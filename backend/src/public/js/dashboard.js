document.addEventListener('DOMContentLoaded', function () {

    console.log("✅ JS Cargado correctamente");

    // ==========================================
    // 1. DRAG & DROP (Arrastrar y Soltar)
    // ==========================================
    const draggables = document.querySelectorAll('.draggable-item');
    const dropzones = document.querySelectorAll('.dropzone');

    // A) Configurar elementos arrastrables (Spots)
    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', (e) => {
            draggable.classList.add('dragging');
            // Guardamos el ID del spot
            e.dataTransfer.setData('text/plain', draggable.dataset.id);
            e.dataTransfer.effectAllowed = "copy";
        });

        draggable.addEventListener('dragend', () => {
            draggable.classList.remove('dragging');
        });
    });

    // B) Configurar zonas de destino (Días del calendario)
    dropzones.forEach(zone => {

        // 1. OBLIGATORIO: Permitir soltar
        zone.addEventListener('dragover', (e) => {
            e.preventDefault(); // Sin esto, el evento DROP nunca se dispara
            zone.classList.add('drag-over'); // Añade clase visual (borde o fondo)
        });

        // 2. Opcional: Limpiar estilo si sale sin soltar
        zone.addEventListener('dragleave', (e) => {
            zone.classList.remove('drag-over');
        });

        // 3. El evento SOLTAR
        zone.addEventListener('drop', (e) => {
            e.preventDefault();
            zone.classList.remove('drag-over');

            console.log("🔥 SOLTADO (DROP) DETECTADO");

            // Asegurarnos de que tenemos la zona correcta (por si suelta sobre un hijo)
            const targetZone = e.target.closest('.dropzone');
            if (!targetZone) {
                console.error("❌ Error: No se detectó la zona de destino");
                return;
            }

            const spotId = e.dataTransfer.getData('text/plain');
            const fechaDestino = targetZone.dataset.date;

            console.log("📍 Datos recibidos -> Spot ID:", spotId, " | Fecha:", fechaDestino);

            if (spotId && fechaDestino) {
                savePlanToBackend(spotId, fechaDestino);
            } else {
                console.error("⚠️ FALTAN DATOS: Revisa data-id en el spot o data-date en el calendario.");
            }
        });
    });

    // ==========================================
    // 2. MODAL (Botón +)
    // ==========================================
    const modal = document.getElementById('dayModal');

    // Asignamos a window para que funcione desde el HTML onclick="..."
    window.openModal = function (name, id) {
        window.currentSpotId = id;
        const titleEl = document.getElementById('modalTitle');
        if (titleEl) titleEl.innerText = 'Añadir: ' + name;
        if (modal) modal.style.display = 'flex';
    };

    window.closeModal = function () {
        if (modal) modal.style.display = 'none';
        window.currentSpotId = null;
    };

    window.addToDay = function (fecha) {
        if (window.currentSpotId && fecha) {
            savePlanToBackend(window.currentSpotId, fecha);
            closeModal();
        } else {
            console.error("Faltan datos: ID o Fecha incorrectos");
        }
    };

    // Cerrar si clic fuera del modal
    window.onclick = function (event) {
        if (event.target == modal) {
            closeModal();
        }
    }
});

// ==========================================
// 3. FUNCIONES GLOBALES (AJAX)
// Estan fuera del DOMContentLoaded o asignadas a window
// para asegurar que los botones HTML las encuentren.
// ==========================================

window.savePlanToBackend = function (spotId, fecha) {
    if (!window.dashboardConfig) {
        console.error("❌ Error: window.dashboardConfig no está definido. Revisa tu Blade.");
        return;
    }

    const { csrfToken, storeUrl } = window.dashboardConfig;

    fetch(storeUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            spot_id: spotId,
            fecha: fecha
        })
    })
        .then(response => {
            if (!response.ok) throw new Error('Error HTTP: ' + response.status);
            return response.json();
        })
        .then(data => {
            if (data.success || data.plan) {
                console.log("✅ Guardado con éxito. Recargando...");
                window.location.reload();
            } else {
                alert('No se pudo guardar el plan.');
            }
        })
        .catch(error => {
            console.error('Error AJAX:', error);
            alert('Hubo un error al conectar con el servidor.');
        });
};

window.deletePlan = function (planId) {
    if (!confirm('¿Seguro que quieres borrar este plan?')) return;

    const { csrfToken } = window.dashboardConfig;

    fetch(`/planes/${planId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const card = document.getElementById(`plan-${planId}`);
                if (card) card.remove();
            } else {
                alert('Error al borrar');
            }
        })
        .catch(err => console.error(err));
};

window.removeFavorite = function (spotId) {
    if (!confirm('¿Quitar de favoritos?')) return;

    const card = document.getElementById(`fav-card-${spotId}`);
    if (card) card.style.opacity = '0.5';

    const { csrfToken } = window.dashboardConfig;

    fetch(`/favoritos/${spotId}`, { 
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (card) card.remove();
                updateFavCount(-1);
            } else {
                alert('Error al eliminar.');
                if (card) card.style.opacity = '1';
            }
        })
        .catch(err => {
            console.error(err);
            if (card) card.style.opacity = '1';
        });
};

window.updateFavCount = function (change) {
    const badge = document.querySelector('.badge-count');
    if (badge) {
        let current = parseInt(badge.innerText) || 0; 
        badge.innerText = Math.max(0, current + change);
    }
};