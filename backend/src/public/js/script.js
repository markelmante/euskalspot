/* ==========================================================================
   CONFIGURACIÓN Y VARIABLES GLOBALES
   ========================================================================== */
let map, markersGroup;
let allSpots = [];
let filtrosActivos = {
    municipio: '',
    tipo: 'todos',
    etiquetas: []
};

// Se ejecuta cuando el HTML ha cargado completamente
document.addEventListener('DOMContentLoaded', () => {

    // 1. GESTIÓN DE LA HOME (RESEÑAS Y CARRUSEL)
    // ----------------------------------------------------------------------
    const carruselSlides = document.querySelectorAll('.carousel-slide');
    if (carruselSlides.length > 0) {
        iniciarCarrusel(carruselSlides);
        cargarResenasHome(); // Nueva función para las reseñas de la portada
    }

    // 2. GESTIÓN DEL EXPLORADOR (MAPA Y FILTROS)
    // ----------------------------------------------------------------------
    const mapContainer = document.getElementById('map');
    if (mapContainer) {
        // Inicializamos el mapa centrado en Euskadi
        map = L.map('map').setView([43.263, -2.935], 9);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        markersGroup = L.layerGroup().addTo(map);

        // Cargamos los spots reales
        cargarSpotsExplorador();
    }
});

/* ==========================================================================
   LÓGICA DE LA HOME (LANDING PAGE)
   ========================================================================== */
function iniciarCarrusel(slides) {
    let currentSlide = 0;
    setInterval(() => {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
    }, 5000); // Cambio cada 5 segundos
}

function cargarResenasHome() {
    const contenedor = document.getElementById('contenedor-reseñas');
    if (!contenedor) return;

    fetch('/api/reviews')
        .then(response => {
            if (!response.ok) throw new Error('Error en API');
            return response.json();
        })
        .then(data => {
            contenedor.innerHTML = ''; // Limpiar "Cargando..."

            data.forEach(review => {
                const estrellas = '⭐'.repeat(review.puntuacion);

                // HTML que coincide con tu nuevo diseño CSS
                const html = `
                       <div class="review-card">
                           <div class="rating">${estrellas}</div>
                           <p class="review-text">"${review.texto}"</p>
                           <footer class="review-author">${review.nombre}</footer>
                       </div>
                   `;
                contenedor.innerHTML += html;
            });
        })
        .catch(err => {
            console.error('Error cargando reseñas:', err);
            contenedor.innerHTML = '<p class="text-center">No se pudieron cargar las opiniones.</p>';
        });
}

/* ==========================================================================
   LÓGICA DEL EXPLORADOR (MAPA Y FILTROS)
   ========================================================================== */
