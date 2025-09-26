@extends('layouts.app')

@section('content')
    <h1>Iniciar sesión</h1>
    <form class="w-50">
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="email">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password">
        </div>
        <button type="submit" class="btn btn-dark">Entrar</button>
    </form>
@endsection
