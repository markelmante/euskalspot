<nav class="main-navbar" aria-label="Navegación principal">
    <div class="navbar-content">

        {{-- LOGO (IMAGEN) --}}
        <a href="{{ route('dashboard') }}" class="nav-logo" aria-label="Ir a la página de inicio de EuskalSpot">
            <img src="{{ asset('img/Logo.png') }}" alt="Logotipo de EuskalSpot" class="logo-img">
        </a>

        {{-- ZONA DERECHA --}}
        <div class="nav-right-side">

            {{-- ENLACES DE ESCRITORIO --}}
            <div class="desktop-links desktop-only">

                {{-- Agenda --}}
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    {!! request()->routeIs('dashboard') ? 'aria-current="page"' : '' !!}>
                    <svg aria-hidden="true" class="nav-icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Agenda Semanal</span>
                </a>

                {{-- Explorar --}}
                <a href="{{ url('/explorar') }}" class="nav-link {{ request()->is('explorar*') ? 'active' : '' }}"
                    {!! request()->is('explorar*') ? 'aria-current="page"' : '' !!}>
                    <svg aria-hidden="true" class="nav-icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Explorar Spots</span>
                </a>

                {{-- Municipios --}}
                <a href="{{ route('municipios.index') }}"
                    class="nav-link {{ request()->routeIs('municipios*') ? 'active' : '' }}"
                    {!! request()->routeIs('municipios*') ? 'aria-current="page"' : '' !!}>
                    <svg aria-hidden="true" class="nav-icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Municipios</span>
                </a>

                {{-- --- TRUCO ADMIN ESCRITORIO --- --}}
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ url('/admin/panel') }}" class="nav-link"
                        style="color: var(--danger); background-color: #FEF2F2; border: 1px solid var(--danger);"
                        {!! request()->is('admin/panel*') ? 'aria-current="page"' : '' !!}>
                        <svg aria-hidden="true" class="nav-icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Panel Admin</span>
                    </a>
                @endif
                {{-- ------------------------------ --}}

            </div>

            {{-- PERFIL DROPDOWN (Escritorio) --}}
            <div class="relative-container desktop-only">
                <button id="profileBtn" class="profile-btn" aria-haspopup="menu" aria-expanded="false" aria-controls="profileDropdown" aria-label="Menú de perfil del usuario">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </button>

                <div id="profileDropdown" class="dropdown-menu" role="menu" aria-labelledby="profileBtn">
                    <div class="dropdown-header" role="presentation">
                        <span class="user-name">{{ Auth::user()->name ?? 'Usuario' }}</span>
                        <span class="user-role">{{ auth()->check() && auth()->user()->role === 'admin' ? 'Administrador' : 'Usuario' }}</span>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="dropdown-item" role="menuitem">
                        <svg aria-hidden="true" class="nav-icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Mi Perfil
                    </a>

                    <form method="POST" action="{{ route('logout') }}" role="presentation">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger" role="menuitem">
                            <svg aria-hidden="true" class="nav-icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>

            {{-- BOTÓN HAMBURGUESA (MÓVIL) --}}
            <button id="mobileMenuBtn" class="hamburger-btn mobile-only" aria-haspopup="dialog" aria-expanded="false" aria-controls="mobileSidebar" aria-label="Abrir menú de navegación móvil">
                <svg aria-hidden="true" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

        </div>
    </div>
</nav>