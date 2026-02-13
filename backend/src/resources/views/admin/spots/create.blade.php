@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}?v={{ time() }}">
    <style>
        .tags-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #fff;
            margin-bottom: 1rem;
        }

        .tag-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .row-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="admin-wrapper">
        <div class="admin-container">
            <header class="admin-header">
                <div class="admin-title-area">
                    <h1>Añadir Nuevo Spot</h1>
                </div>
            </header>

            <div class="admin-card">
                <form action="{{ route('admin.spots.store') }}" method="POST" enctype="multipart/form-data"
                    class="admin-form">
                    @csrf

                    {{-- Fila 1: Nombre --}}
                    <div class="form-group">
                        <label class="form-label">Nombre del Spot</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                    </div>

                    {{-- Fila 2: Municipio y Tipo --}}
                    <div class="row-inputs">
                        <div class="form-group">
                            <label class="form-label">Municipio</label>
                            <select name="municipio_id" class="form-control" required>
                                <option value="">Selecciona un municipio</option>
                                @foreach ($municipios as $m)
                                    <option value="{{ $m->id }}" {{ old('municipio_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo</label>
                            <select name="tipo" class="form-control" required>
                                <option value="playa" {{ old('tipo') == 'playa' ? 'selected' : '' }}>Playa</option>
                                <option value="monte" {{ old('tipo') == 'monte' ? 'selected' : '' }}>Monte</option>
                            </select>
                        </div>
                    </div>

                    {{-- Fila 3: Latitud y Longitud --}}
                    <div class="row-inputs">
                        <div class="form-group">
                            <label class="form-label">Latitud</label>
                            <input type="text" name="latitud" class="form-control" value="{{ old('latitud') }}"
                                placeholder="43.32158">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Longitud</label>
                            <input type="text" name="longitud" class="form-control" value="{{ old('longitud') }}"
                                placeholder="-1.98564">
                        </div>
                    </div>

                    {{-- Etiquetas --}}
                    <div class="form-group">
                        <label class="form-label">Etiquetas (Características)</label>
                        <div class="tags-grid">
                            @foreach($etiquetas as $etiqueta)
                                <label class="tag-item">
                                    <input type="checkbox" name="etiquetas[]" value="{{ $etiqueta->id }}" {{ is_array(old('etiquetas')) && in_array($etiqueta->id, old('etiquetas')) ? 'checked' : '' }}>
                                    {{ $etiqueta->nombre }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div class="form-group">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
                    </div>

                    {{-- Fotos --}}
                    <div class="form-group">
                        <label class="form-label">Subir 3 Fotos (Requerido)</label>
                        <input type="file" name="fotos[]" class="form-control" multiple required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Guardar Spot</button>
                        <a href="{{ route('admin.spots.index') }}" class="btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection