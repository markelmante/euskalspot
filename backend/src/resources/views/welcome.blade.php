<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EuskalSpot - Surf & Trekking</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <header class="main-header">
        <div class="container header-container">
            <div class="logo">
                <img src="{{ asset('img/Logo.png') }}" alt="EuskalSpot Logo">
            </div>
            <nav class="nav-links">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/explorar') }}" class="btn-register">Ir al Explorador</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="btn-register">Registrarse</a>
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="carousel-container">
                <div class="carousel-slide active"
                    style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('img/ola.jpg') }}');">
                </div>
                <div class="carousel-slide"
                    style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('img/montaña.jpg') }}');">
                </div>
                <div class="carousel-slide"
                    style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('img/zarautz.jpg') }}');">
                </div>
            </div>

            <div class="container hero-content">
                <h1>Vive <span class="text-green">EuskalSpot</span></h1>
                <p>Tu comunidad de Surf y Montaña en el País Vasco.</p>
                <a href="{{ route('login') }}" class="btn-cta">Empezar aventura</a>
            </div>
        </section>

        <section class="reviews-section">
            <div class="container">
                <h2 class="section-title">Lo que dicen los nuestros Usuarios</h2>

                <div id="contenedor-reseñas" class="reviews-grid">
                    <p style="text-align: center; width: 100%; color: #666;">Cargando opiniones...</p>
                </div>
            </div>
        </section>

    </main>

    <footer class="main-footer">
        <div class="container footer-container">
            <div class="footer-info">
                <p>&copy; 2026 <strong>EuskalSpot</strong></p>
            </div>
            <div class="footer-links">
                <a href="#">Instagram</a>
                <a href="#">Twitter</a>
                <a href="#">Contacto</a>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>