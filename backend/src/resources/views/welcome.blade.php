<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EuskalSpot - Surf & Trekking</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <header class="main-header">
        <div class="container header-container">
            <div class="logo">
                <img src="{{ asset('img/Logo.png') }}" alt="EuskalSpot Logo">
            </div>
            <nav class="nav-links">
                <a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="btn-register">Registrarse</a>
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
                <a href="{{ route('register') }}" class="btn-cta">Empezar aventura</a>
            </div>
        </section>

        <section class="reviews-section">
            <div class="container">
                <h2 class="section-title">Lo que dicen nuestros usuarios</h2>
                <div class="reviews-grid">
                    <div class="review-card">
                        <div class="rating">⭐⭐⭐⭐⭐</div>
                        <p class="review-text">"Gracias a la conexión con Euskalmet, ya no pierdo el tiempo yendo a la
                            playa si no hay olas. ¡Imprescindible!"</p>
                        <p class="review-author">— Jon, Surfer en Gros</p>
                    </div>

                    <div class="review-card">
                        <div class="rating">⭐⭐⭐⭐⭐</div>
                        <p class="review-text">"El planificador semanal es súper intuitivo. Arrastrar mis rutas
                            favoritas al calendario me ayuda a organizar mis findes en el monte."</p>
                        <p class="review-author">— Ane, Mendizale</p>
                    </div>

                    <div class="review-card">
                        <div class="rating">⭐⭐⭐⭐</div>
                        <p class="review-text">"He descubierto spots en Bizkaia que ni conocía gracias al buscador por
                            municipio. ¡Una comunidad genial!"</p>
                        <p class="review-author">— Markel, Aventurero</p>
                    </div>
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