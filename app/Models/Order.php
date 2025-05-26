<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'cart_id', 'order_date', 'total'];

    // Un pedido pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un pedido pertenece a un carrito
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Un pedido tiene muchos pagos
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
