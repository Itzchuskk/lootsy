<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>LOOTSY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('ccs/style.css') }}">


</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">🎮 Lootsy </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/catalogo">CATÁLOGO</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('carrito.index') }}">
                            CARRITO @if(!empty($cartCount)) <span class="badge bg-primary">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="/login">INICIAR SESIÓN</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>
</body>

</html>