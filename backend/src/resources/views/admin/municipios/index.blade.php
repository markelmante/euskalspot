@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="admin-wrapper">
        <div class="admin-container">

            @if(session('success'))
                <div class="admin-badge"
                    style="background-color: var(--success-light); color: var(--success); border-color: var(--success); margin-bottom: 2rem; width: 100%; justify-content: center;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="admin-badge"
                    style="background-color: #fee2e2; color: #dc2626; border-color: #dc2626; margin-bottom: 2rem; width: 100%; justify-content: center;">
                    {{ session('error') }}
                </div>
            @endif

            <header class="admin-header header-flex">
                <div class="admin-title-area">
                    <h1>Gestionar Municipios</h1>
                    <p>Administra las localidades donde se ubican los spots.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.panel') }}" class="btn-secondary">
                        &larr; Volver al Panel
                    </a>
                    <a href="{{ route('admin.municipios.create') }}" class="btn-primary">
                        + Añadir Municipio
                    </a>
                </div>
            </header>

            <div class="admin-toolbar">
                <form action="{{ route('admin.municipios.index') }}" method="GET" class="admin-search-form">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="search-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" placeholder="Buscar municipio..." value="{{ request('search') }}"
                        class="admin-search-input">
                    <button type="submit" class="btn-search">Buscar</button>
                </form>
            </div>

            <div class="admin-card">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Spots Asociados</th>
                                <th class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($municipios as $m)
                                <tr>
                                    <td><span class="badge-id">#{{ $m->id }}</span></td>
                                    <td class="font-medium">{{ $m->nombre }}</td>
                                    <td>
                                        <span class="badge-count">
                                            {{ $m->spots_count }} {{ Str::plural('spot', $m->spots_count) }}
                                        </span>
                                    </td>
                                    <td class="text-right table-actions">
                                        <a href="{{ route('admin.municipios.edit', $m->id) }}" class="btn-action btn-edit"
                                            title="Editar">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.municipios.destroy', $m->id) }}" method="POST"
                                            style="display: inline;"
                                            onsubmit="return confirm('¿Estás seguro? Se borrará si no tiene spots asociados.');">
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
                                    <td colspan="4" class="text-center empty-state">No hay municipios registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination-wrapper">
                    {{ $municipios->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection