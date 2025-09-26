<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['user_id','status','payment_method','total'];
    public function items(){ return $this->hasMany(PedidoItem::class); }
    public function pago(){ return $this->hasOne(Pago::class); }
}
