<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EuskalSpot - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="@yield('body-class')">

    <header class="main-header">
        <div class="container header-container">
            <div class="logo">
                <a href="/"><img src="{{ asset('img/Logo.png') }}" alt="EuskalSpot Logo"></a>
            </div>

            {{-- Si NO es la página de login y NO es la de registro, muestra los botones --}}
            @if (!Route::is('login') && !Route::is('register'))
                <nav class="nav-links">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-login">Mi Agenda</a>
                        <form method="POST" action="{{ route('logout') }}" style="display:inline">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                                style="margin-left:15px; color:#64748b; text-decoration:none; font-weight:bold;">Salir</a>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="btn-register">Registrarse</a>
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="main-footer">
        <div class="container footer-container">
            <p>&copy; 2026 <strong>EuskalSpot</strong>. Comunidad de Surf y Montaña.</p>
        </div>
    </footer>

    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>