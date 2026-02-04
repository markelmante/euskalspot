@extends('layouts.app')

@section('content')
    <div class="show-wrapper">
        <div class="show-container">

            {{-- CABECERA MINIMALISTA --}}
            <div class="header-simple">
                <a href="{{ route('municipios.index') }}" class="nav-back-simple">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver al listado
                </a>

                <div class="title-row mt-3">
                    <h1 class="muni-title">{{ $municipio->nombre }}</h1>

                    <span class="province-badge">
                        {{ $municipio->provincia ?? 'Euskadi' }}
                    </span>
                </div>

                {{-- Pequeño contador discreto debajo del título --}}
                <div class="mt-4 text-gray-500 text-sm font-medium">
                    Explorando {{ $municipio->spots->count() }} spots disponibles
                </div>
            </div>

            {{-- GRID DE SPOTS --}}
            <div class="spots-grid">
                @forelse($municipio->spots as $spot)

                    {{-- ENLACE A DETALLE DEL SPOT --}}
                    <a href="{{ route('spots.show', $spot->id) }}" class="spot-card-link">
                        <div class="spot-card">
                            <div class="spot-image-wrapper">
                                {{-- LÓGICA DE IMAGEN --}}
                                @php
                                    $imgSrc = null;
                                    if ($spot->foto) {
                                        $rawFoto = $spot->foto;
                                        if (is_string($rawFoto)) {
                                            $decoded = json_decode($rawFoto, true);
                                            $path = is_array($decoded) ? ($decoded[0] ?? null) : $rawFoto;
                                        } elseif (is_array($rawFoto)) {
                                            $path = $rawFoto[0] ?? null;
                                        }
                                        if (isset($path)) {
                                            $imgSrc = asset('storage/' . str_replace('\\', '/', $path));
                                        }
                                    }
                                    // Fallbacks de alta calidad
                                    if (!$imgSrc) {
                                        $imgSrc = strtolower($spot->tipo) === 'playa'
                                            ? "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80"
                                            : "https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=800&q=80";
                                    }
                                @endphp

                                <img src="{{ $imgSrc }}" alt="{{ $spot->nombre }}" loading="lazy">

                                <span class="spot-type-badge {{ strtolower($spot->tipo) }}">
                                    {{ ucfirst($spot->tipo) }}
                                </span>
                            </div>

                            <div class="spot-content">
                                <h4 class="spot-name">{{ $spot->nombre }}</h4>
                                <p class="spot-desc">{{ $spot->descripcion }}</p>

                                <div class="card-cta">
                                    Ver detalles <span>&rarr;</span>
                                </div>
                            </div>
                        </div>
                    </a>

                @empty
                    <div
                        class="col-span-full flex flex-col items-center justify-center py-16 px-4 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">No hay spots todavía</h3>
                        <p class="text-gray-500 mt-1">Este municipio aún no tiene lugares registrados.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/municipio-show.css') }}?v={{ time() }}">
@endpush