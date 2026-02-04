@extends('layouts.app')

@section('title', 'Planificador')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')

    <div class="dashboard-container">

        {{-- HEADER --}}
        <header class="planner-header">
            <div class="planner-title">
                <h2>Planificador Semanal</h2>
                <p>Organiza tu semana arrastrando tus spots favoritos.</p>
            </div>

            <div class="nav-buttons">
                <a href="?date={{ $semanaAnterior }}" class="btn-week">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="vertical-align: text-bottom;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                
                <input type="text" id="modernDatepicker" class="flatpickr-custom-input" placeholder="Fecha">
                
                <a href="?date={{ $semanaSiguiente }}" class="btn-week">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="vertical-align: text-bottom;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </header>

        <div class="planner-layout">

            {{-- SIDEBAR FAVORITOS --}}
            <aside class="sidebar-favorites">
                <div class="sidebar-header">
                    <h3>Guardados</h3>
                    <span class="badge-count">{{ count($favoritos) }}</span>
                </div>

                <div class="favorites-scroll">
                    @forelse($favoritos as $fav)
                        <div class="spot-card draggable-item" draggable="true" data-id="{{ $fav->spot->id }}" id="fav-card-{{ $fav->spot->id }}">
                            <div class="spot-content">
                                <strong>{{ $fav->spot->nombre }}</strong>
                                <small>{{ $fav->spot->municipio->nombre ?? 'Euskadi' }}</small>
                            </div>
                            <div style="display:flex; align-items:center; gap:5px;">
                                <button class="btn-icon-action btn-add-mobile" onclick="openModal('{{ addslashes($fav->spot->nombre) }}', {{ $fav->spot->id }})">
                                    +
                                </button>
                                <button class="btn-icon-action btn-remove-fav" onclick="removeFavorite({{ $fav->spot->id }})">
                                    &times;
                                </button>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center; padding:30px 10px; color:var(--dash-text);">
                            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom:10px; opacity:0.5;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <p style="font-size:0.9rem;">No tienes favoritos.</p>
                            <a href="{{ url('/explorar') }}" style="color:var(--dash-primary); font-weight:700; text-decoration:none; font-size:0.9rem;">Explorar Spots</a>
                        </div>
                    @endforelse
                </div>
            </aside>

            {{-- CALENDARIO --}}
            <div class="calendar-grid">
                @foreach($calendario as $dia)
                    <div class="day-column dropzone {{ $dia['es_hoy'] ? 'today-column' : '' }}" data-date="{{ $dia['fecha_completa'] }}">
                        
                        {{-- CABECERA DEL DÍA --}}
                        <div class="day-header">
                            <div class="day-info">
                                <span class="day-name">{{ ucfirst($dia['nombre']) }}</span>
                                <span class="day-number">{{ $dia['numero'] }}</span>
                            </div>
                            
                            @if($dia['clima_icono'])
                                <div class="weather-icon" title="{{ $dia['lugar_clima'] }}">
                                    {{ $dia['clima_icono'] }}
                                </div>
                            @endif
                        </div>

                        {{-- CUERPO (PLANES) --}}
                        <div class="day-body">
                            @foreach($dia['planes'] as $plan)
                                <div class="spot-card-calendar" id="plan-{{ $plan->id }}">
                                    <div class="spot-content">
                                        <strong>{{ $plan->spot->nombre }}</strong>
                                    </div>
                                    <button class="btn-delete-plan" onclick="deletePlan({{ $plan->id }})">&times;</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    {{-- MODAL (Sin cambios funcionales, solo estilo) --}}
    <div id="dayModal" class="modal-overlay">
        <div class="modal-box">
            <h3 id="modalTitle" style="margin-top:0; color:var(--dark);">Añadir Spot</h3>
            <p style="color:var(--dash-text); margin-bottom:15px;">Elige el día para añadir este plan:</p>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px; margin-bottom:20px;">
                @foreach($calendario as $dia)
                    <button onclick="addToDay('{{ $dia['fecha_completa'] }}')"
                        style="padding:12px; border:1px solid #e2e8f0; background:{{ $dia['es_hoy'] ? '#eff6ff' : '#fff' }}; border-radius:10px; cursor:pointer; text-align:center; font-weight:600; color:var(--dark); box-shadow:0 1px 2px rgba(0,0,0,0.05);">
                        {{ ucfirst(substr($dia['nombre'],0,3)) }} {{ $dia['numero'] }}
                    </button>
                @endforeach
            </div>

            <button onclick="closeModal()" style="width:100%; padding:12px; background:#f1f5f9; border:none; border-radius:8px; font-weight:600; color:#64748b; cursor:pointer;">
                Cancelar
            </button>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

    <script>
        // Mantiene tu lógica JS intacta, solo actualizo la configuración del calendario
        window.dashboardConfig = { csrfToken: "{{ csrf_token() }}", storeUrl: "{{ route('planes.store') }}" };

        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#modernDatepicker", {
                locale: "es",
                defaultDate: "{{ $inicioSemana->format('Y-m-d') }}",
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "j F Y",
                disableMobile: "true",
                onChange: function (selectedDates, dateStr) { window.location.href = '?date=' + dateStr; }
            });

            // DRAG AND DROP (Igual que antes)
            const draggables = document.querySelectorAll('.draggable-item');
            const dropzones = document.querySelectorAll('.dropzone');

            draggables.forEach(draggable => {
                draggable.addEventListener('dragstart', (e) => {
                    draggable.classList.add('dragging');
                    e.dataTransfer.setData('text/plain', draggable.dataset.id);
                    e.dataTransfer.effectAllowed = "copy";
                });
                draggable.addEventListener('dragend', () => draggable.classList.remove('dragging'));
            });

            dropzones.forEach(zone => {
                zone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    zone.classList.add('drag-over');
                });
                zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
                zone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    zone.classList.remove('drag-over');
                    const spotId = e.dataTransfer.getData('text/plain');
                    const date = zone.dataset.date;
                    if(spotId && date) savePlanToBackend(spotId, date);
                });
            });
        });

        // MODAL LOGIC
        const modal = document.getElementById('dayModal');
        window.openModal = function(name, id) {
            window.currentSpotId = id;
            document.getElementById('modalTitle').innerText = 'Añadir: ' + name;
            modal.classList.add('active'); // Usamos clase CSS
        };
        window.closeModal = function() {
            modal.classList.remove('active');
            window.currentSpotId = null;
        };
        window.addToDay = function(date) {
            if(window.currentSpotId && date) {
                savePlanToBackend(window.currentSpotId, date);
                closeModal();
            }
        };

        // AJAX FUNCTIONS (Igual que antes)
        window.savePlanToBackend = function(spotId, date) {
            fetch(window.dashboardConfig.storeUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.dashboardConfig.csrfToken },
                body: JSON.stringify({ spot_id: spotId, fecha: date })
            }).then(r => r.json()).then(d => {
                if(d.success) window.location.reload();
            });
        };

        window.deletePlan = function(id) {
            if(confirm('¿Borrar plan?')) {
                fetch(`/planes/${id}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.dashboardConfig.csrfToken }
                }).then(r => r.json()).then(d => { if(d.success) window.location.reload(); });
            }
        };

        window.removeFavorite = function(id) {
            if(confirm('¿Quitar de favoritos?')) {
                fetch(`/favoritos/${id}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.dashboardConfig.csrfToken }
                }).then(r => r.json()).then(d => {
                    if(d.success) {
                        document.getElementById('fav-card-'+id).remove();
                        // Actualizar contador visualmente si quieres
                    }
                });
            }
        };
    </script>
@endpush