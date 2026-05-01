{{-- Template del email de confirmación de pedido --}}
{{-- Usa los componentes de Markdown Mail de Laravel --}}
{{-- Se envía al cliente después de completar el checkout --}}

@component('mail::message')

# ¡Pedido confirmado!

{{-- Saludo personalizado con el nombre del cliente --}}
Hola **{{ $order->customer->name }}**, tu pedido fue registrado exitosamente.

---

{{-- Panel con los datos principales de la orden --}}
@component('mail::panel')
**Número de orden:** {{ $order->order_number }}
**Estado:** Pendiente
**Método de pago:** {{ ucfirst($order->payment_method) }}
**Dirección de envío:** {{ $order->shipping_address }}
@endcomponent

---

## Productos

{{-- Tabla con el detalle de productos comprados --}}
{{-- Los datos de cantidad y precio vienen de la tabla pivot order_items --}}
@component('mail::table')
| Producto | Cantidad | Precio |
|:---------|:--------:|-------:|
@foreach($order->products as $product)
| {{ $product->name }} | {{ $product->pivot->quantity }} | S/. {{ number_format($product->pivot->price, 2) }} |
@endforeach
@endcomponent

---

{{-- Panel con el resumen de costos --}}
@component('mail::panel')
| | |
|:--|--:|
| **Subtotal** | S/. {{ number_format($order->subtotal, 2) }} |
| **Envío** | S/. {{ number_format($order->shipping, 2) }} |
| **Total** | **S/. {{ number_format($order->total, 2) }}** |
@endcomponent

{{-- Mensaje de seguimiento --}}
Nos pondremos en contacto contigo para coordinar la entrega.

{{-- Botón de llamada a la acción --}}
@component('mail::button', ['url' => 'http://localhost:3000', 'color' => 'blue'])
Ver tienda
@endcomponent

Gracias por tu compra,
**ESO.MERCH**

@endcomponent