document.addEventListener('DOMContentLoaded', function () {

    // --- LÓGICA DEL MENÚ LATERAL (MÓVIL) ---
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');

    function openSidebar() {
        if (mobileSidebar) {
            mobileSidebar.classList.add('open');
            document.body.style.overflow = 'hidden'; // Bloquea el scroll
        }
    }

    function closeSidebar() {
        if (mobileSidebar) {
            mobileSidebar.classList.remove('open');
            document.body.style.overflow = ''; // Reactiva el scroll
        }
    }

    if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openSidebar);
    if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);

    // Cerrar al hacer click fuera (en lo oscuro)
    if (mobileSidebar) {
        mobileSidebar.addEventListener('click', function (e) {
            if (e.target === mobileSidebar) {
                closeSidebar();
            }
        });
    }

    // --- LÓGICA DEL DROPDOWN DE PERFIL (ESCRITORIO) ---
    // Importante: Asegúrate de que tu botón de perfil en el navigation.blade.php tenga id="profileBtn"
    // y el menú desplegable tenga id="profileDropdown"
    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');

    if (profileBtn && profileDropdown) {
        profileBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('show');
        });

        // Cerrar al hacer click fuera
        document.addEventListener('click', function (e) {
            if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.remove('show');
            }
        });
    }
});