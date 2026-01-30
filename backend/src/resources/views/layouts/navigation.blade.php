<nav class="main-navbar">
    <div class="navbar-content">

        {{-- 1. LOGO (Izquierda) --}}
        <div class="nav-left">
            <a href="{{ route('dashboard') }}" class="nav-logo">
                Euskal<span class="text-primary">Spot</span>
            </a>
        </div>

        {{-- 2. ENLACES ESCRITORIO (Centro) --}}
        <div class="desktop-links">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                📅 Agenda
            </a>
            <a href="explorar" class="nav-link">
                🌍 Explorador
            </a>
        </div>

        {{-- 3. ACCIONES (Derecha: Perfil PC / Hamburguesa Móvil) --}}
        <div class="nav-actions">

            {{-- PERFIL (Solo Desktop) --}}
            <div class="profile-dropdown-container desktop-only">
                <button onclick="toggleProfile(event)" class="profile-btn" type="button">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </button>

                {{-- Dropdown Menú --}}
                <div id="profileDropdown" class="dropdown-menu hidden">
                    <div class="dropdown-header">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-email">{{ Auth::user()->email }}</span>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        ⚙️ Configuración
                    </a>

                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            🚪 Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>

            {{-- BOTÓN HAMBURGUESA (Solo Móvil) --}}
            <button id="menuBtn" class="hamburger-btn mobile-only">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </div>
</nav>

{{-- 4. MENÚ LATERAL MÓVIL (Off-Canvas) --}}
<div id="sideMenu" class="side-menu-overlay hidden">
    <div class="side-backdrop" id="closeBackdrop"></div>

    <div class="side-menu-content">
        <div class="side-header">
            <div>
                <span class="side-title">Menú</span>
                <span class="side-subtitle">{{ Auth::user()->name }}</span>
            </div>
            <button id="closeMenuBtn" class="close-btn">&times;</button>
        </div>

        <nav class="side-nav-links">
            <a href="{{ route('dashboard') }}" class="side-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                📅 Mi Planificador
            </a>
            <a href="#" class="side-link">
                🌍 Explorador de Spots
            </a>
            <a href="{{ route('profile.edit') }}" class="side-link">
                ⚙️ Configuración
            </a>

            <div class="side-divider"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="side-link text-danger-mobile">
                    🚪 Cerrar Sesión
                </button>
            </form>
        </nav>
    </div>
</div>

{{-- SCRIPT PARA QUE FUNCIONE TODO --}}
<script>
    // 1. Lógica del Menú Móvil
    const menuBtn = document.getElementById('menuBtn');
    const closeMenuBtn = document.getElementById('closeMenuBtn');
    const closeBackdrop = document.getElementById('closeBackdrop');
    const sideMenu = document.getElementById('sideMenu');

    function toggleMenu() {
        sideMenu.classList.toggle('hidden');
    }

    if (menuBtn) menuBtn.addEventListener('click', toggleMenu);
    if (closeMenuBtn) closeMenuBtn.addEventListener('click', toggleMenu);
    if (closeBackdrop) closeBackdrop.addEventListener('click', toggleMenu);

    // 2. Lógica del Dropdown de Perfil (Desktop)
    function toggleProfile(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('profileDropdown');
        dropdown.classList.toggle('hidden');
    }

    // Cerrar dropdown si hago click fuera
    window.onclick = function (event) {
        const dropdown = document.getElementById('profileDropdown');
        if (!event.target.closest('.profile-dropdown-container') && dropdown && !dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
        }
    }
</script>