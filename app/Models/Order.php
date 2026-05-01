<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden llenar masivamente
     * al crear o editar una orden.
     */
    protected $fillable = [
        'order_number',     // Número de orden legible
        'customer_id',      // ID del cliente
        'status',           // Estado de la orden
        'subtotal',         // Subtotal antes de descuentos
        'shipping',         // Costo de envío
        'discount',         // Descuento aplicado
        'total',            // Total final
        'payment_method',   // Método de pago
        'is_paid',          // Si fue pagado
        'shipping_address', // Dirección de envío
        'notes',            // Notas adicionales
    ];

    /**
     * Convierte los campos al tipo correcto
     * al leerlos desde la base de datos.
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping' => 'decimal:2',
        'discount' => 'decimal:2',
        'total'    => 'decimal:2',
        'is_paid'  => 'boolean',
    ];

    /**
     * Se ejecuta automáticamente al crear/guardar una orden.
     * Genera el número de orden y envía notificación al admin.
     */
    protected static function boot(): void
    {
        parent::boot();

        // Genera número de orden único antes de guardar
        // Usa max('id') en lugar de count() para evitar duplicados
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $last = static::max('id') ?? 0; // ← FIX: evita UNIQUE constraint failed
                $order->order_number = 'ORD-' . date('Y') . '-' . str_pad(
                    $last + 1,
                    6, '0', STR_PAD_LEFT
                );
            }
        });

        // Envía notificación flash al admin cuando se crea un pedido
        static::created(function ($order) {
            cache()->put(
                'new_order_notification',
                "🛒 Nuevo pedido {$order->order_number} — S/. {$order->total}",
                now()->addHour()
            );
        });
    }

    /**
     * Relación: una orden pertenece a un cliente.
     * Permite hacer: $order->customer->name
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relación: una orden tiene muchos productos.
     * Usa tabla intermedia 'order_items'.
     * Permite hacer: $order->products
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot(['quantity', 'price'])
            ->withTimestamps();
    }
}