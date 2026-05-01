<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla 'customers' en la base de datos.
     * Almacena los datos de los clientes que compran
     * productos de merchandising.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            // Clave primaria autoincremental
            $table->id();

            // Nombre completo del cliente
            $table->string('name');

            // Correo electrónico único por cliente
            $table->string('email')->unique();

            // Número de teléfono o celular
            $table->string('phone')->nullable();

            // Dirección de envío
            $table->string('address')->nullable();

            // Ciudad del cliente
            $table->string('city')->nullable();

            // Departamento (Lima, Cusco, etc.)
            $table->string('department')->nullable();

            // Notas internas sobre el cliente
            $table->text('notes')->nullable();

            // Si el cliente está activo o bloqueado
            $table->boolean('is_active')->default(true);

            // Fechas de creación y actualización automáticas
            $table->timestamps();
        });
    }

    /**
     * Elimina la tabla si se revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};