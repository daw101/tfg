<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    // Un carrito pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un carrito puede tener muchos productos (muchos a muchos)
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity')->withTimestamps();
    }

    // Un carrito puede tener varios métodos de pago
    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }
}
