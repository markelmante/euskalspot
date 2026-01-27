<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EuskalSpot - @yield('title', 'Tu Surf App')</title>

    {{-- FUENTES --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- ESTILOS GLOBALES --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">

    {{-- ESTILOS DE NAVEGACIÓN (Asegúrate de tenerlos) --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">

    @stack('styles')
</head>

<body class="@yield('body-class')">

    {{--
    LÓGICA DEL MENÚ:
    1. Si está logueado (@auth) -> Carga tu navigation.blade.php (el menú pro)
    2. Si es invitado (@guest) -> Carga un header sencillo
    --}}

    @auth
        {{-- AQUÍ SE INCRUSTA TU ARCHIVO NAVIGATION.BLADE.PHP --}}
        @include('layouts.navigation')
    @endauth

    @guest
        {{-- Header simplificado para invitados (Landing page / Login) --}}
        <header class="main-header">
            <div class="container header-container">
                <div class="logo">
                    <a href="/">
                        Euskal<span style="color:#2563EB;">Spot</span>
                    </a>
                </div>
                <nav class="nav-links">
                    <a href="{{ route('login') }}" class="btn-login">Entrar</a>
                    <a href="{{ route('register') }}" class="btn-register">Registrarse</a>
                </nav>
            </div>
        </header>
    @endguest


    {{-- CONTENIDO PRINCIPAL (Donde se inyectan las páginas) --}}
    <main style="min-height: 80vh;">
        @yield('content')
    </main>


    {{-- FOOTER GLOBAL --}}
    <footer class="main-footer">
        <div class="container footer-container">
            <div class="footer-info">
                <p>&copy; {{ date('Y') }} <strong>EuskalSpot</strong></p>
            </div>
            <div class="footer-links">
                <a href="#">Instagram</a>
                <a href="#">Contacto</a>
            </div>
        </div>
    </footer>

    {{-- SCRIPTS --}}

    {{-- Script para que funcione el menú móvil y dropdown --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Solo ejecutar si existen los elementos (para evitar errores en modo invitado)
            const menuBtn = document.getElementById('menuBtn');
            const closeMenuBtn = document.getElementById('closeMenuBtn');
            const sideMenu = document.getElementById('sideMenu');
            const profileBtn = document.getElementById('profileBtn');
            const profileDropdown = document.getElementById('profileDropdown');

            // 1. Menú Móvil (Hamburguesa)
            if (menuBtn && sideMenu && closeMenuBtn) {
                menuBtn.addEventListener('click', () => {
                    sideMenu.classList.remove('hidden');
                });
                closeMenuBtn.addEventListener('click', () => {
                    sideMenu.classList.add('hidden');
                });
                // Cerrar al hacer click fuera (en el overlay oscuro)
                sideMenu.addEventListener('click', (e) => {
                    if (e.target === sideMenu) {
                        sideMenu.classList.add('hidden');
                    }
                });
            }

            // 2. Dropdown de Perfil
            if (profileBtn && profileDropdown) {
                profileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('hidden');
                });
                // Cerrar al hacer click fuera
                document.addEventListener('click', (e) => {
                    if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                        profileDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>