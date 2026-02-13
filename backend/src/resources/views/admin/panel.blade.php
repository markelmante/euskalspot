@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="admin-wrapper">
        <div class="admin-container">

            {{-- CABECERA ADMIN --}}
            <header class="admin-header">
                <div class="admin-title-area">
                    <h1>Panel de Control</h1>
                    <p>Bienvenido, {{ Auth::user()->name }}. Aquí tienes el resumen de la plataforma.</p>
                </div>
                <div class="admin-badge">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Modo Administrador
                </div>
            </header>

            {{-- GRID DE ESTADÍSTICAS (Ahora con datos dinámicos) --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon icon-blue">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3>Usuarios Totales</h3>
                        <p>{{ $usersCount }}</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon icon-green">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3>Spots Publicados</h3>
                        <p>{{ $spotsCount }}</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon icon-yellow">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3>Municipios Activos</h3>
                        <p>{{ $municipiosCount }}</p>
                    </div>
                </div>
            </div>

            {{-- ACCESOS DIRECTOS --}}
            <h2 class="section-title">Gestión Rápida</h2>
            <div class="actions-grid">

                {{-- Tarjeta 1: Gestionar Spots --}}
                <a href="{{ route('admin.spots.index') }}" class="action-card">
                    <div class="action-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3>Gestionar Spots</h3>
                    <p>Crea, edita o elimina los lugares turísticos de la plataforma. Revisa las imágenes y la información.
                    </p>
                    <div class="action-arrow">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                </a>

                {{-- Tarjeta 2: Gestionar Municipios --}}
                <a href="#" class="action-card">
                    <div class="action-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3>Municipios</h3>
                    <p>Administra los pueblos y ciudades disponibles. Añade descripciones y asigna provincias.</p>
                    <div class="action-arrow">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                </a>

                {{-- Tarjeta 3: Gestionar Usuarios --}}
                <a href="#" class="action-card">
                    <div class="action-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3>Usuarios Registrados</h3>
                    <p>Visualiza la lista de usuarios, cambia roles (Admin/Usuario) o elimina cuentas inactivas.</p>
                    <div class="action-arrow">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                </a>

            </div>
        </div>
    </div>
@endsection