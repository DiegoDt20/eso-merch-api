<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Lista todas las categorías activas.
     * Incluye el conteo de productos por categoría.
     * Usado en el menú de navegación del frontend.
     */
    public function index()
    {
        $categories = Category::where('is_active', true)
            // Cuenta cuántos productos tiene cada categoría
            ->withCount('products')
            // Ordena por el campo sort_order definido en el admin
            ->orderBy('sort_order')
            ->get();

        return response()->json($categories);
    }

    /**
     * Muestra el detalle de una categoría por su slug
     * e incluye sus productos activos paginados.
     * Ejemplo: /api/categories/polos
     */
    public function show(string $slug)
    {
        // Busca la categoría por slug
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Carga los productos activos de esa categoría
        $products = $category->products()
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return response()->json([
            'category' => $category,
            'products' => $products,
        ]);
    }
}