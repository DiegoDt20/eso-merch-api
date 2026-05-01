<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Clase Mailable para el email de confirmación de pedido.
 * Se envía automáticamente al cliente cuando su orden es creada.
 * Usa Markdown para el template del correo.
 */
class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Recibe la orden completa para usarla en el template.
     * Al declarar como 'public', la orden queda disponible
     * automáticamente en la vista del email.
     */
    public function __construct(public Order $order) {}

    /**
     * Define el asunto del correo.
     * Incluye el número de orden para que sea identificable
     * fácilmente en la bandeja de entrada del cliente.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Pedido confirmado — ' . $this->order->order_number,
        );
    }

    /**
     * Define el template y los datos que se envían a la vista.
     * Usa Markdown de Laravel Mail para un diseño limpio
     * y compatible con todos los clientes de correo.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order-confirmation',
            with: ['order' => $this->order],
        );
    }
}