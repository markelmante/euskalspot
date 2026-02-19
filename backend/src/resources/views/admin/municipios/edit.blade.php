@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="admin-wrapper">
        <div class="admin-container">
            <header class="admin-header">
                <h1>Editar Municipio: {{ $municipio->nombre }}</h1>
            </header>

            <div class="admin-card">
                <form action="{{ route('admin.municipios.update', $municipio->id) }}" method="POST" class="admin-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Nombre del Municipio</label>
                        <input type="text" name="nombre" class="form-control"
                            value="{{ old('nombre', $municipio->nombre) }}" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Actualizar Municipio</button>
                        <a href="{{ route('admin.municipios.index') }}" class="btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection