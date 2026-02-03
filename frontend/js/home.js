document.addEventListener('DOMContentLoaded', () => {

    // 1. CARRUSEL
    // ==========================================
    const carruselSlides = document.querySelectorAll('.carousel-slide');
    if (carruselSlides.length > 0) {
        let currentSlide = 0;

        // Intervalo de 5 segundos como en tu script original
        setInterval(() => {
            carruselSlides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % carruselSlides.length;
            carruselSlides[currentSlide].classList.add('active');
        }, 5000);

        // Solo si estamos en la home, cargamos las reseñas
        cargarResenasHome();
    }
});

// 2. RESEÑAS (Fetch a tu API Laravel)
// ==========================================
function cargarResenasHome() {
    const contenedor = document.getElementById('contenedor-reseñas');
    if (!contenedor) return;

    // AÑADIDO: Headers para asegurar respuesta JSON
    fetch('/api/reviews', {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            // Si el servidor da error (404, 500), lanzamos error manual
            if (!response.ok) {
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            contenedor.innerHTML = '';

            // Si devuelve array vacío
            if (data.length === 0) {
                contenedor.innerHTML = '<p style="text-align: center; width: 100%; color: #64748B;">Aún no hay reseñas compartidas.</p>';
                return;
            }

            data.forEach(review => {
                // Protección por si la API cambia nombre de campos
                const estrellas = '⭐'.repeat(review.puntuacion || 5);
                const autor = review.nombre || review.usuario || review.user?.name || 'Anónimo';
                const texto = review.texto || review.content || '';

                const html = `
                <div class="review-card">
                    <div class="rating">${estrellas}</div>
                    <p class="review-text">"${texto}"</p>
                    <footer class="review-author">${autor}</footer>
                </div>
            `;
                contenedor.innerHTML += html;
            });
        })
        .catch(err => {
            console.error('Error cargando reseñas:', err);
            contenedor.innerHTML = `
            <div style="text-align:center; width:100%; color:#ef4444; padding: 20px;">
                <p>⚠️ No se pudieron cargar las opiniones.</p>
                <small style="color:#94a3b8;">(Intenta recargar la página)</small>
            </div>`;
        });
}