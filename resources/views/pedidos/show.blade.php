@extends('layouts.app')

@section('content')
<style>
  .order-hero{
    position:relative; border-radius:1rem; overflow:hidden;
    background: radial-gradient(800px 400px at -10% -10%, #0ea5e955 0%, transparent 60%),
                radial-gradient(700px 350px at 120% 0%, #22c55e55 0%, transparent 60%),
                linear-gradient(135deg, #0f172a 0%, #111827 100%);
    color:#fff;
  }
  .order-hero::after{
    content:""; position:absolute; inset:0;
    background:url('https://images.unsplash.com/photo-1603484477859-abe6a73f9361?q=80&w=2060&auto=format&fit=crop') center/cover no-repeat;
    mix-blend-mode:overlay; opacity:.2;
  }
  .glass{ backdrop-filter: blur(8px); background: rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); border-radius:1rem; }
  .card-soft{ border:1px solid rgba(0,0,0,.06); border-radius:1rem; }
  .mini{ font-size:.9rem }
  .text-muted-2{ color:#6b7280 }
  .table> :not(caption)>*>*{ vertical-align: middle; }
</style>

@php
  $statusMap = [
    'paid'     => ['Pagado',   'success',  'Compra confirmada'],
    'pending'  => ['Pendiente','warning',  'Pago en proceso'],
    'failed'   => ['Fallido',  'danger',   'Pago rechazado'],
    'canceled' => ['Cancelado','secondary','Pedido cancelado'],
  ];
  [$statusLabel, $statusColor, $statusHint] = $statusMap[$pedido->status] ?? ['—','secondary','Estado no disponible'];

  $subtotal = $pedido->items->sum(fn($i) => (float)$i->subtotal);
  $iva      = max(round(($pedido->total ?? 0) - $subtotal, 2), 0);
@endphp

<div class="container py-4 py-md-5">

  {{-- HERO / ENCABEZADO --}}
  <section class="order-hero mb-4">
    <div class="position-relative p-4 p-md-5">
      <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
        <div>
          <h1 class="h2 fw-bold mb-1">Pedido #{{ $pedido->id }}</h1>
          <div class="mini text-white-50">
            Creado: {{ optional($pedido->created_at)->format('d M Y H:i') ?? '—' }}
            @if($pedido->user_id)
              · Cliente ID: {{ $pedido->user_id }}
            @endif
          </div>
        </div>
        <div class="text-md-end">
          <span class="badge bg-{{ $statusColor }} px-3 py-2 me-2">{{ $statusLabel }}</span>
          <span class="badge bg-dark text-white-50 px-3 py-2">Total: ${{ number_format($pedido->total,2) }} USD</span>
          <div class="mini text-white-50 mt-1">{{ $statusHint }}</div>
        </div>
      </div>
    </div>
  </section>

  <div class="row g-4">
    {{-- ITEMS --}}
    <div class="col-lg-8">
      <div class="card card-soft shadow-sm">
        <div class="card-body p-3 p-md-4">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="h5 mb-0">Artículos</h3>
            <span class="text-muted-2 mini">{{ $pedido->items->count() }} producto(s)</span>
          </div>

          <div class="table-responsive">
            <table class="table align-middle">
              <thead class="table-light">
                <tr>
                  <th style="width:70px"> </th>
                  <th>Producto</th>
                  <th class="text-end">Precio</th>
                  <th class="text-center">Cant.</th>
                  <th class="text-end">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @foreach($pedido->items as $it)
                  <tr>
                    <td>
                      @php
                        $img = optional($it->producto)->imagen;
                        if(!$img) $img = 'https://picsum.photos/seed/item'.$it->id.'/120/90';
                      @endphp
                      <img src="{{ $img }}" alt="" class="rounded" style="width:70px;height:50px;object-fit:cover">
                    </td>
                    <td class="fw-medium">{{ $it->nombre }}</td>
                    <td class="text-end">${{ number_format($it->precio,2) }}</td>
                    <td class="text-center">{{ $it->cantidad }}</td>
                    <td class="text-end">${{ number_format($it->subtotal,2) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>

    {{-- RESUMEN & PAGO --}}
    <div class="col-lg-4">
      <div class="card card-soft shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
          <h3 class="h6 fw-bold mb-3">Resumen</h3>

          <div class="d-flex justify-content-between mb-1">
            <span class="text-muted-2">Subtotal</span>
            <span class="fw-semibold">${{ number_format($subtotal,2) }} USD</span>
          </div>
          <div class="d-flex justify-content-between mb-1">
            <span class="text-muted-2">IVA (16%)</span>
            <span class="fw-semibold">${{ number_format($iva,2) }} USD</span>
          </div>
          <hr class="my-2">
          <div class="d-flex justify-content-between fs-5">
            <span class="fw-bold">Total</span>
            <span class="fw-bold">${{ number_format($pedido->total,2) }} USD</span>
          </div>
        </div>
      </div>

      <div class="card card-soft shadow-sm">
        <div class="card-body p-3 p-md-4">
          <h3 class="h6 fw-bold mb-3">Pago</h3>

          @if($pedido->pago)
            <div class="d-flex align-items-center justify-content-between mb-1">
              <span class="text-muted-2">Método</span>
              <span class="fw-semibold text-uppercase">{{ $pedido->pago->method ?? $pedido->payment_method ?? '—' }}</span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-1">
              <span class="text-muted-2">Estado</span>
              <span class="badge bg-{{ $pedido->pago->status === 'paid' ? 'success':'warning' }}">
                {{ ucfirst($pedido->pago->status) }}
              </span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-1">
              <span class="text-muted-2">Monto</span>
              <span class="fw-semibold">${{ number_format($pedido->pago->amount ?? 0,2) }} USD</span>
            </div>
            <div class="d-flex align-items-center justify-content-between">
              <span class="text-muted-2">Referencia</span>
              <code class="small">{{ $pedido->pago->reference ?? '—' }}</code>
            </div>
          @else
            <p class="text-muted-2 mb-0">No hay pago registrado.</p>
          @endif

          <div class="d-grid gap-2 mt-3">
            <button onclick="window.print()" class="btn btn-outline-secondary">
              🧾 Descargar/Imprimir comprobante
            </button>
            <a href="{{ route('productos.index') }}" class="btn btn-primary">Seguir comprando</a>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
