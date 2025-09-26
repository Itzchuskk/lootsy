@extends('layouts.app')

@section('content')
<style>
  .hero-catalog{
    position:relative; border-radius:1rem; overflow:hidden;
    background: radial-gradient(900px 450px at -10% -10%, #0ea5e955 0%, transparent 60%),
                radial-gradient(800px 400px at 120% 0%, #22c55e55 0%, transparent 60%),
                linear-gradient(135deg, #0f172a 0%, #111827 100%);
    color:#fff;
  }
  .hero-catalog::after{
    content:""; position:absolute; inset:0;
    background:url('https://images.unsplash.com/photo-1605901309584-818e25960a8a?q=80&w=2060&auto=format&fit=crop') center/cover no-repeat;
    mix-blend-mode:overlay; opacity:.2;
  }
  .card-product:hover { transform: translateY(-4px); transition: .2s ease; }
  .badge-out { position:absolute; top:.5rem; left:.5rem; }
</style>

<div class="container py-4 py-md-5">

  {{-- HERO --}}
  <section class="hero-catalog mb-4">
    <div class="position-relative p-4 p-md-5">
      <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
        <div>
          <h1 class="h2 fw-bold mb-1">Catálogo</h1>
          <p class="text-white-50 mb-0">Descubre juegos digitales y tarjetas al instante.</p>
        </div>
        <div class="text-md-end">
          <span class="badge bg-dark text-white-50 px-3 py-2">
            {{ $productos instanceof \Illuminate\Support\Collection ? $productos->count() : (is_countable($productos) ? count($productos) : 0) }} productos
          </span>
        </div>
      </div>
    </div>
  </section>

  {{-- MENSAJES --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  {{-- GRID DE PRODUCTOS --}}
  @if(($productos instanceof \Illuminate\Support\Collection ? $productos->count() : (is_countable($productos) ? count($productos) : 0)) > 0)
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
      @foreach($productos as $producto)
        @php
          $sinStock = !is_null($producto->stock) && $producto->stock <= 0;
          $detalleUrl = route('productos.show', $producto->slug ?? $producto->id);
          $img = $producto->imagen ?: 'https://picsum.photos/seed/prod'.$producto->id.'/600/400';
        @endphp
        <div class="col">
          <div class="card h-100 card-product shadow-sm">
            <div class="position-relative">
              <a href="{{ $detalleUrl }}" class="text-decoration-none">
                <img src="{{ $img }}" class="card-img-top" alt="{{ $producto->nombre }}"
                     style="aspect-ratio:4/3;object-fit:cover">
              </a>
              @if($sinStock)
                <span class="badge bg-danger badge-out">Sin stock</span>
              @endif
            </div>

            <div class="card-body d-flex flex-column">
              <h5 class="card-title text-truncate mb-1">
                <a href="{{ $detalleUrl }}" class="text-reset text-decoration-none">
                  {{ $producto->nombre }}
                </a>
              </h5>
              <div class="text-muted mb-3">${{ number_format($producto->precio,2) }} USD</div>

              <div class="mt-auto d-flex gap-2">
                <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
                  @csrf
                  <button class="btn btn-primary btn-sm" {{ $sinStock ? 'disabled' : '' }}>
                    {{ $sinStock ? 'Agotado' : 'Agregar' }}
                  </button>
                </form>
                <a href="{{ $detalleUrl }}" class="btn btn-outline-secondary btn-sm">Detalles</a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="text-center py-5">
      <h4 class="mb-2">Aún no hay productos</h4>
      <p class="text-muted">Cuando se publiquen, aparecerán aquí.</p>
    </div>
  @endif
</div>
@endsection
