<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega la columna category_id a la tabla products.
     * Esto permite relacionar cada producto con una categoría.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Clave foránea que apunta a la tabla categories
            // nullable() porque productos existentes no tienen categoría aún
            $table->foreignId('category_id')
                ->nullable()
                ->after('category')
                ->constrained()
                ->nullOnDelete();
        });
    }

    /**
     * Elimina la columna si se revierte la migración.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};