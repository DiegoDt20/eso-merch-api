<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

/**
 * Notificación que se envía al admin cuando se crea un nuevo pedido.
 * Usa el formato nativo de Filament para aparecer en la campana.
 */
class NewOrderNotification extends Notification
{
    use Queueable;

    /**
     * Recibe la orden para mostrar sus datos en la notificación.
     */
    public function __construct(public Order $order) {}

    /**
     * Define que la notificación se guarda en la base de datos.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Formato nativo de Filament para la notificación.
     * Aparece correctamente en el panel con ícono y mensaje.
     */
    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Nuevo pedido recibido')
            ->icon('heroicon-o-shopping-bag')
            ->iconColor('success')
            ->body("Pedido {$this->order->order_number} — S/. {$this->order->total}")
            ->getDatabaseMessage();
    }
}