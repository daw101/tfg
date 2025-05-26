<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'payment_method_id', 'payment_date', 'amount'];

    // Un pago pertenece a un pedido
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Un pago pertenece a un método de pago
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