async function cargarSpotsExplorador() {
    try {
        const response = await fetch('/api/spots', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (response.status === 401) {
            window.location.href = '/login'; // Si no está logueado, fuera
            return;
        }

        allSpots = await response.json();

        // Inicializamos componentes del explorador
        generarBotonesEtiquetas();
        configurarBuscador();
        aplicarFiltros();

    } catch (e) {
        console.error("Error cargando spots:", e);
    }
}

function generarBotonesEtiquetas() {
    const contenedor = document.getElementById('contenedor-etiquetas');
    if (!contenedor) return;

    const tagsSet = new Set();
    allSpots.forEach(s => s.etiquetas.forEach(e => tagsSet.add(e.nombre)));

    contenedor.innerHTML = '';
    tagsSet.forEach(tag => {
        const btn = document.createElement('button');
        btn.className = 'tag-pill-filter';
        btn.innerText = tag;
        btn.onclick = () => toggleEtiqueta(tag, btn);
        contenedor.appendChild(btn);
    });
}

function toggleEtiqueta(tag, elemento) {
    if (filtrosActivos.etiquetas.includes(tag)) {
        filtrosActivos.etiquetas = filtrosActivos.etiquetas.filter(item => item !== tag);
        elemento.classList.remove('active');
    } else {
        filtrosActivos.etiquetas.push(tag);
        elemento.classList.add('active');
    }
    aplicarFiltros();
}

// Función global para ser llamada desde el HTML (onclick)
window.cambiarTipo = function (tipo, btn) {
    filtrosActivos.tipo = tipo;
    document.querySelectorAll('.btn-tipo').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    aplicarFiltros();
};

function aplicarFiltros() {
    let filtrados = allSpots;

    // 1. Filtro Municipio
    if (filtrosActivos.municipio) {
        filtrados = filtrados.filter(s => s.municipio.nombre === filtrosActivos.municipio);
    }

    // 2. Filtro Tipo
    if (filtrosActivos.tipo !== 'todos') {
        // Asumiendo que en DB 'playa' es surf y 'monte' es montaña
        // Ajusta estos valores según lo que tengas en tu Base de Datos real
        const tipoDb = filtrosActivos.tipo === 'surf' ? 'playa' : 'monte';
        filtrados = filtrados.filter(s => s.tipo === tipoDb);
    }

    // 3. Filtro Etiquetas
    if (filtrosActivos.etiquetas.length > 0) {
        filtrados = filtrados.filter(s =>
            filtrosActivos.etiquetas.every(tagBusca => s.etiquetas.some(e => e.nombre === tagBusca))
        );
    }

    renderizarInterfaz(filtrados);
}

function renderizarInterfaz(data) {
    const lista = document.getElementById('lista-spots');
    if (!lista) return;

    lista.innerHTML = '';
    if (markersGroup) markersGroup.clearLayers();

    if (data.length === 0) {
        lista.innerHTML = `<p style="grid-column: 1/-1; text-align: center; padding: 20px;">No se encontraron spots con estos filtros.</p>`;
        return;
    }

    data.forEach(spot => {
        const esPlaya = spot.tipo === 'playa';

        // Estilos dinámicos
        const badgeColor = esPlaya ? '#2563EB' : '#16A34A';
        const textoBadge = esPlaya ? 'SURF' : 'MONTAÑA';
        const emoji = esPlaya ? '🌊' : '⛰️';

        // Marcador en Mapa
        if (markersGroup) {
            const iconHtml = `<div style="font-size: 24px; text-shadow: 0 2px 5px rgba(0,0,0,0.2);">${emoji}</div>`;
            const icon = L.divIcon({
                html: iconHtml,
                className: 'custom-marker-dummy',
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });

            L.marker([spot.latitud, spot.longitud], { icon })
                .addTo(markersGroup)
                .bindPopup(`<b>${spot.nombre}</b><br>${spot.municipio.nombre}`);
        }

        // Renderizar Tarjeta (Coincidiendo con el nuevo CSS)
        const tagsHTML = spot.etiquetas.map(e =>
            `<span style="background: #f1f5f9; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; margin-right: 5px; color: #64748b;">#${e.nombre}</span>`
        ).join('');

        const htmlCard = `
               <div class="review-card" style="cursor: pointer;" onclick="window.volarASpot(${spot.latitud}, ${spot.longitud})">
                   <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                       <span style="background: ${badgeColor}; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold;">
                           ${textoBadge}
                       </span>
                   </div>
                   
                   <h3 style="margin: 10px 0; font-size: 1.25rem;">${spot.nombre}</h3>
                   <p style="color: #64748b; margin-bottom: 10px;">📍 ${spot.municipio.nombre}</p>
                   <div style="margin-bottom: 15px;">${tagsHTML}</div>
                   
                   <button class="btn-cta" style="padding: 8px 20px; font-size: 0.9rem; width: 100%; justify-content: center;">
                       Ver Detalles
                   </button>
               </div>
           `;
        lista.innerHTML += htmlCard;
    });
}

// Función global para volar al mapa
window.volarASpot = function (lat, lng) {
    if (map) {
        map.flyTo([lat, lng], 13, {
            duration: 1.5
        });
        // Scroll suave hacia el mapa
        document.getElementById('map').scrollIntoView({ behavior: 'smooth' });
    }
};

function configurarBuscador() {
    // Requiere jQuery UI
    const input = $("#buscador-municipio");
    if (input.length === 0 || typeof input.autocomplete !== 'function') return;

    const nombresMunicipios = [...new Set(allSpots.map(s => s.municipio.nombre))];

    input.autocomplete({
        source: nombresMunicipios,
        select: function (event, ui) {
            filtrosActivos.municipio = ui.item.value;
            aplicarFiltros();

            // Buscar el primer spot de ese municipio y volar allí
            const primero = allSpots.find(s => s.municipio.nombre === ui.item.value);
            if (primero && map) {
                map.flyTo([primero.latitud, primero.longitud], 12);
            }
        }
    }).on('input', function () {
        if ($(this).val() === "") {
            filtrosActivos.municipio = "";
            aplicarFiltros();
        }
    });
}