<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'cart_id'];

    // Un método de pago pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un método de pago pertenece a un carrito
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Un método de pago puede tener muchos pagos
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
