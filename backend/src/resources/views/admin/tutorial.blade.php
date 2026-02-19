@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}?v={{ time() }}">
    <style>
        .video-container {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-md);
            max-width: 800px;
            margin: 0 auto;
        }
        .video-player {
            width: 100%;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            background-color: var(--dark);
        }
    </style>
@endpush

@section('content')
    <div class="admin-wrapper">
        <div class="admin-container">
            
            <div class="admin-header">
                <div class="admin-title-area">
                    <h1>Tutorial del Panel</h1>
                    <p>Guía de uso para la administración de EuskalSpot</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.panel') }}" class="btn-secondary">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver al Panel
                    </a>
                </div>
            </div>

            <div class="video-container">
                <h2 class="section-title text-center">Cómo gestionar EuskalSpot</h2>
                
                <video 
                    class="video-player"
                    controls 
                    controlsList="nodownload" 
                    preload="metadata" 
                    poster="{{ asset('assets/img/miniatura-admin.jpg') }}">
                    
                    <source src="{{ asset('assets/video/tutorial-admin.mp4') }}" type="video/mp4">
                    
                    <track src="{{ asset('assets/video/subtitulos-admin.vtt') }}" kind="subtitles" srclang="es" label="Español" default>
                    
                    <p>Tu navegador no soporta el vídeo de HTML5. Puedes descargar el vídeo <a href="{{ asset('assets/video/tutorial-admin.mp4') }}">haciendo clic aquí</a>.</p>
                </video>
            </div>

        </div>
    </div>
@endsection