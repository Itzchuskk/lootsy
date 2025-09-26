@extends('layouts.app')

@section('content')
<style>
  .hero {
    position: relative;
    min-height: 60vh;
    border-radius: 1rem;
    overflow: hidden;
    background:
      radial-gradient(1200px 600px at -10% -10%, #0ea5e9 0%, rgba(14,165,233,0) 60%),
      radial-gradient(1000px 500px at 110% 10%, #22c55e 0%, rgba(34,197,94,0) 60%),
      linear-gradient(135deg, #0f172a 0%, #111827 100%);
    color: #fff;
  }
  .hero::after{
    content:"";
    position:absolute; inset:0;
    background: url('https://images.unsplash.com/photo-1603484477859-abe6a73f9361?q=80&w=2060&auto=format&fit=crop') center/cover no-repeat;
    mix-blend-mode: overlay; opacity:.25;
  }
  .glass {
    backdrop-filter: blur(8px);
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 1rem;
  }
  .feature-icon {
    width: 44px; height: 44px; border-radius: 10px;
    display:grid; place-items:center; font-size:20px;
    background:#0ea5e933; color:#7dd3fc;
  }
  .card-product:hover { transform: translateY(-4px); transition: .2s ease; }
</style>

<div class="container py-4 py-md-5">

  {{-- HERO --}}
  <section class="hero mb-5 d-flex align-items-center">
    <div class="position-relative container py-5">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <h1 class="display-4 fw-bold mb-3">TE DAMOS LA BIENVENIDA A LOOTSY</h1>
          <p class="lead mb-4">Tu tienda de <strong>videojuegos y tarjetas digitales</strong> con entrega rápida, precios justos y soporte real.</p>
          <div class="d-flex gap-2">
            <a href="{{ route('productos.index') }}" class="btn btn-primary btn-lg">Ver catálogo</a>
            <a href="#quienes-somos" class="btn btn-outline-light btn-lg">Conócenos</a>
          </div>
          <p class="mt-3 text-white-50">{{ $totalProductos }}+ productos publicados</p>
        </div>
      </div>
    </div>
  </section>

  {{-- QUIÉNES SOMOS --}}
  <section id="quienes-somos" class="mb-5">
    <div class="row g-4 align-items-center">
      <div class="col-lg-6">
        <div class="glass p-4 p-md-5">
          <h2 class="h1 fw-bold mb-3">¿Quiénes somos?</h2>
          <p class="mb-3">En <strong>Lootsy</strong> somos gamers. Nacimos para que consigas tus juegos digitales y gift cards de forma <em>simple, segura y al instante</em>. Seleccionamos los mejores títulos y ofertas para que solo te preocupes por jugar.</p>
          <ul class="mb-0">
            <li>Entrega rápida y confiable.</li>
            <li>Precios competitivos y promociones frecuentes.</li>
            <li>Soporte cercano por si algo no sale como esperabas.</li>
          </ul>
        </div>
      </div>
      <div class="col-lg-6">
        <img class="w-100 rounded-4" alt="Lootsy" src="https://i.pinimg.com/736x/90/be/73/90be73f9faa01add39ccb7b94f69b352.jpg">
      </div>
    </div>
  </section>

  {{-- FEATURES --}}
  <section class="mb-5">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="glass p-4 h-100">
          <div class="feature-icon mb-3">⚡️</div>
          <h3 class="h5 fw-bold">Entrega inmediata</h3>
          <p class="text-secondary mb-0">Recibe tu código digital en minutos y empieza a jugar sin esperar.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="glass p-4 h-100">
          <div class="feature-icon mb-3">🏷️</div>
          <h3 class="h5 fw-bold">Precios justos</h3>
          <p class="text-secondary mb-0">Ofertas constantes y combos para que tu dinero rinda más.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="glass p-4 h-100">
          <div class="feature-icon mb-3">🛡️</div>
          <h3 class="h5 fw-bold">Compra segura</h3>
          <p class="text-secondary mb-0">Protegemos tu compra y te acompañamos hasta que actives tu juego.</p>
        </div>
      </div>
    </div>
  </section>

  {{-- CATEGORÍAS (enlazan al catálogo por ahora) --}}
  <section class="mb-5">
    <h2 class="fw-bold mb-3">Explora por categoría</h2>
    <div class="row g-3">
      @php
        $cats = [
          ['PS5', 'https://i.pinimg.com/1200x/83/28/1a/83281af14fcb9aecb13e857aec9a72fb.jpg'],
          ['PS4', 'https://i.pinimg.com/1200x/83/28/1a/83281af14fcb9aecb13e857aec9a72fb.jpg'],
          ['Xbox', 'https://i.pinimg.com/1200x/e2/46/61/e246613faaef5cce8c27ecb7da736c14.jpg'],
          ['Tarjetas digitales', 'https://i.pinimg.com/1200x/b0/7c/b1/b07cb11f27ceeee3430feddc1d2a3e0c.jpg'],
        ];
      @endphp
      @foreach($cats as [$label, $img])
        <div class="col-6 col-md-3">
          <a class="text-decoration-none" href="{{ route('productos.index') }}">
            <div class="position-relative rounded-4 overflow-hidden card-product">
              <img src="{{ $img }}" class="w-100" style="aspect-ratio: 4/3; object-fit: cover;" alt="{{ $label }}">
              <div class="position-absolute bottom-0 start-0 end-0 p-2" style="background:linear-gradient(to top, rgba(0,0,0,.6), transparent);">
                <span class="badge bg-light text-dark">{{ $label }}</span>
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </section>

  {{-- NOVEDADES --}}
  <section class="mb-5">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h2 class="fw-bold mb-0">Novedades</h2>
      <a href="{{ route('productos.index') }}" class="btn btn-sm btn-outline-secondary">Ver catálogo</a>
    </div>
    <div class="row g-3">
      @forelse($nuevos as $p)
        <div class="col-6 col-md-3">
          <div class="card h-100 card-product">
            <a href="{{ route('productos.show', $p->slug ?? $p->id) }}" class="text-decoration-none text-reset">
              <img src="{{ $p->imagen }}" class="card-img-top" alt="{{ $p->nombre }}" style="aspect-ratio:4/3;object-fit:cover">
            </a>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title mb-1 text-truncate">{{ $p->nombre }}</h5>
              <p class="text-muted mb-3">${{ number_format($p->precio,2) }} USD</p>
              <div class="mt-auto d-flex gap-2">
                <form action="{{ route('carrito.agregar', $p->id) }}" method="POST">
                  @csrf
                  <button class="btn btn-primary btn-sm">Agregar</button>
                </form>
                <a href="{{ route('productos.show', $p->slug ?? $p->id) }}" class="btn btn-outline-secondary btn-sm">Detalles</a>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12">
          <div class="alert alert-secondary">Aún no hay productos. ¡Agrega algunos en tu panel o seeder!</div>
        </div>
      @endforelse
    </div>
  </section>

  {{-- CTA final --}}
  <section class="glass p-4 p-md-5 mb-4 text-center">
    <h3 class="fw-bold mb-2">¿Listo para tu próximo juego?</h3>
    <p class="mb-3">Explora las últimas ofertas y completa tu biblioteca en minutos.</p>
    <a href="{{ route('productos.index') }}" class="btn btn-success btn-lg">Ir al catálogo</a>
  </section>

</div>
@endsection
