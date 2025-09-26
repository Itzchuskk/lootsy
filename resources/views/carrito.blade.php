@extends('layouts.app')

@section('content')
<style>
  .cart-hero{
    position:relative; border-radius:1rem; overflow:hidden;
    background: radial-gradient(900px 450px at -10% -10%, #0ea5e955 0%, transparent 60%),
                radial-gradient(800px 400px at 120% 0%, #22c55e55 0%, transparent 60%),
                linear-gradient(135deg, #0f172a 0%, #111827 100%);
    color:#fff;
  }
  .cart-hero::after{
    content:""; position:absolute; inset:0;
    background:url('https://images.unsplash.com/photo-1603484477859-abe6a73f9361?q=80&w=2060&auto=format&fit=crop') center/cover no-repeat;
    mix-blend-mode:overlay; opacity:.2;
  }
  .card-soft{ border:1px solid rgba(0,0,0,.06); border-radius:1rem; }
  .table> :not(caption)>*>*{ vertical-align: middle; }
</style>

<div class="container py-4 py-md-5">

  {{-- HERO --}}
  <section class="cart-hero mb-4">
    <div class="position-relative p-4 p-md-5 d-flex align-items-end justify-content-between">
      <div>
        <h1 class="h2 fw-bold mb-1">Tu carrito</h1>
        <p class="text-white-50 mb-0">Revisa tus artículos y finaliza tu compra.</p>
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

  @php
    $hayItems = $carrito && $carrito->items && $carrito->items->count() > 0;
  @endphp

  @if(!$hayItems)
    <div class="text-center py-5">
      <h4 class="mb-2">Tu carrito está vacío</h4>
      <p class="text-muted">Agrega algunos juegos desde el catálogo.</p>
      <a href="{{ route('productos.index') }}" class="btn btn-primary">Ir al catálogo</a>
    </div>
  @else
    <div class="row g-4">
      <div class="col-lg-8">
        <div class="card card-soft shadow-sm">
          <div class="card-body p-3 p-md-4">
            <div class="table-responsive">
              <table class="table align-middle">
                <thead class="table-light">
                  <tr>
                    <th style="width:70px"></th>
                    <th>Producto</th>
                    <th class="text-end">Precio</th>
                    <th class="text-center" style="width:160px">Cantidad</th>
                    <th class="text-end">Subtotal</th>
                    <th class="text-center" style="width:80px">Quitar</th>
                  </tr>
                </thead>
                <tbody>
                  @php $subtotal = 0; @endphp
                  @foreach($carrito->items as $item)
                    @php
                      $precio = $item->producto->precio ?? 0;
                      $sub    = $precio * $item->cantidad;
                      $subtotal += $sub;
                      $img = $item->producto->imagen ?: 'https://picsum.photos/seed/cart'.$item->id.'/120/90';
                      $detalleUrl = route('productos.show', $item->producto->slug ?? $item->producto->id);
                    @endphp
                    <tr>
                      <td>
                        <a href="{{ $detalleUrl }}">
                          <img src="{{ $img }}" alt="" class="rounded" style="width:70px;height:50px;object-fit:cover">
                        </a>
                      </td>
                      <td class="fw-medium">
                        <a class="text-reset text-decoration-none" href="{{ $detalleUrl }}">
                          {{ $item->producto->nombre ?? 'Producto' }}
                        </a>
                      </td>
                      <td class="text-end">${{ number_format($precio,2) }} USD</td>
                      <td class="text-center">
                        <div class="d-inline-flex align-items-center gap-1">
                          {{-- -1 --}}
                          <form action="{{ route('carrito.eliminar', $item->producto_id) }}" method="POST" onsubmit="return confirm('¿Quitar una unidad?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-secondary btn-sm" title="Quitar 1">−</button>
                          </form>
                          <span class="px-2">{{ $item->cantidad }}</span>
                          {{-- +1 --}}
                          <form action="{{ route('carrito.agregar', $item->producto_id) }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-secondary btn-sm" title="Agregar 1">+</button>
                          </form>
                        </div>
                      </td>
                      <td class="text-end">${{ number_format($sub,2) }} USD</td>
                      <td class="text-center">
                        {{-- eliminar línea completa (si quieres quitar todo, presiona hasta que llegue a 0 o cambia lógica a delete full) --}}
                        <form action="{{ route('carrito.eliminar', $item->producto_id) }}" method="POST" onsubmit="return confirm('¿Quitar una unidad?')">
                          @csrf @method('DELETE')
                          <button class="btn btn-danger btn-sm" title="Quitar">
                            <span aria-hidden="true">🗑️</span>
                          </button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>

      {{-- RESUMEN --}}
      <div class="col-lg-4">
        @php
          $iva   = round($subtotal * 0.16, 2);
          $total = round($subtotal + $iva, 2);
        @endphp
        <div class="card card-soft shadow-sm">
          <div class="card-body p-3 p-md-4">
            <h5 class="fw-bold mb-3">Resumen</h5>
            <div class="d-flex justify-content-between mb-1">
              <span class="text-muted">Subtotal</span>
              <span class="fw-semibold">${{ number_format($subtotal,2) }} USD</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span class="text-muted">IVA (16%)</span>
              <span class="fw-semibold">${{ number_format($iva,2) }} USD</span>
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-between fs-5">
              <span class="fw-bold">Total</span>
              <span class="fw-bold">${{ number_format($total,2) }} USD</span>
            </div>

            <div class="d-grid gap-2 mt-3">
              <a href="{{ route('carrito.checkout.resumen') }}" class="btn btn-success">
                Finalizar compra
              </a>
              <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Seguir comprando</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
@endsection
