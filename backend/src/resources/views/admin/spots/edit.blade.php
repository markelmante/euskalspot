@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}?v={{ time() }}">
    <style>
        .tags-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 1rem; }
        .tag-item { display: flex; align-items: center; gap: 8px; }
        .row-inputs { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .current-photos { display: flex; gap: 10px; margin-bottom: 10px; }
        .current-photos img { width: 80px; height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd; }
    </style>
@endpush

@section('content')
<div class="admin-wrapper">
    <div class="admin-container">
        <header class="admin-header"><h1>Editar Spot: {{ $spot->nombre }}</h1></header>

        <div class="admin-card">
            <form action="{{ route('admin.spots.update', $spot->id) }}" method="POST" enctype="multipart/form-data" class="admin-form">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $spot->nombre) }}" required>
                </div>

                <div class="row-inputs">
                    <div class="form-group">
                        <label class="form-label">Municipio</label>
                        <select name="municipio_id" class="form-control" required>
                            @foreach ($municipios as $m)
                                <option value="{{ $m->id }}" {{ old('municipio_id', $spot->municipio_id) == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-control" required>
                            <option value="playa" {{ old('tipo', $spot->tipo) == 'playa' ? 'selected' : '' }}>Playa</option>
                            <option value="monte" {{ old('tipo', $spot->tipo) == 'monte' ? 'selected' : '' }}>Monte</option>
                        </select>
                    </div>
                </div>

                <div class="row-inputs">
                    <div class="form-group">
                        <label class="form-label">Latitud</label>
                        <input type="text" name="latitud" class="form-control" value="{{ old('latitud', $spot->latitud) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Longitud</label>
                        <input type="text" name="longitud" class="form-control" value="{{ old('longitud', $spot->longitud) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Etiquetas actuales</label>
                    <div class="tags-grid">
                        @foreach($etiquetas as $etiqueta)
                            <label class="tag-item">
                                <input type="checkbox" name="etiquetas[]" value="{{ $etiqueta->id }}" 
                                    {{ in_array($etiqueta->id, old('etiquetas', $spotEtiquetasIds)) ? 'checked' : '' }}>
                                {{ $etiqueta->nombre }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $spot->descripcion) }}</textarea>
                </div>

                <div class="form-group">
    <label class="form-label">Fotos actuales</label>
    <div class="current-photos">
        @if($spot->foto)
                        {{-- Forzamos a que siempre sea un array, por si acaso --}}
                        @php
                            $fotos = is_array($spot->foto) ? $spot->foto : json_decode($spot->foto, true);
                        @endphp

                        @if(is_array($fotos))
                            @foreach($fotos as $f)
                                <img src="{{ asset('storage/' . $f) }}" alt="Foto del spot">
                            @endforeach
                        @endif
                    @else
                        <p class="text-muted">No hay fotos guardadas.</p>
                    @endif
                </div>
                <label class="form-label">Reemplazar fotos (sube 3 nuevas si deseas cambiar)</label>
                <input type="file" name="fotos[]" class="form-control" multiple>
            </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Actualizar Spot</button>
                    <a href="{{ route('admin.spots.index') }}" class="btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection