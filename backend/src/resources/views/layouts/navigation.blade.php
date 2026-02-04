<nav class="main-navbar">
    <div class="navbar-content">

        {{-- LOGO (IMAGEN) --}}
        <a href="{{ route('dashboard') }}" class="nav-logo">
            {{-- La clase logo-img se encarga del tamaño en el CSS --}}
            <img src="{{ asset('img/Logo.png') }}" alt="EuskalSpot" class="logo-img">
        </a>

        {{-- ZONA DERECHA --}}
        <div class="nav-right-side">

            {{-- ENLACES DE ESCRITORIO --}}
            <div class="desktop-links desktop-only">

                {{-- Agenda --}}
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="nav-icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Agenda Semanal</span>
                </a>

                {{-- Explorar --}}
                <a href="{{ url('/explorar') }}" class="nav-link {{ request()->is('explorar*') ? 'active' : '' }}">
                    <svg class="nav-icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Explorar Spots</span>
                </a>

                {{-- Municipios --}}
                <a href="{{ route('municipios.index') }}"
                    class="nav-link {{ request()->routeIs('municipios*') ? 'active' : '' }}">
                    <svg class="nav-icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Municipios</span>
                </a>

            </div>

            {{-- PERFIL DROPDOWN (Escritorio) --}}
            <div class="relative-container desktop-only">
                <button id="profileBtn" class="profile-btn">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </button>

                <div id="profileDropdown" class="dropdown-menu">
                    <div class="dropdown-header">
                        <span class="user-name">{{ Auth::user()->name ?? 'Usuario' }}</span>
                        <span class="user-role">Usuario</span>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <svg class="nav-icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Mi Perfil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <svg class="nav-icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>

            {{-- BOTÓN HAMBURGUESA (MÓVIL) --}}
            <button id="mobileMenuBtn" class="hamburger-btn mobile-only">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

        </div>
    </div>
</nav>