<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EuskalSpot') }}</title>

    {{-- FUENTES --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@700;800&display=swap"
        rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    @stack('styles')
</head>

<body>

    {{-- 1. NAVBAR --}}
    @include('layouts.navigation')

    {{-- 2. CONTENIDO --}}
    <main>
        @yield('content')
    </main>

    {{-- 3. SIDEBAR MÓVIL --}}
    <div id="mobileSidebar">
        <div class="side-menu-content">
            <div class="side-header">
                <span class="menu-title">Menú</span>
                <button id="closeSidebarBtn" class="close-btn-modern">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="side-nav-links">
                <a href="{{ route('dashboard') }}"
                    class="side-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Agenda Semanal</span>
                </a>

                <a href="{{ url('/explorar') }}" class="side-link {{ request()->is('explorar*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Explorar Spots</span>
                </a>

                <a href="{{ route('municipios.index') }}"
                    class="side-link {{ request()->routeIs('municipios*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Municipios</span>
                </a>

                <a href="{{ route('profile.edit') }}"
                    class="side-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Mi Perfil</span>
                </a>

                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ url('/admin/panel') }}" class="side-link"
                        style="color: var(--danger); background-color: #FEF2F2;">
                        <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Panel Admin</span>
                    </a>
                @endif

                <div class="separator"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="side-link logout-link">
                        <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </nav>
        </div>
    </div>

    {{-- 4. FOOTER COMPACTO Y DE UNA FILA --}}
    <footer class="main-footer">
        <div class="footer-links-group">
            <div class="footer-column info-column">
                <span class="footer-copy">&copy; {{ date('Y') }} <strong>EuskalSpot</strong>.<br>Hecho con ❤️ en
                    Euskadi.</span>
                <a href="{{ url('/accesibilidad') }}">Declaración de accesibilidad</a>
            </div>

            <div class="footer-column">
                <span class="footer-title">Explorar</span>
                <a href="{{ url('/explorar') }}">Todos los Spots</a>
                <a href="{{ route('municipios.index') }}">Municipios</a>
            </div>

            <div class="footer-column">
                <span class="footer-title">Cuenta</span>
                <a href="{{ route('profile.edit') }}">Mi Perfil</a>
                <a href="{{ route('dashboard') }}">Mi Agenda</a>
            </div>
        </div>
    </footer>

    {{-- JS --}}
    <script src="{{ asset('js/navigation.js') }}?v={{ time() }}"></script>
    @stack('scripts')
</body>

</html>