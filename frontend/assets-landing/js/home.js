document.addEventListener('DOMContentLoaded', () => {
    // Cargar las reseñas al iniciar la página
    cargarResenasHome();
});

// RESEÑAS (Fetch API)
// ==========================================
function cargarResenasHome() {
    const contenedor = document.getElementById('contenedor-reseñas');
    if (!contenedor) return;

    fetch('/api/reviews', {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) throw new Error(`Error ${response.status}`);
            return response.json();
        })
        .then(data => {
            contenedor.innerHTML = ''; // Limpiar texto de "Cargando..."

            if (data.length === 0) {
                contenedor.innerHTML = '<p style="text-align: center; width: 100%; grid-column: 1/-1;">Aún no hay reseñas compartidas.</p>';
                return;
            }

            // Tomamos hasta 3 reseñas (o las que haya)
            const ultimasResenas = data.slice(0, 3);

            ultimasResenas.forEach(review => {
                // Datos por defecto si faltan en la API
                const puntuacion = review.puntuacion || 5;
                const estrellas = '⭐'.repeat(puntuacion);
                const autor = review.user ? review.user.name : (review.nombre || 'Usuario EuskalSpot');
                const texto = review.content || review.texto || 'Sin comentario.';

                const html = `
                <div class="review-card">
                    <div class="rating">${estrellas}</div>
                    <p class="review-text">"${texto}"</p>
                    <footer class="review-author">${autor}</footer>
                </div>
            `;
                // Insertar al final del contenedor
                contenedor.insertAdjacentHTML('beforeend', html);
            });
        })
        .catch(err => {
            console.error('Error cargando reseñas:', err);
            contenedor.innerHTML = `
            <div style="text-align:center; width: 100%; color:#ef4444;">
                <p>⚠️ No se pudieron cargar las opiniones.</p>
            </div>`;
        });
}