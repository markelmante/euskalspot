@extends('layouts.app')

@section('title', 'Mi Planificador')

{{-- 1. CARGA DE ESTILOS (Tu CSS + Flatpickr) --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">
    {{-- Estilo base de Flatpickr --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    {{-- Tema moderno (Airbnb style) --}}
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

    {{-- Pequeño ajuste para que el calendario coincida con tu color azul --}}
    <style>
        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange,
        .flatpickr-day.selected.inRange,
        .flatpickr-day.startRange.inRange,
        .flatpickr-day.endRange.inRange,
        .flatpickr-day.selected:focus,
        .flatpickr-day.startRange:focus,
        .flatpickr-day.endRange:focus,
        .flatpickr-day.selected:hover,
        .flatpickr-day.startRange:hover,
        .flatpickr-day.endRange:hover,
        .flatpickr-day.selected.prevMonthDay,
        .flatpickr-day.startRange.prevMonthDay,
        .flatpickr-day.endRange.prevMonthDay,
        .flatpickr-day.selected.nextMonthDay,
        .flatpickr-day.startRange.nextMonthDay,
        .flatpickr-day.endRange.nextMonthDay {
            background: #2563EB !important;
            /* Tu color primario */
            border-color: #2563EB !important;
        }
    </style>
@endpush

@section('content')

    <div class="dashboard-container">

        {{-- A. CABECERA --}}
        <div class="planner-header">
            <div class="planner-title">
                <h2>Mi Agenda</h2>
                <p>Planificando semana del {{ $startOfWeek->format('d M, Y') }}</p>
            </div>

            <div class="nav-buttons" style="align-items: center;">
                {{-- Botón Anterior --}}
                <a href="{{ route('dashboard', ['date' => $prevWeek]) }}" class="btn-week">❮</a>

                {{-- NUEVO SELECTOR FLATPICKR --}}
                {{-- Ya no es type="date", ahora lo maneja JS --}}
                <input type="text" id="modern-calendar" class="date-picker-input" placeholder="Seleccionar fecha..."
                    style="text-align: center; width: 140px;">

                {{-- Botón Hoy --}}
                <a href="{{ route('dashboard') }}" class="btn-week" style="font-size: 0.8rem; padding: 8px 12px;">Hoy</a>

                {{-- Botón Siguiente --}}
                <a href="{{ route('dashboard', ['date' => $nextWeek]) }}" class="btn-week">❯</a>
            </div>
        </div>

        {{-- B. GRID (Esto sigue igual que antes) --}}
        <div class="planner-layout">

            {{-- SIDEBAR --}}
            <aside class="sidebar-favorites">
                <h3>Spots Guardados</h3>
                <p style="font-size: 0.85rem; color: #64748B; margin-bottom: 20px;">
                    Arrastra al calendario o usa el "+".
                </p>

                <div id="favorites-list">
                    <div class="spot-card draggable-item" draggable="true" data-id="1" data-name="Playa de Zarautz">
                        <div class="spot-info">
                            <strong>Playa de Zarautz</strong>
                            <span>Gipuzkoa</span>
                        </div>
                        <button class="btn-add-mobile" onclick="openModal('Playa de Zarautz')">+</button>
                    </div>

                    <div class="spot-card draggable-item" draggable="true" data-id="2" data-name="Mundaka">
                        <div class="spot-info">
                            <strong>Mundaka</strong>
                            <span>Bizkaia</span>
                        </div>
                        <button class="btn-add-mobile" onclick="openModal('Mundaka')">+</button>
                    </div>
                </div>
            </aside>

            {{-- CALENDARIO --}}
            <section class="calendar-grid">
                @foreach($diasSemana as $index => $dia)
                    <div class="day-column {{ $dia['es_hoy'] ? 'today-column' : '' }}">
                        <div class="day-header">
                            <span class="day-name">{{ $dia['nombre'] }}</span>
                            <span class="day-number">{{ $dia['numero'] }}</span>
                            @if($dia['es_hoy']) <span class="badge-today">HOY</span> @endif
                        </div>
                        <div class="day-body dropzone" data-date="{{ $dia['fecha_completa'] }}" id="day-{{ $index }}"></div>
                    </div>
                @endforeach
            </section>
        </div>
    </div>

    {{-- MODAL (Sin cambios) --}}
    <div id="dayModal" class="modal-overlay">
        <div class="modal-box">
            <h3 id="modalTitle" style="margin-top:0;">Añadir Spot</h3>
            <p style="margin-bottom:20px; color:#64748B;">Selecciona el día para ir:</p>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                @foreach($diasSemana as $index => $dia)
                    <button onclick="addToDay({{ $index }})"
                        style="padding:10px; border:1px solid #E2E8F0; background:white; border-radius:8px; cursor:pointer;">
                        {{ $dia['nombre'] }}
                    </button>
                @endforeach
            </div>
            <button onclick="closeModal()"
                style="margin-top:20px; width:100%; padding:10px; background:#f1f5f9; border:none; border-radius:8px; cursor:pointer;">Cancelar</button>
        </div>
    </div>

@endsection

{{-- 2. SCRIPTS --}}
@push('scripts')
    {{-- JS de Flatpickr --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    {{-- Idioma Español --}}
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

    {{-- Tu JS del dashboard --}}
    <script src="{{ asset('js/dashboard.js') }}?v={{ time() }}"></script>

    {{-- 3. INICIALIZAR CALENDARIO --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#modern-calendar", {
                locale: "es",              // Ponerlo en español
                defaultDate: "{{ $startOfWeek->format('Y-m-d') }}", // Fecha inicial
                altInput: true,            // Muestra una fecha "bonita" al usuario
                altFormat: "j F, Y",       // Ejemplo: "27 Enero, 2026"
                dateFormat: "Y-m-d",       // Formato que enviamos al servidor
                disableMobile: "true",     // Forzar el diseño moderno también en móvil
                onChange: function (selectedDates, dateStr, instance) {
                    // Al elegir fecha, recargamos la página
                    window.location.href = "{{ route('dashboard') }}?date=" + dateStr;
                }
            });
        });
    </script>
@endpush