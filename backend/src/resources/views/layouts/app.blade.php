<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>EuskalSpot - @yield('title', 'Tu Surf App')</title>

    {{-- FUENTES --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- 1. CSS GLOBAL (Navbar, Footer, Reset, Variables) --}}
    {{-- CAMBIO IMPORTANTE: Aquí cargamos layout.css, no dashboard.css --}}
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}?v={{ time() }}">

    {{-- Aquí se inyectarán los CSS específicos de cada vista (ej: dashboard.css) --}}
    @stack('styles')
</head>

<body class="@yield('body-class')">

    {{--
    LÓGICA DEL MENÚ:
    1. Si está logueado (@auth) -> Carga navigation.blade.php
    2. Si es invitado (@guest) -> Carga un header sencillo
    --}}

    @auth
        @include('layouts.navigation')
    @endauth

    @guest
        {{-- Header simplificado para invitados --}}
        <header class="main-header">
            <div class="header-container">
                <a href="/" class="nav-logo" style="margin-right: auto;">
                    Euskal<span class="text-primary">Spot</span>
                </a>
                <nav class="nav-links">
                    <a href="{{ route('login') }}" class="btn-login">Entrar</a>
                    <a href="{{ route('register') }}" class="btn-register">Registrarse</a>
                </nav>
            </div>
        </header>
    @endguest


    {{-- CONTENIDO PRINCIPAL --}}
    <main style="min-height: 80vh;">
        @yield('content')
    </main>


    {{-- FOOTER GLOBAL --}}
    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-info">
                <p>&copy; {{ date('Y') }} <strong>EuskalSpot</strong></p>
            </div>
            <div class="footer-links">
                <a href="#">Instagram</a>
                <a href="#">Contacto</a>
            </div>
        </div>
    </footer>

    {{-- SCRIPTS FUNCIONALES GLOBALES (Menú y Dropdowns) --}}
    <script>
        // Función Global para abrir/cerrar perfil (Desktop)
        window.toggleProfile = function (event) {
            event.stopPropagation(); // Evita que el click llegue al document
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {

            // 1. CERRAR DROPDOWN AL CLICAR FUERA
            document.addEventListener('click', function (e) {
                const dropdown = document.getElementById('profileDropdown');
                const button = document.querySelector('.profile-btn');
                const dropdownContainer = document.querySelector('.profile-dropdown-container');

                if (dropdown && !dropdown.classList.contains('hidden')) {
                    // Si el clic no fue dentro del contenedor del dropdown
                    if (!dropdownContainer.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                }
            });

            // 2. LÓGICA MENÚ MÓVIL (Hamburguesa)
            const menuBtn = document.getElementById('menuBtn');
            const sideMenu = document.getElementById('sideMenu');
            const closeMenuBtn = document.getElementById('closeMenuBtn');

            if (menuBtn && sideMenu) {
                menuBtn.addEventListener('click', () => {
                    sideMenu.classList.remove('hidden');
                });
            }

            if (closeMenuBtn && sideMenu) {
                closeMenuBtn.addEventListener('click', () => {
                    sideMenu.classList.add('hidden');
                });
            }

            // Cerrar menú móvil al hacer click en el fondo oscuro
            if (sideMenu) {
                sideMenu.addEventListener('click', (e) => {
                    if (e.target === sideMenu) {
                        sideMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>