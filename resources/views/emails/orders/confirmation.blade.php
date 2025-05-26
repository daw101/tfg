@component('mail::message')
# ¡Gracias por tu compra!

Tu pedido **#{{ $order->id }}** ha sido registrado el {{ $order->order_date->format('d/m/Y H:i') }}.

**Total:** {{ number_format($order->total, 2) }}€

@component('mail::button', ['url' => url('/orders/'.$order->id)])
Ver detalles de tu pedido
@endcomponent

¡Gracias por confiar en nosotros!  
SportsZone
@endcomponent
