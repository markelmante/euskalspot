@extends('layouts.app')

@section('title', 'Explorar Spots')

{{-- Usamos la clase para el body si la necesitas --}}
@section('body-class', 'explorar-page')

@section('content')
    <h3>ESTO ES EL BACKEND</h3>
    <div class="container" style="margin-top: 40px; margin-bottom: 60px;">
        <div style="text-align: center; margin-bottom: 50px;">
            <h1 style="font-size: 2.5rem; color: var(--azul-dark);">Explora Euskal Herria</h1>
            <p style="color: #64748b;">Encuentra tu próximo destino de surf o montaña entre nuestros 50 spots seleccionados.
            </p>
        </div>

        <div style="display: flex; justify-content: center; gap: 15px; margin-bottom: 40px;">
            <button class="btn-login" id="filter-todos" onclick="filtrar('todos', this)"
                style="cursor:pointer; background: var(--azul); color: white;">Todos</button>
            <button class="btn-login" id="filter-playa" onclick="filtrar('playa', this)" style="cursor:pointer;">🏄
                Surf</button>
            <button class="btn-login" id="filter-monte" onclick="filtrar('monte', this)" style="cursor:pointer;">⛰️
                Montaña</button>
        </div>

        <div id="lista-spots" class="reviews-grid">
            <div style="grid-column: 1/-1; text-align: center; padding: 50px;">
                <p>Cargando spots mágicos...</p>
            </div>
        </div>
    </div>
@endsection