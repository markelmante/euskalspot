@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="admin-wrapper">
        <div class="admin-container">
            <header class="admin-header">
                <h1>Añadir Nuevo Municipio</h1>
            </header>

            <div class="admin-card">
                <form action="{{ route('admin.municipios.store') }}" method="POST" class="admin-form">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nombre del Municipio</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}"
                            placeholder="Ej: Donostia - San Sebastián" required>
                        @error('nombre')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Guardar Municipio</button>
                        <a href="{{ route('admin.municipios.index') }}" class="btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection