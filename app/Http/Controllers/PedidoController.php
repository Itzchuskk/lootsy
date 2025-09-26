<?php

namespace App\Http\Controllers;

use App\Models\Pedido;

class PedidoController extends Controller
{
    // /pedidos/{pedido}
    public function show(Pedido $pedido)
    {
        // carga relaciones para el resumen
        $pedido->load('items.producto', 'pago');
        return view('pedidos.show', compact('pedido'));
    }
}
