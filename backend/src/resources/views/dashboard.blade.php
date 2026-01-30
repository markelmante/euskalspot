@extends('layouts.app')

@section('title', 'Planificador')

@section('content')
    {{-- CSS de Flatpickr (Calendario moderno) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div class="dashboard-container">

        {{-- HEADER DEL PLANIFICADOR --}}
        <header class="planner-header">
            <div class="planner-title">
                <h2>Planificador Semanal</h2>
                <p>Arrastra tus spots favoritos a los días de la semana.</p>
            </div>

            <div class="nav-buttons">
                {{-- Botones de navegación por semana --}}
                <a href="?date={{ $semanaAnterior }}" class="btn-week">← Anterior</a>

                {{-- Input del selector de fecha (Flatpickr) --}}
                <input type="text" id="modernDatepicker" class="flatpickr-custom-input" placeholder="Ir a fecha...">

                <a href="?date={{ $semanaSiguiente }}" class="btn-week">Siguiente →</a>
            </div>
        </header>

        <div class="planner-layout">

            {{-- BARRA LATERAL (FAVORITOS) --}}
            <aside class="sidebar-favorites">
                <div class="sidebar-header">
                    <h3>Guardados</h3>
                    <span class="badge-count">{{ count($favoritos) }}</span>
                </div>

                <div class="favorites-scroll">
                    @forelse($favoritos as $fav)
                        {{-- Tarjeta arrastrable --}}
                        <div class="spot-card draggable-item" draggable="true" data-id="{{ $fav->spot->id }}"
                            id="fav-card-{{ $fav->spot->id }}">

                            <div class="spot-content">
                                <strong>{{ $fav->spot->nombre }}</strong>
                                <small>{{ $fav->spot->municipio->nombre ?? 'Ubicación' }}</small>
                            </div>

                            <div style="display:flex; align-items:center; gap:5px;">
                                {{-- Botón + para móvil (abre modal) --}}
                                <button class="btn-add-mobile"
                                    onclick="openModal('{{ addslashes($fav->spot->nombre) }}', {{ $fav->spot->id }})">
                                    +
                                </button>
                                {{-- Botón borrar favorito --}}
                                <button class="btn-remove-fav" onclick="removeFavorite({{ $fav->spot->id }})">
                                    ×
                                </button>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center; padding:20px; color:var(--dash-text);">
                            <p>No tienes favoritos aún.</p>
                            <a href="{{ route('explorer') }}" style="color:var(--dash-primary); font-weight:600;">
                                Ir a Explorar
                            </a>
                        </div>
                    @endforelse
                </div>
            </aside>

            {{-- GRILLA DEL CALENDARIO --}}
            <div class="calendar-grid">
                @foreach($calendario as $dia)
                    {{-- Columna del día (Zona de Drop) --}}
                    <div class="day-column dropzone {{ $dia['es_hoy'] ? 'today-column' : '' }}"
                        data-date="{{ $dia['fecha_completa'] }}">

                        <div class="day-header">
                            <span class="day-name">{{ ucfirst($dia['nombre']) }}</span>

                            <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <span class="day-number">{{ $dia['numero'] }}</span>

                                {{-- ICONO DEL CLIMA INTELIGENTE --}}
                                @if($dia['clima_icono'])
                                    <span title="{{ $dia['lugar_clima'] }}"
                                        style="font-size: {{ $dia['es_clima_spot'] ? '1.5rem' : '1.1rem' }}; 
                                                             cursor: help;
                                                             transition: all 0.3s ease;
                                                             {{ $dia['es_clima_spot'] ? 'filter: drop-shadow(0 0 2px gold); transform: scale(1.1);' : 'opacity: 0.7;' }}">
                                        {{ $dia['clima_icono'] }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="day-body">
                            @foreach($dia['planes'] as $plan)
                                <div class="spot-card-calendar" id="plan-{{ $plan->id }}">
                                    <div class="spot-content">
                                        <strong>{{ $plan->spot->nombre }}</strong>
                                    </div>
                                    <button class="btn-delete-plan" onclick="deletePlan({{ $plan->id }})">
                                        ×
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    {{-- MODAL PARA MÓVIL (Selección de día) --}}
    <div id="dayModal" class="modal-overlay" style="display:none;">
        <div class="modal-box">
            <h3 id="modalTitle" style="margin-top:0; color:var(--dark);">Añadir Spot</h3>
            <p style="color:var(--dash-text); margin-bottom:15px;">Selecciona el día:</p>

            <div style="display:grid; gap:8px; max-height:300px; overflow-y:auto;">
                @foreach($calendario as $dia)
                    <button onclick="addToDay('{{ $dia['fecha_completa'] }}')"
                        style="padding:10px; border:1px solid #e2e8f0; background:white; border-radius:8px; cursor:pointer; text-align:left; font-weight:600; color:var(--dark);">
                        {{ ucfirst($dia['nombre']) }} {{ $dia['numero'] }}
                        @if($dia['clima_icono']) {{ $dia['clima_icono'] }} @endif
                    </button>
                @endforeach
            </div>

            <button onclick="closeModal()"
                style="width:100%; margin-top:15px; padding:10px; background:transparent; border:none; color:var(--dash-text); cursor:pointer;">
                Cancelar
            </button>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Scripts necesarios para el calendario --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

    <script>
        // CONFIGURACIÓN GLOBAL
        window.dashboardConfig = {
            csrfToken: "{{ csrf_token() }}",
            storeUrl: "{{ route('planes.store') }}"
        };

        // 1. INICIALIZAR FLATPICKR (Calendario Header)
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#modernDatepicker", {
                locale: "es",
                defaultDate: "{{ $inicioSemana->format('Y-m-d') }}",
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "j F Y",
                disableMobile: "true",
                onChange: function (selectedDates, dateStr) {
                    window.location.href = '?date=' + dateStr;
                }
            });
        });

        // 2. LÓGICA DE DRAG AND DROP (Arrastrar y Soltar)
        document.addEventListener('DOMContentLoaded', function () {
            const draggables = document.querySelectorAll('.draggable-item');
            const dropzones = document.querySelectorAll('.dropzone');

            // Eventos para los elementos arrastrables (Favoritos)
            draggables.forEach(draggable => {
                draggable.addEventListener('dragstart', (e) => {
                    draggable.classList.add('dragging');
                    e.dataTransfer.setData('text/plain', draggable.dataset.id);
                    e.dataTransfer.effectAllowed = "copy";
                });

                draggable.addEventListener('dragend', () => {
                    draggable.classList.remove('dragging');
                });
            });

            // Eventos para las zonas de destino (Días del calendario)
            dropzones.forEach(zone => {
                zone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    zone.classList.add('drag-over');
                });

                zone.addEventListener('dragleave', () => {
                    zone.classList.remove('drag-over');
                });

                zone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    zone.classList.remove('drag-over');

                    const targetZone = e.target.closest('.dropzone');
                    if (!targetZone) return;

                    const spotId = e.dataTransfer.getData('text/plain');
                    const fechaDestino = targetZone.dataset.date;

                    if (spotId && fechaDestino) {
                        savePlanToBackend(spotId, fechaDestino);
                    }
                });
            });
        });

        // 3. LÓGICA DEL MODAL (Para Móvil)
        const modal = document.getElementById('dayModal');

        window.openModal = function (name, id) {
            window.currentSpotId = id;
            document.getElementById('modalTitle').innerText = 'Añadir: ' + name;
            modal.style.display = 'flex';
        };

        window.closeModal = function () {
            modal.style.display = 'none';
            window.currentSpotId = null;
        };

        window.addToDay = function (fecha) {
            if (window.currentSpotId && fecha) {
                savePlanToBackend(window.currentSpotId, fecha);
                closeModal();
            }
        };

        window.onclick = function (event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        // 4. FUNCIONES AJAX (Conexión con el Backend)

        // Guardar Plan
        window.savePlanToBackend = function (spotId, fecha) {
            const { csrfToken, storeUrl } = window.dashboardConfig;

            fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    spot_id: spotId,
                    fecha: fecha
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success || data.id) {
                        // Recargamos para ver el clima actualizado del spot
                        window.location.reload();
                    } else {
                        alert('Hubo un problema al guardar el plan.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error de conexión.');
                });
        }

        // Borrar Plan
        window.deletePlan = function (planId) {
            if (!confirm('¿Seguro que quieres borrar este plan?')) return;

            const { csrfToken } = window.dashboardConfig;

            fetch(`/planes/${planId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Recargamos para que vuelva el clima de residencia
                        window.location.reload();
                    } else {
                        alert('No se pudo borrar el plan.');
                    }
                });
        }

        // Quitar de Favoritos
        window.removeFavorite = function (spotId) {
            if (!confirm('¿Quitar de favoritos?')) return;

            const { csrfToken } = window.dashboardConfig;

            fetch(`/favoritos/${spotId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const card = document.getElementById(`fav-card-${spotId}`);
                        if (card) card.remove();

                        const badge = document.querySelector('.badge-count');
                        if (badge) {
                            let count = parseInt(badge.innerText);
                            badge.innerText = Math.max(0, count - 1);
                        }
                    }
                });
        }
    </script>
@endpush