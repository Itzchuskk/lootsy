<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Carrito;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Pago;

class CarritoController extends Controller
{
    // Ver carrito
    public function index(Request $request)
    {
        $cartId = $request->session()->get('cart_id');

        if (!$cartId || !Carrito::find($cartId)) {
            $carrito = Carrito::create();
            $request->session()->put('cart_id', $carrito->id);
        } else {
            $carrito = Carrito::with(['items.producto'])->find($cartId);
        }

        return view('carrito', compact('carrito'));
    }

    // Agregar producto al carrito (POST)
    public function agregar(Request $request, $productoId)
    {
        $producto = Producto::findOrFail($productoId);

        if (!is_null($producto->stock) && $producto->stock <= 0) {
            return back()->with('error', 'Producto sin stock.');
        }

        $cartId = $request->session()->get('cart_id');
        if (!$cartId || !Carrito::find($cartId)) {
            $carrito = Carrito::create();
            $request->session()->put('cart_id', $carrito->id);
        } else {
            $carrito = Carrito::find($cartId);
        }

        DB::transaction(function () use ($carrito, $producto) {
            $item = $carrito->items()->where('producto_id', $producto->id)->first();

            if ($item) {
                $item->cantidad += 1;
                $item->save();
            } else {
                $carrito->items()->create([
                    'producto_id' => $producto->id,
                    'cantidad'    => 1,
                ]);
            }
        });

        return redirect()->route('carrito.index')->with('success', 'Producto agregado al carrito.');
    }

    // Eliminar 1 unidad o el item completo
    public function eliminar(Request $request, $productoId)
    {
        $cartId = $request->session()->get('cart_id');
        if (!$cartId) return back()->with('error', 'Carrito no encontrado.');

        $carrito = Carrito::find($cartId);
        if (!$carrito) return back()->with('error', 'Carrito no encontrado.');

        $item = $carrito->items()->where('producto_id', $productoId)->first();
        if (!$item) return back()->with('error', 'Producto no está en el carrito.');

        if ($item->cantidad > 1) {
            $item->cantidad -= 1;
            $item->save();
        } else {
            $item->delete();
        }

        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito.');
    }

    // Resumen/confirmación de pago (GET)
    public function checkoutResumen(Request $request)
    {
        $cartId = $request->session()->get('cart_id');
        if (!$cartId) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        $carrito = Carrito::with('items.producto')->find($cartId);
        if (!$carrito || $carrito->items->isEmpty()) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        $subtotal = 0;
        foreach ($carrito->items as $item) {
            $subtotal += ($item->producto->precio ?? 0) * $item->cantidad;
        }
        $iva   = round($subtotal * 0.16, 2);
        $total = round($subtotal + $iva, 2);

        $metodos = [
            'tarjeta' => 'Tarjeta de crédito / débito',
            'paypal'  => 'PayPal',
            'oxxo'    => 'OXXO / Pago en efectivo',
        ];

        return view('checkout', compact('carrito', 'subtotal', 'iva', 'total', 'metodos'));
    }

    // Pagar (POST): crea Pedido + Items + Pago y descuenta stock
    public function checkoutPagar(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|in:tarjeta,paypal,oxxo',
        ], [
            'metodo_pago.required' => 'Selecciona un método de pago.',
            'metodo_pago.in'       => 'Método de pago no válido.',
        ]);

        $cartId = $request->session()->get('cart_id');
        if (!$cartId) {
            return redirect()->route('carrito.index')->with('error', 'No hay carrito activo.');
        }

        $carrito = Carrito::with('items.producto')->find($cartId);
        if (!$carrito || $carrito->items->isEmpty()) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        // Recalcular totales + validar stock
        $subtotal = 0;
        foreach ($carrito->items as $item) {
            $producto = $item->producto;
            if (!$producto) {
                return back()->with('error', 'Hay un producto inválido en el carrito.');
            }
            if (!is_null($producto->stock) && $producto->stock < $item->cantidad) {
                return back()->with('error', "Stock insuficiente para {$producto->nombre}.");
            }
            $subtotal += ($producto->precio ?? 0) * $item->cantidad;
        }
        $iva   = round($subtotal * 0.16, 2);
        $total = round($subtotal + $iva, 2);

        // Transacción: descuenta stock + crea pedido/items/pago + limpia carrito
        try {
            DB::transaction(function () use ($carrito, $total, $request) {

                // 1) Pedido
                $pedido = Pedido::create([
                    'user_id'        => auth()->id(),
                    'status'         => 'paid',                 // si integras un gateway real, inicia en 'pending'
                    'payment_method' => $request->metodo_pago,  // tarjeta|paypal|oxxo
                    'total'          => $total,
                ]);

                // 2) Items (snapshot)
                foreach ($carrito->items as $item) {
                    $prod = $item->producto;

                    // Stock real: descuenta si no es null
                    if (!is_null($prod->stock)) {
                        $prod->decrement('stock', $item->cantidad);
                        if ($prod->stock < 0) {
                            throw new \Exception("Stock insuficiente para {$prod->nombre}");
                        }
                    }

                    PedidoItem::create([
                        'pedido_id'   => $pedido->id,
                        'producto_id' => $prod->id,
                        'nombre'      => $prod->nombre,
                        'precio'      => $prod->precio ?? 0,
                        'cantidad'    => $item->cantidad,
                        'subtotal'    => ($prod->precio ?? 0) * $item->cantidad,
                    ]);
                }

                // 3) Pago (simulado)
                Pago::create([
                    'pedido_id' => $pedido->id,
                    'amount'    => $total,
                    'method'    => $request->metodo_pago,
                    'status'    => 'paid',                      // cambia según la respuesta del gateway
                    'reference' => 'SIM-'.strtoupper(Str::random(10)),
                ]);

                // 4) Limpiar carrito
                $carrito->items()->delete();
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo completar el pago: '.$e->getMessage());
        }

        // Cerrar carrito de la sesión
        $request->session()->forget('cart_id');

        // Redirigir al detalle del pedido más reciente del usuario (o al catálogo)
        $ultimoPedido = Pedido::latest('id')->first();
        if ($ultimoPedido) {
            return redirect()->route('pedidos.show', $ultimoPedido->id)
                ->with('success', '¡Compra completada!');
        }

        return redirect()->route('productos.index')->with('success', "¡Pago realizado! Total: \${$total} USD");
    }
}
