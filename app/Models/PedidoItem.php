<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    protected $fillable = [
        'pedido_id','producto_id','nombre','precio','cantidad','subtotal'
    ];

    public function pedido(){ return $this->belongsTo(Pedido::class); }
    public function producto(){ return $this->belongsTo(\App\Models\Producto::class); }
}

