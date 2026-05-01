<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla 'categories' en la base de datos.
     * Las categorías agrupan los productos del merchandising
     * (ej: Polos, Gorras, Stickers, Mochilas)
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            // Clave primaria autoincremental
            $table->id();

            // Nombre de la categoría (ej: "Polos", "Gorras")
            $table->string('name');

            // URL amigable (ej: "polos", "gorras")
            $table->string('slug')->unique();

            // Descripción opcional de la categoría
            $table->text('description')->nullable();

            // Imagen representativa de la categoría
            $table->string('image')->nullable();

            // Si la categoría está visible en la tienda
            $table->boolean('is_active')->default(true);

            // Orden de aparición en el menú (1, 2, 3...)
            $table->integer('sort_order')->default(0);

            // Fechas de creación y actualización automáticas
            $table->timestamps();

            // Permite eliminar sin borrar permanentemente
            $table->softDeletes();
        });
    }

    /**
     * Elimina la tabla si se revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};