document.addEventListener('DOMContentLoaded', function () {
    // --- DRAG & DROP ---
    const draggables = document.querySelectorAll('.draggable-item');
    const dropzones = document.querySelectorAll('.dropzone');

    draggables.forEach(d => {
        d.addEventListener('dragstart', () => d.classList.add('dragging'));
        d.addEventListener('dragend', () => d.classList.remove('dragging'));
    });

    dropzones.forEach(zone => {
        zone.addEventListener('dragover', (e) => {
            e.preventDefault();
            zone.classList.add('drag-over');
        });
        zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
        zone.addEventListener('drop', (e) => {
            e.preventDefault();
            zone.classList.remove('drag-over');
            const draggable = document.querySelector('.dragging');
            if (draggable) createSpotCard(zone, draggable.dataset.name);
        });
    });
});

// --- MODAL ---
let currentSpotName = '';

function openModal(name) {
    currentSpotName = name;
    document.getElementById('modalTitle').innerText = 'Añadir: ' + name;
    document.getElementById('dayModal').classList.add('active');
}

function closeModal() {
    document.getElementById('dayModal').classList.remove('active');
}

function addToDay(index) {
    const zone = document.getElementById('day-' + index);
    createSpotCard(zone, currentSpotName);
    closeModal();
}

// --- CREAR ELEMENTO VISUAL ---
function createSpotCard(container, name) {
    const div = document.createElement('div');
    // Aplicamos estilos directamente al elemento creado dinámicamente o usamos clases
    div.style.cssText = "background:white; border-left: 4px solid #2563EB; padding:8px; margin-bottom:5px; border-radius:4px; box-shadow:0 1px 2px rgba(0,0,0,0.1); font-size:0.9rem;";
    div.innerHTML = `<strong>${name}</strong>`;
    container.appendChild(div);
}