@extends('layouts.app')

@section('content')
    <h1>Productos</h1>

    <form action="{{ route('productos.store') }}" method="POST">
        @csrf
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="descripcion" placeholder="Descripción">
        <input type="number" name="precio" placeholder="Precio" required>
        <button type="submit">Guardar</button>
    </form>

    <h2>Lista de productos</h2>
    <ul>
        @foreach($productos as $producto)
            <li>{{ $producto->nombre }} - ${{ $producto->precio }}</li>
        @endforeach
    </ul>
@endsection
