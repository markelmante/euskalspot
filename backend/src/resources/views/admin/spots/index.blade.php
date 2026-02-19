@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="admin-wrapper">
        <div class="admin-container">

            {{-- MENSAJES DE ÉXITO --}}
            @if(session('success'))
                <div class="admin-badge"
                    style="background-color: var(--success-light); color: var(--success); border-color: var(--success); margin-bottom: 2rem; width: 100%; justify-content: center;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- CABECERA CON BOTONES AGRUPADOS --}}
            <header class="admin-header header-flex">
                <div class="admin-title-area">
                    <h1>Gestionar Spots</h1>
                    <p>Crea, edita y elimina los lugares turísticos.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.panel') }}" class="btn-secondary">
                        &larr; Volver al Panel
                    </a>
                    <a href="{{ route('admin.spots.create') }}" class="btn-primary">
                        + Añadir Nuevo Spot
                    </a>
                </div>
            </header>

            {{-- BARRA DE BÚSQUEDA --}}
            <div class="admin-toolbar">
                <form action="{{ route('admin.spots.index') }}" method="GET" class="admin-search-form">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="search-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" placeholder="Buscar por nombre o municipio..."
                        value="{{ request('search') }}" class="admin-search-input">
                    <button type="submit" class="btn-search">Buscar</button>
                </form>
            </div>

            {{-- TARJETA CON LA TABLA --}}
            <div class="admin-card">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Municipio</th>
                                <th>Tipo</th>
                                <th class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($spots as $spot)
                                <tr>
                                    <td><span class="badge-id">#{{ $spot->id }}</span></td>
                                    <td class="font-medium">{{ $spot->nombre }}</td>
                                    <td class="text-gray">{{ $spot->municipio->nombre ?? 'Sin asignar' }}</td>
                                    <td>
                                        <span
                                            class="badge-type {{ strtolower($spot->tipo) === 'playa' ? 'badge-playa' : (strtolower($spot->tipo) === 'monte' ? 'badge-monte' : '') }}">
                                            {{ ucfirst($spot->tipo) }}
                                        </span>
                                    </td>
                                    <td class="text-right table-actions">
                                        <a href="{{ route('admin.spots.edit', $spot->id) }}" class="btn-action btn-edit"
                                            title="Editar">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.spots.destroy', $spot->id) }}" method="POST"
                                            style="display: inline;"
                                            onsubmit="return confirm('¿Estás seguro de que quieres eliminar este spot?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Eliminar">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center empty-state">No se han encontrado spots.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    {{ $spots->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection