<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;

    /**
     * SoftDeletes permite eliminar productos de forma segura.
     * En lugar de borrarlos de la BD, los marca como eliminados
     * con una fecha en la columna 'deleted_at'.
     * Se pueden recuperar en cualquier momento.
     */
    use SoftDeletes;

    /**
     * Campos que se pueden llenar masivamente.
     * Solo los campos listados aquí pueden ser asignados
     * al crear o editar un producto desde el panel admin.
     */
   protected $fillable = [
    'name',        // Nombre del producto
    'slug',        // URL amigable
    'description', // Descripción detallada
    'price',       // Precio normal
    'sale_price',  // Precio de oferta
    'stock',       // Cantidad en inventario
    'sku',         // Código único de inventario
    'image',       // Imagen principal
    'images',      // Galería de imágenes (JSON)
    'category_id', // ID de la categoría relacionada
    'is_active',   // Visible en tienda
    'is_featured', // Producto destacado
];

    /**
     * Convierte los campos al tipo de dato correcto
     * al leerlos desde la base de datos.
     */
    protected $casts = [
        'price'       => 'decimal:2', // Precio con 2 decimales
        'sale_price'  => 'decimal:2', // Precio oferta con 2 decimales
        'stock'       => 'integer',   // Stock como número entero
        'images'      => 'array',     // Galería como array PHP
        'is_active'   => 'boolean',   // Activo como true/false
        'is_featured' => 'boolean',   // Destacado como true/false
    ];

    /**
     * Se ejecuta automáticamente al crear un producto.
     * Genera el slug desde el nombre si no se proporcionó uno.
     * Ejemplo: "Polo ESO 2024" → "polo-eso-2024"
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
    /**
 * Relación: un producto pertenece a una categoría.
 * Permite hacer: $product->category->name
 */
public function category()
{
    return $this->belongsTo(Category::class);
}
}