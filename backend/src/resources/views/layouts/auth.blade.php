<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name', 'EuskalSpot') }}</title>

    {{-- FUENTES --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@700;800&display=swap"
        rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    @stack('styles')

    {{-- ESTILOS EXTRA PARA CENTRAR TODO --}}
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: var(--bg-body);
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-logo-container {
            margin-bottom: 2rem;
            text-align: center;
        }

        /* Ajustamos el tamaño del logo para la pantalla de login */
        .auth-brand-img {
            max-height: 80px;
            /* Puedes subir o bajar este valor si lo ves muy grande/pequeño */
            width: auto;
            object-fit: contain;
        }

        main {
            width: 100%;
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body>

    {{-- LOGO IMAGEN CENTRADO --}}
    <div class="auth-logo-container">
        <a href="{{ url('/') }}">
            <img src="{{ asset('img/Logo.png') }}" alt="EuskalSpot" class="auth-brand-img">
        </a>
    </div>

    {{-- AQUI VA LA TARJETA DE LOGIN / REGISTRO --}}
    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>