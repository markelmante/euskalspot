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

            <div class="nav-buttons">
                {{-- Botón Semana Anterior --}}
                <a href="?date={{ $semanaAnterior }}" class="btn-week" title="Semana anterior">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                {{-- Input para Flatpickr (Selector de Fecha) --}}
                <div class="date-picker-wrapper">
                    <input type="text" id="modernDatepicker" class="flatpickr-custom-input" placeholder="Seleccionar fecha"
                        value="{{ $inicioSemana->format('Y-m-d') }}">
                </div>

                {{-- Botón Semana Siguiente --}}
                <a href="?date={{ $semanaSiguiente }}" class="btn-week" title="Semana siguiente">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </header>

        <div class="planner-layout">

            {{-- SIDEBAR: Favoritos (Draggables) --}}
            <aside class="sidebar-favorites">
                <div class="sidebar-header">
                    <h3>Guardados</h3>
                    <span class="badge-count">{{ count($favoritos) }}</span>
                </div>

                <div class="favorites-scroll">
                    @forelse($favoritos as $fav)
                        @if($fav->spot)
                            {{-- LOGICA COLOR SIDEBAR --}}
                            <div class="spot-card draggable-item {{ $fav->spot->tipo === 'playa' ? 'type-playa' : 'type-monte' }}"
                                draggable="true" data-origin="favorite" data-type="{{ $fav->spot->tipo }}"
                                data-id="{{ $fav->spot->id }}" data-lat="{{ $fav->spot->lat }}" data-lon="{{ $fav->spot->lon }}"
                                id="fav-card-{{ $fav->spot->id }}">

                                <div class="spot-content">
                                    <strong>{{ $fav->spot->nombre }}</strong>
                                    <small>{{ $fav->spot->municipio->nombre ?? 'Ubicación' }}</small>
                                </div>

                                <div class="card-actions">
                                    {{-- Ver detalle --}}
                                    <a href="{{ route('spots.show', $fav->spot->id) }}" class="btn-icon-action btn-details"
                                        title="Ver detalles">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    {{-- Añadir (Versión Móvil) --}}
                                    <button class="btn-icon-action btn-add-mobile"
                                        onclick="openModal('{{ addslashes($fav->spot->nombre) }}', {{ $fav->spot->id }})">
                                        +
                                    </button>

                                    {{-- Eliminar de favoritos --}}
                                    <button class="btn-icon-action btn-remove-fav" onclick="removeFavorite({{ $fav->spot->id }})">
                                        &times;
                                    </button>
                                </div>
                            </div>
                        @endif
                    @empty
                        {{-- NUEVO ESTADO VACÍO PROFESIONAL --}}
                        <div class="empty-favorites-state">
                            <div class="empty-icon-wrapper">
                                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    @endforelse
                </div>
            </aside>

            {{-- GRID CALENDARIO --}}
            <div class="calendar-grid">
                @foreach ($calendario as $dia)
                    <div class="day-column dropzone {{ $dia['es_hoy'] ? 'today-column' : '' }}"
                        data-date="{{ $dia['fecha_completa'] }}">

                        <div class="day-header">
                            <div class="day-info">
                                <span class="day-name">{{ ucfirst($dia['nombre']) }}</span>
                                <span class="day-number">{{ $dia['numero'] }}</span>
                            </div>

                            {{-- Widget Clima --}}
                            <div class="weather-widget" data-lat="{{ $dia['lat'] }}" data-lon="{{ $dia['lon'] }}"
                                data-date="{{ $dia['fecha_completa'] }}">
                                <span class="loading-pulse">⌛</span>
                            </div>
                        </div>

                        <div class="day-body">
                            @foreach ($dia['planes'] as $plan)
                                @if($plan->spot)
                                    <div class="spot-card-calendar draggable-item {{ $plan->spot->tipo === 'playa' ? 'type-playa' : 'type-monte' }}"
                                        draggable="true" data-origin="plan" data-type="{{ $plan->spot->tipo }}"
                                        data-plan-id="{{ $plan->id }}" data-id="{{ $plan->spot->id }}" id="plan-{{ $plan->id }}">

                                        <div class="spot-content">
                                            <strong>{{ $plan->spot->nombre }}</strong>
                                        </div>

                                        <div class="plan-actions">
                                            <a href="{{ route('spots.show', $plan->spot->id) }}" class="btn-mini-action"
                                                title="Ver Spot">
                                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <button class="btn-delete-plan" onclick="deletePlan({{ $plan->id }})">&times;</button>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="dayModal" class="modal-overlay" style="display: none;">
        <div class="modal-box">
            <h3 id="modalTitle">Añadir Spot</h3>
            <p class="modal-subtitle">Elige el día para añadir este plan:</p>

            <div class="modal-grid-days">
                @foreach ($calendario as $dia)
                    <button onclick="addToDay('{{ $dia['fecha_completa'] }}')"
                        class="btn-select-day {{ $dia['es_hoy'] ? 'is-today' : '' }}">
                        {{ ucfirst(substr($dia['nombre'], 0, 3)) }} {{ $dia['numero'] }}
                    </button>
                @endforeach
            </div>

            <button onclick="closeModal()" class="btn-modal-cancel">Cancelar</button>
        </div>
    </div>
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
    </script>
    <script src="{{ asset('js/dashboard.js') }}?v={{ time() }}"></script>
@endpush