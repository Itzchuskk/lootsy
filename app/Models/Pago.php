<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'pedido_id', 'amount', 'method', 'status', 'reference', 'raw_payload',
    ];

    protected $casts = [
        'raw_payload' => 'array',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
