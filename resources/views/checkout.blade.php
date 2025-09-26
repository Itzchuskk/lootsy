@extends('layouts.app')

@section('content')
    <h1>Checkout</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <h4>Resumen de compra</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="text-end">Precio</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($carrito->items as $item)
                        @php
                            $precio = $item->producto->precio ?? 0;
                            $sub = $precio * $item->cantidad;
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $item->producto->imagen }}" alt="" width="60">
                                    <div>{{ $item->producto->nombre }}</div>
                                </div>
                            </td>
                            <td class="text-end">${{ number_format($precio, 2) }} USD</td>
                            <td class="text-center">{{ $item->cantidad }}</td>
                            <td class="text-end">${{ number_format($sub, 2) }} USD</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h5>Resumen</h5>
                <div class="d-flex justify-content-between">
                    <span>Subtotal</span>
                    <strong>${{ number_format($subtotal, 2) }} USD</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>IVA (16%)</span>
                    <strong>${{ number_format($iva, 2) }} USD</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between fs-5">
                    <span>Total</span>
                    <strong>${{ number_format($total, 2) }} USD</strong>
                </div>

                <hr>
                <form action="{{ route('carrito.checkout.pagar') }}" method="POST" class="mt-2">
                    @csrf
                    <h6>Método de pago</h6>
                    @foreach($metodos as $key => $label)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metodo_pago" id="mp_{{ $key }}"
                                value="{{ $key }}" {{ $loop->first ? 'checked' : '' }}>
                            <label class="form-check-label" for="mp_{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach

                    @error('metodo_pago')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror


                    <button class="btn btn-success w-100 mt-3">Pagar ahora</button>
                </form>
            </div>
        </div>
    </div>
@endsection