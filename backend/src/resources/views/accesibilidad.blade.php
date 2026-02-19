@extends('layouts.app') {{-- Cambia 'app' por el nombre de tu layout principal si es distinto --}}

@section('content')
<main class="container" style="max-width: 800px; margin: 40px auto; padding: 20px; color: #333; background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px;">Declaración de Accesibilidad</h1>

    <p style="margin-bottom: 15px; line-height: 1.6;">
        <strong>EuskalSpot</strong> se ha comprometido a hacer accesible su sitio web de conformidad con las pautas de accesibilidad <strong>WCAG 2.2</strong>.
    </p>

    <h2 style="font-size: 1.5rem; font-weight: 600; margin-top: 30px; margin-bottom: 15px;">Situación de cumplimiento</h2>
    <p style="margin-bottom: 15px; line-height: 1.6;">
        Este sitio web es <strong>parcialmente conforme</strong> con las pautas WCAG 2.2.
    </p>
    <ul style="margin-bottom: 15px; line-height: 1.6; padding-left: 20px;">
        <li style="margin-bottom: 10px;">Se ha logrado un <strong>cumplimiento íntegro del Nivel A</strong>, asegurando que el sitio sea navegable por teclado, compatible con lectores de pantalla básicos (etiquetas alt en imágenes), semánticamente correcto y libre de trampas de foco.</li>
        <li style="margin-bottom: 10px;">Se ha logrado un <strong>cumplimiento amplio del Nivel AA</strong>, con algunas excepciones justificadas que se detallan a continuación.</li>
    </ul>

    <h2 style="font-size: 1.5rem; font-weight: 600; margin-top: 30px; margin-bottom: 15px;">Contenido no accesible (Justificación Nivel AA)</h2>
    <p style="margin-bottom: 15px; line-height: 1.6;">
        El contenido que se recoge a continuación no es accesible por los siguientes motivos (excepciones al Nivel AA):
    </p>
    <ul style="margin-bottom: 15px; line-height: 1.6; padding-left: 20px;">
        <li style="margin-bottom: 10px;"><strong>Criterio 1.4.3 Contraste (mínimo):</strong> Algunos elementos visuales provenientes de mapas interactivos de terceros pueden no alcanzar la ratio de contraste de 4.5:1. Al ser contenido de terceros incrustado, escapa al control directo del desarrollo actual.</li>
        <li style="margin-bottom: 10px;"><strong>Criterio 1.2.5 Audiodescripción (Grabado):</strong> El vídeo explicativo cuenta con su audio original y subtítulos integrados (cumpliendo así el Nivel A). Sin embargo, actualmente no dispone de una pista de audiodescripción adicional (narración de las acciones visuales) para personas con discapacidad visual severa debido a limitaciones técnicas en la fase de producción.</li>
    </ul>

    <h2 style="font-size: 1.5rem; font-weight: 600; margin-top: 30px; margin-bottom: 15px;">Preparación de la presente declaración de accesibilidad</h2>
    <p style="margin-bottom: 15px; line-height: 1.6;">
        Esta declaración fue preparada y aprobada el <time datetime="2025-10-15"><strong>15/02/2026</strong></time>.
    </p>
    <p style="margin-bottom: 15px; line-height: 1.6;">
        El método empleado para preparar la declaración fue una autoevaluación exhaustiva llevada a cabo en su momento por el equipo de desarrollo, utilizando herramientas de auditoría automática junto con estrictas revisiones manuales de navegación por teclado y lectores de pantalla.
    </p>
</main>
@endsection