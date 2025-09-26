<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PedidoController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Catálogo
Route::get('/catalogo', [ProductoController::class, 'index'])->name('productos.index');

// Carrito (siempre vía controlador)
Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('/carrito/agregar/{productoId}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::delete('/carrito/eliminar/{productoId}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
//Route::post('/carrito/checkout', [CarritoController::class, 'checkout'])->name('carrito.checkout');

// vista producto 

Route::get('/producto/{producto}', [ProductoController::class, 'show'])->name('productos.show');

// Pedido
Route::get('pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');


// Checkout en 2 pasos
Route::get('/checkout', [CarritoController::class, 'checkoutResumen'])->name('carrito.checkout.resumen');
Route::post('/checkout/pagar', [CarritoController::class, 'checkoutPagar'])->name('carrito.checkout.pagar');

// Login
Route::get('/login', function () {
    return view('login');
});
