<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function items()
    {
        return $this->hasMany(CarritoProducto::class);
    }

    // Relación para traer productos directamente
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'carrito_productos')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}
