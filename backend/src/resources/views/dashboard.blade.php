@extends('layouts.app')

@section('title', 'Planificador Semanal')

@push('styles')
    {{-- Estilos específicos del Dashboard --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">
    {{-- Estilos de Flatpickr (Calendario) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')
    <div class="dashboard-container">

        {{-- HEADER: Navegación entre semanas y selector de fecha --}}
        <header class="planner-header">
            <div class="planner-title">
                <h2>Planificador Semanal</h2>
                <p>Organiza tu semana arrastrando tus spots favoritos al calendario.</p>
            </div>

            <nav class="nav-buttons" aria-label="Navegación del calendario">
                {{-- Botón Semana Anterior --}}
                <a href="?date={{ $semanaAnterior }}" class="btn-week" aria-label="Semana anterior">
                    <svg aria-hidden="true" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                {{-- Input para Flatpickr (Selector de Fecha) --}}
                <div class="date-picker-wrapper">
                    <input type="text" id="modernDatepicker" class="flatpickr-custom-input" placeholder="Seleccionar fecha"
                        aria-label="Seleccionar fecha de inicio de la semana"
                        value="{{ $inicioSemana->format('Y-m-d') }}">
                </div>

                {{-- Botón Semana Siguiente --}}
                <a href="?date={{ $semanaSiguiente }}" class="btn-week" aria-label="Semana siguiente">
                    <svg aria-hidden="true" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </nav>
        </header>

        <div class="planner-layout">

            {{-- SIDEBAR: Favoritos (Draggables) --}}
            <aside class="sidebar-favorites" aria-label="Spots Favoritos">
                <div class="sidebar-header">
                    <h3>Guardados</h3>
                    <span class="badge-count" aria-label="{{ count($favoritos) }} spots guardados">{{ count($favoritos) }}</span>
                </div>

                <div class="favorites-scroll" role="list">
                    @forelse($favoritos as $fav)
                        @if($fav->spot)
                            {{-- LOGICA COLOR SIDEBAR --}}
                            <div class="spot-card draggable-item {{ $fav->spot->tipo === 'playa' ? 'type-playa' : 'type-monte' }}"
                                draggable="true" role="listitem" tabindex="0"
                                aria-label="Mover spot {{ $fav->spot->nombre }}"
                                data-origin="favorite" data-type="{{ $fav->spot->tipo }}"
                                data-id="{{ $fav->spot->id }}" data-lat="{{ $fav->spot->lat }}" data-lon="{{ $fav->spot->lon }}"
                                id="fav-card-{{ $fav->spot->id }}">

                                <div class="spot-content">
                                    <strong>{{ $fav->spot->nombre }}</strong>
                                    <small>{{ $fav->spot->municipio->nombre ?? 'Ubicación' }}</small>
                                </div>

                                <div class="card-actions">
                                    {{-- Ver detalle --}}
                                    <a href="{{ route('spots.show', $fav->spot->id) }}" class="btn-icon-action btn-details"
                                        aria-label="Ver detalles de {{ $fav->spot->nombre }}">
                                        <svg aria-hidden="true" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    {{-- Añadir (Versión Móvil) --}}
                                    <button class="btn-icon-action btn-add-mobile"
                                        aria-label="Añadir {{ $fav->spot->nombre }} al calendario"
                                        onclick="openModal('{{ addslashes($fav->spot->nombre) }}', {{ $fav->spot->id }})">
                                        <span aria-hidden="true">+</span>
                                    </button>

                                    {{-- Eliminar de favoritos --}}
                                    <button class="btn-icon-action btn-remove-fav" 
                                        aria-label="Eliminar {{ $fav->spot->nombre }} de favoritos"
                                        onclick="removeFavorite({{ $fav->spot->id }})">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    @empty
                        {{-- ESTADO VACÍO --}}
                        <div class="empty-favorites-state" role="status">
                            <div class="empty-icon-wrapper">
                                <svg aria-hidden="true" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h4>¿No tienes planes?</h4>
                            <p>Tu lista de favoritos está vacía. Descubre lugares increíbles para añadir a tu semana.</p>
                            <a href="{{ url('/explorar') }}" class="btn-explore-spots">
                                <span>Explorar Spots</span>
                                <svg aria-hidden="true" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    @endforelse
                </div>
            </aside>

            {{-- GRID CALENDARIO --}}
            <div class="calendar-grid" role="region" aria-label="Días de la semana">
                @foreach ($calendario as $dia)
                    <section class="day-column dropzone {{ $dia['es_hoy'] ? 'today-column' : '' }}"
                        data-date="{{ $dia['fecha_completa'] }}"
                        aria-label="{{ ucfirst($dia['nombre']) }} {{ $dia['numero'] }}">

                        <header class="day-header">
                            <div class="day-info">
                                <span class="day-name" aria-hidden="true">{{ ucfirst($dia['nombre']) }}</span>
                                <span class="day-number" aria-hidden="true">{{ $dia['numero'] }}</span>
                            </div>

                            {{-- Widget Clima --}}
                            <div class="weather-widget" data-lat="{{ $dia['lat'] }}" data-lon="{{ $dia['lon'] }}"
                                data-date="{{ $dia['fecha_completa'] }}" aria-label="Información del tiempo">
                                <span class="loading-pulse" aria-hidden="true">⌛</span>
                            </div>
                        </header>

                        <div class="day-body" role="list">
                            @foreach ($dia['planes'] as $plan)
                                @if($plan->spot)
                                    <div class="spot-card-calendar draggable-item {{ $plan->spot->tipo === 'playa' ? 'type-playa' : 'type-monte' }}"
                                        draggable="true" role="listitem" tabindex="0"
                                        aria-label="Plan: {{ $plan->spot->nombre }}"
                                        data-origin="plan" data-type="{{ $plan->spot->tipo }}"
                                        data-plan-id="{{ $plan->id }}" data-id="{{ $plan->spot->id }}" id="plan-{{ $plan->id }}">

                                        <div class="spot-content">
                                            <strong>{{ $plan->spot->nombre }}</strong>
                                        </div>

                                        <div class="plan-actions">
                                            <a href="{{ route('spots.show', $plan->spot->id) }}" class="btn-mini-action"
                                                aria-label="Ver detalles del plan {{ $plan->spot->nombre }}">
                                                <svg aria-hidden="true" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <button class="btn-delete-plan" 
                                                aria-label="Eliminar plan {{ $plan->spot->nombre }}" 
                                                onclick="deletePlan({{ $plan->id }})">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <dialog id="dayModal" class="modal-overlay" style="display: none;" aria-labelledby="modalTitle">
        <div class="modal-box">
            <h3 id="modalTitle">Añadir Spot</h3>
            <p class="modal-subtitle">Elige el día para añadir este plan:</p>

            <div class="modal-grid-days">
                @foreach ($calendario as $dia)
                    <button onclick="addToDay('{{ $dia['fecha_completa'] }}')"
                        class="btn-select-day {{ $dia['es_hoy'] ? 'is-today' : '' }}"
                        aria-label="Añadir plan el {{ ucfirst($dia['nombre']) }} {{ $dia['numero'] }}">
                        {{ ucfirst(substr($dia['nombre'], 0, 3)) }} {{ $dia['numero'] }}
                    </button>
                @endforeach
            </div>

            <button onclick="closeModal()" class="btn-modal-cancel">Cancelar</button>
        </div>
    </dialog>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

    <script>
        window.dashboardConfig = {
            csrfToken: "{{ csrf_token() }}",
            storeUrl: "{{ route('planes.store') }}",
            updateBaseUrl: "{{ url('planes') }}",
            favRemoveUrl: "{{ url('favoritos') }}",
            currentDate: "{{ $inicioSemana->format('Y-m-d') }}"
        };

        // PARCHE DE ACCESIBILIDAD PARA WAVE
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                // 1. Arreglo para Flatpickr (Missing form label)
                let flatpickrInputs = document.querySelectorAll('.flatpickr-custom-input, .flatpickr-input');
                flatpickrInputs.forEach(function(input) {
                    input.setAttribute('aria-label', 'Seleccionar fecha del calendario');
                    input.setAttribute('title', 'Seleccionar fecha del calendario');
                });

                // 2. Arreglo para el botón del Sidebar (Empty button)
                let sidebarBtn = document.getElementById('closeSidebarBtn');
                if (sidebarBtn && !sidebarBtn.hasAttribute('aria-label')) {
                    sidebarBtn.setAttribute('aria-label', 'Cerrar menú lateral');
                    let svg = sidebarBtn.querySelector('svg');
                    if(svg) svg.setAttribute('aria-hidden', 'true');
                }
            }, 600); // 600ms de retraso para asegurar que los elementos dinámicos existan
        });
    </script>
    <script src="{{ asset('js/dashboard.js') }}?v={{ time() }}"></script>
@endpush