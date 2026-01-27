<nav class="main-navbar">
    <div class="container navbar-content">

        {{-- 1. LOGO (Izquierda) --}}
        <a href="{{ route('dashboard') }}" class="nav-logo">
            Euskal<span class="text-primary">Spot</span>
        </a>

        {{-- 2. ENLACES DE ESCRITORIO (Visible solo en PC) --}}
        {{-- Esto es nuevo: permite navegar sin abrir el menú en pantallas grandes --}}
        <div class="desktop-links hidden-on-mobile">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                📅 Agenda
            </a>
            <a href="{{ route('explorer') }}" class="nav-link {{ request()->routeIs('explorer') ? 'active' : '' }}">
                🌍 Explorador
            </a>
        </div>

        {{-- 3. ACCIONES (Derecha) --}}
        <div class="nav-actions">

            {{-- Botón de Perfil (Con Dropdown) --}}
            <div class="profile-dropdown-container">
                <button id="profileBtn" class="profile-btn">
                    {{-- Cogemos la inicial del nombre --}}
                    <span class="profile-initial">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </button>

                {{-- Menú desplegable del Perfil --}}
                <div id="profileDropdown" class="dropdown-menu hidden">
                    <div class="dropdown-header">
                        <p class="user-name">{{ Auth::user()->name }}</p>
                        <p class="user-email">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">⚙️ Configuración</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">🚪 Cerrar Sesión</button>
                    </form>
                </div>
            </div>

            {{-- Botón Hamburguesa (Para abrir menú lateral en Móvil) --}}
            <button id="menuBtn" class="hamburger-btn">
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

{{-- 4. MENÚ LATERAL (Off-Canvas - Principalmente para Móvil) --}}
<div id="sideMenu" class="side-menu-overlay hidden">
    <div class="side-menu-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <span style="font-weight: 800; font-size: 1.2rem;">Menú</span>
            <button id="closeMenuBtn" class="close-btn"
                style="font-size: 1.5rem; background: none; border: none; cursor: pointer;">&times;</button>
        </div>

        <nav class="side-nav-links">
            <a href="{{ route('dashboard') }}" class="side-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                📅 Mi Planificador
            </a>

            {{-- ENLACE AL EXPLORADOR CORREGIDO --}}
            <a href="{{ route('explorer') }}" class="side-link {{ request()->routeIs('explorer') ? 'active' : '' }}">
                🌍 Explorador de Spots
            </a>

            <a href="#" class="side-link disabled" style="opacity: 0.5; cursor: not-allowed;">
                📊 Estadísticas (Pronto)
            </a>
        </nav>
    </div>
</div>