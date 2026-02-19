@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="admin-wrapper">
        <div class="admin-container">

            {{-- HEADER --}}
            <header class="admin-header">
                <div class="admin-title-area">
                    <h1>Editar Usuario</h1>
                    <p>Modificando datos de: <strong>{{ $user->name }}</strong></p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary"
                    style="color: var(--text-muted); text-decoration: none; font-weight: 500;">
                    &larr; Volver a la lista
                </a>
            </header>

            {{-- FORMULARIO --}}
            <div class="admin-card">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="admin-form">
                    @csrf
                    @method('PUT')

                    {{-- Errores --}}
                    @if ($errors->any())
                        <div class="admin-badge" style="background-color: #fee2e2; color: #dc2626; margin-bottom: 1rem;">
                            <ul style="margin: 0; padding-left: 1rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row-inputs">
                        <div class="form-group">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    <div class="form-group" style="max-width: 50%;">
                        <label for="role" class="form-label">Rol del Usuario</label>
                        <select name="role" id="role" class="form-control">
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Usuario Normal</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                        <p style="font-size: 0.85rem; color: #64748b; margin-top: 0.5rem;">
                            ⚠️ <strong>Atención:</strong> Dar rol de Administrador permite acceso completo a este panel.
                        </p>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Actualizar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection