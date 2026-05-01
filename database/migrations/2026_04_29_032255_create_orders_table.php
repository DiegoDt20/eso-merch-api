<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla 'orders' en la base de datos.
     * Registra cada compra realizada por un cliente,
     * con sus productos, montos y estado de entrega.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            // Clave primaria autoincremental
            $table->id();

            // Número de orden legible (ej: ORD-2024-001)
            $table->string('order_number')->unique();

            // Cliente que realizó la compra
            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            // Estado actual de la orden
            // pending=pendiente, processing=en proceso,
            // shipped=enviado, delivered=entregado, cancelled=cancelado
            $table->enum('status', [
                'pending',
                'processing',
                'shipped',
                'delivered',
                'cancelled'
            ])->default('pending');

            // Subtotal antes de descuentos
            $table->decimal('subtotal', 10, 2)->default(0);

            // Costo de envío
            $table->decimal('shipping', 10, 2)->default(0);

            // Descuento aplicado
            $table->decimal('discount', 10, 2)->default(0);

            // Total final a pagar
            $table->decimal('total', 10, 2)->default(0);

            // Método de pago usado
            $table->string('payment_method')->nullable();

            // Si el pago fue confirmado
            $table->boolean('is_paid')->default(false);

            // Dirección de envío de esta orden
            $table->string('shipping_address')->nullable();

            // Notas adicionales de la orden
            $table->text('notes')->nullable();

            // Fechas de creación y actualización automáticas
            $table->timestamps();
        });
    }

    /**
     * Elimina la tabla si se revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};