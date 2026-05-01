<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden llenar masivamente
     * al crear o editar un cliente.
     */
    protected $fillable = [
        'name',       // Nombre completo
        'email',      // Correo electrónico
        'phone',      // Teléfono o celular
        'address',    // Dirección de envío
        'city',       // Ciudad
        'department', // Departamento
        'notes',      // Notas internas
        'is_active',  // Activo o bloqueado
    ];

    /**
     * Convierte los campos al tipo correcto
     * al leerlos desde la base de datos.
     */
    protected $casts = [
        'is_active' => 'boolean', // Como true/false
    ];

    /**
     * Relación: un cliente puede tener muchas órdenes.
     * Permite hacer: $customer->orders
     * Corregido: Order::class en mayúscula y descomentado
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}