<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla 'products' en la base de datos.
     * Esta tabla almacena todos los productos del sistema
     * de merchandising (ropa, accesorios, etc.)
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            // Clave primaria autoincremental
            $table->id();

            // Nombre del producto (ej: "Polo ESO Logo 2024")
            $table->string('name');

            // URL amigable del producto (ej: "polo-eso-logo-2024")
            // Se genera automáticamente desde el nombre
            $table->string('slug')->unique();

            // Descripción detallada del producto
            $table->text('description')->nullable();

            // Precio normal del producto en soles
            $table->decimal('price', 10, 2);

            // Precio de oferta (opcional, null si no hay descuento)
            $table->decimal('sale_price', 10, 2)->nullable();

            // Cantidad disponible en inventario
            $table->integer('stock')->default(0);

            // Código único del producto para inventario (ej: "ESO-POLO-001")
            $table->string('sku')->unique()->nullable();

            // Imagen principal del producto (ruta del archivo)
            $table->string('image')->nullable();

            // Galería de imágenes adicionales (almacenadas como JSON)
            $table->json('images')->nullable();

            // Categoría del producto (ej: "polos", "gorras", "stickers")
            $table->string('category')->nullable();

            // Si el producto está visible en la tienda (true/false)
            $table->boolean('is_active')->default(true);

            // Si el producto aparece en sección destacados (true/false)
            $table->boolean('is_featured')->default(false);

            // Fecha de creación y última actualización (automáticas)
            $table->timestamps();

            // Permite "eliminar" sin borrar de la base de datos
            // El registro se oculta pero se puede recuperar
            $table->softDeletes();
        });
    }

    /**
     * Elimina la tabla 'products' si se revierte la migración.
     * Se usa cuando se ejecuta: php artisan migrate:rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};