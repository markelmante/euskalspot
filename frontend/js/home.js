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

    fetch('/api/reviews')
        .then(response => {
            if (!response.ok) throw new Error('Error en API');
            return response.json();
        })
        .then(data => {
            contenedor.innerHTML = '';

            data.forEach(review => {
                // Generar estrellas
                const estrellas = '⭐'.repeat(review.puntuacion || 5);

                // Usamos 'nombre' o 'usuario' para evitar fallos si cambia la API
                const autor = review.nombre || review.usuario || 'Anónimo';

                // HTML idéntico al que espera tu CSS antiguo (.review-card, .rating, etc.)
                const html = `
                    <div class="review-card">
                        <div class="rating">${estrellas}</div>
                        <p class="review-text">"${review.texto}"</p>
                        <footer class="review-author">${autor}</footer>
                    </div>
                `;
                contenedor.innerHTML += html;
            });
        })
        .catch(err => {
            console.error('Error cargando reseñas:', err);
            contenedor.innerHTML = '<p style="text-align:center; width:100%; color:#666;">No hay reseñas disponibles en este momento.</p>';
        });
}