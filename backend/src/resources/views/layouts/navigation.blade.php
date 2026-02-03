<nav class="main-navbar">
    <div class="navbar-content">

        {{-- 1. LOGO (Izquierda) --}}
        <div class="nav-left">
            <a href="{{ route('dashboard') }}" class="nav-logo">
                <span class="text-primary">Euskal</span>Spot
            </a>
        </div>

        {{-- 2. DERECHA (Menú Desktop + Botón Móvil) --}}
        <div class="nav-right-side">

            {{-- ENLACES ESCRITORIO (Se ocultan en móvil) --}}
            <div class="desktop-links desktop-only">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="nav-icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Agenda</span>
                </a>

                <a href="{{ url('/explorar') }}" class="nav-link {{ request()->is('explorar*') ? 'active' : '' }}">
                    <svg class="nav-icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Explorar</span>
                </a>
            </div>

            {{-- PERFIL ESCRITORIO (IMPORTANTE: desktop-only para que NO salga en móvil) --}}
            <div class="relative-container desktop-only">
                <button id="profileBtn" class="profile-btn">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </button>

                {{-- Menú Flotante Premium --}}
                <div id="profileDropdown" class="dropdown-menu">
                    <div class="dropdown-header">
                        <p class="dropdown-label">Mi Cuenta</p>
                        <span class="user-name">{{ Auth::user()->name ?? 'Usuario' }}</span>
                    </div>

                    <div class="dropdown-body">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            Configuración
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- BOTÓN HAMBURGUESA (Solo visible en Móvil) --}}
            <button id="mobileMenuBtn" class="hamburger-btn mobile-only">
                <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>

    </div>
</nav>