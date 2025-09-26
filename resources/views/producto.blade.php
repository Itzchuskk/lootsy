@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Catálogo</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $producto->nombre }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- IMAGEN + MINI GALERÍA --}}
        <div class="col-md-5">
            <div class="border rounded p-2">
                <img src="{{ $producto->imagen }}" alt="{{ $producto->nombre }}" class="img-fluid w-100">
            </div>

            @php
                // Si guardas una galería (JSON) en la columna "galeria"
                $galeria = [];
                if(!empty($producto->galeria)) {
                    $galeria = is_array($producto->galeria) ? $producto->galeria : (json_decode($producto->galeria, true) ?? []);
                }
            @endphp

            @if(!empty($galeria))
                <div class="d-flex gap-2 mt-2 flex-wrap">
                    @foreach($galeria as $thumb)
                        <img src="{{ $thumb }}" class="rounded" style="width:80px;height:80px;object-fit:cover" alt="thumb">
                    @endforeach
                </div>
            @endif
        </div>

        {{-- INFO, PRECIO, DESCRIPCIÓN, ACCIONES --}}
        <div class="col-md-7">
            <h1 class="h3 mb-2">{{ $producto->nombre }}</h1>
            <p class="text-muted mb-2">ID: {{ $producto->id }}</p>

            <div class="d-flex align-items-baseline gap-2 mb-3">
                <span class="h4 mb-0">
                    ${{ number_format($producto->precio ?? 0, 2) }} USD
                </span>
                @if(!is_null($producto->stock))
                    <span class="badge {{ $producto->stock > 0 ? 'bg-success' : 'bg-secondary' }}">
                        {{ $producto->stock > 0 ? 'En stock' : 'Agotado' }}
                    </span>
                @endif
            </div>

            @if(!empty($producto->descripcion))
                <p class="mb-3">{{ $producto->descripcion }}</p>
            @endif

            <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST" class="mb-4">
                @csrf
                <button class="btn btn-primary btn-lg" {{ (!is_null($producto->stock) && $producto->stock <= 0) ? 'disabled' : '' }}>
                    Agregar al carrito
                </button>
                <a href="{{ route('carrito.checkout.resumen') }}" class="btn btn-outline-secondary btn-lg ms-2">
                    Comprar ahora
                </a>
            </form>

            {{-- ESPECIFICACIONES (solo muestra si existen) --}}
            <div class="card">
                <div class="card-header">Especificaciones</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tbody>
                            @if(!empty($producto->plataforma)) <tr><th style="width:30%">Plataforma</th><td>{{ $producto->plataforma }}</td></tr>@endif
                            @if(!empty($producto->genero))     <tr><th>Género</th><td>{{ $producto->genero }}</td></tr>@endif
                            @if(!empty($producto->modo))       <tr><th>Modo de juego</th><td>{{ $producto->modo }}</td></tr>@endif
                            @if(!empty($producto->lanzamiento))<tr><th>Fecha de lanzamiento</th><td>{{ $producto->lanzamiento }}</td></tr>@endif
                            @if(!empty($producto->publisher))  <tr><th>Publisher</th><td>{{ $producto->publisher }}</td></tr>@endif
                            @if(!empty($producto->idioma))     <tr><th>Idioma</th><td>{{ $producto->idioma }}</td></tr>@endif
                            @if(!empty($producto->region))     <tr><th>Región</th><td>{{ $producto->region }}</td></tr>@endif
                            @if(!empty($producto->formato))    <tr><th>Formato</th><td>{{ $producto->formato }}</td></tr>@endif
                            @if(!empty($producto->requisitos)) <tr><th>Requisitos</th><td>{!! nl2br(e($producto->requisitos)) !!}</td></tr>@endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
