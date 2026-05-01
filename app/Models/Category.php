<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    /**
     * SoftDeletes — elimina categorías de forma segura
     * sin borrarlas permanentemente de la base de datos.
     */
    use SoftDeletes;

    /**
     * Campos que se pueden llenar masivamente
     * al crear o editar una categoría.
     */
    protected $fillable = [
        'name',        // Nombre de la categoría
        'slug',        // URL amigable
        'description', // Descripción
        'image',       // Imagen representativa
        'is_active',   // Visible en tienda
        'sort_order',  // Orden de aparición
    ];

    /**
     * Convierte los campos al tipo correcto
     * al leerlos desde la base de datos.
     */
    protected $casts = [
        'is_active'  => 'boolean', // Como true/false
        'sort_order' => 'integer', // Como número entero
    ];

    /**
     * Genera el slug automáticamente desde el nombre
     * al crear una categoría nueva.
     * Ejemplo: "Polos ESO" → "polos-eso"
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Relación: una categoría tiene muchos productos.
     * Permite hacer: $category->products
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}