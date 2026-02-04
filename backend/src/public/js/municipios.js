document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.querySelector('.muni-search-input');
    const cards = document.querySelectorAll('.muni-card');
    const noResultsMessage = document.getElementById('no-results-message');

    // Función para quitar acentos (tildes)
    const normalizeText = (text) => {
        return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
    };

    searchInput.addEventListener('input', function (e) {
        const searchTerm = normalizeText(e.target.value);
        let hasVisibleCards = false;

        cards.forEach(card => {
            // Buscamos el nombre dentro de la tarjeta
            const nameElement = card.querySelector('.muni-name');
            const muniName = normalizeText(nameElement.textContent);

            // Si el nombre incluye lo que escribimos, mostramos. Si no, ocultamos.
            if (muniName.includes(searchTerm)) {
                card.style.display = ''; // Vuelve a su estado original (flex/block)
                hasVisibleCards = true;
            } else {
                card.style.display = 'none'; // Se oculta
            }
        });

        // Mostrar mensaje si no hay resultados
        if (noResultsMessage) {
            noResultsMessage.style.display = hasVisibleCards ? 'none' : 'block';
        }
    });
});