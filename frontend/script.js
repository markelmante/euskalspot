document.addEventListener('DOMContentLoaded', () => {
    // --- LÓGICA DEL CARRUSEL (Para la Landing) ---
    const slides = document.querySelectorAll('.carousel-slide');
    if (slides.length > 0) {
        let current = 0;
        setInterval(() => {
            slides[current].classList.remove('active');
            current = (current + 1) % slides.length;
            slides[current].classList.add('active');
        }, 4000);
    }

    // --- LÓGICA DE CARGA DE SPOTS (Para Explorar) ---
    if (document.getElementById('lista-spots')) {
        cargarSpots();
    }
});

// Función para obtener los datos de Laravel
async function cargarSpots(filtro = 'todos') {
    try {
        const response = await fetch('/api/spots');
        let spots = await response.json();

        // Aplicar filtro si no es "todos"
        if (filtro !== 'todos') {
            spots = spots.filter(s => s.tipo === filtro);
        }

        renderizarSpots(spots);
    } catch (error) {
        console.error("Error cargando spots:", error);
        const lista = document.getElementById('lista-spots');
        lista.innerHTML = '<p class="text-center text-danger">Error al conectar con la API</p>';
    }
}

// Función para pintar las cards de Bootstrap
function renderizarSpots(spots) {
    const lista = document.getElementById('lista-spots');
    lista.innerHTML = ''; // Limpiar spinner

    if (spots.length === 0) {
        lista.innerHTML = '<p class="text-center">No se encontraron spots en esta categoría.</p>';
        return;
    }

    spots.forEach(spot => {
        const card = `
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <span class="badge ${spot.tipo === 'playa' ? 'bg-info' : 'bg-success'} mb-2">
                            ${spot.tipo === 'playa' ? '🏄 Surf' : '⛰️ Montaña'}
                        </span>
                        <h5 class="card-title fw-bold">${spot.nombre}</h5>
                        <p class="card-text text-muted">
                            <i class="bi bi-geo-alt"></i> 📍 ${spot.municipio ? spot.municipio.nombre : 'Euskadi'}
                        </p>
                        <button class="btn btn-outline-primary btn-sm w-100 mt-2">Ver detalles</button>
                    </div>
                </div>
            </div>
        `;
        lista.innerHTML += card;
    });
}

// Función para los botones de filtro (UI)
function filtrar(tipo, btn) {
    // Resetear botones
    document.querySelectorAll('.filter-btn').forEach(b => {
        b.classList.remove('active', 'btn-primary');
        b.classList.add('btn-outline-primary');
    });

    // Activar botón clicado
    btn.classList.add('active', 'btn-primary');
    btn.classList.remove('btn-outline-primary');

    cargarSpots(tipo);
}