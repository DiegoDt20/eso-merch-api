<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla 'order_items' en la base de datos.
     * Es la tabla intermedia entre órdenes y productos.
     * Guarda qué productos y en qué cantidad se compró
     * en cada orden, junto al precio en ese momento.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            // Clave primaria autoincremental
            $table->id();

            // Referencia a la orden
            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            // Referencia al producto comprado
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            // Cantidad de este producto en la orden
            $table->integer('quantity')->default(1);

            // Precio unitario al momento de la compra
            // Se guarda aquí porque el precio puede cambiar después
            $table->decimal('price', 10, 2);

            // Fechas automáticas
            $table->timestamps();
        });
    }

    /**
     * Elimina la tabla si se revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};