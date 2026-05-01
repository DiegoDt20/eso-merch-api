<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Lista todos los productos activos.
     * Permite filtrar por categoría y buscar por nombre.
     * Usado en el showroom y catálogo del frontend.
     */
    public function index(Request $request)
    {
        $query = Product::with('category')
            ->where('is_active', true);

        // Filtro por categoría si se envía en la URL
        // Ejemplo: /api/products?category=polos
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Búsqueda por nombre del producto
        // Ejemplo: /api/products?search=polo
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Solo productos destacados
        // Ejemplo: /api/products?featured=true
        if ($request->has('featured')) {
            $query->where('is_featured', true);
        }

        // Ordenar por precio
        // Ejemplo: /api/products?sort=price_asc
        if ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            // Por defecto ordena por más reciente
            $query->latest();
        }

        // Pagina los resultados de 12 en 12
        $products = $query->paginate(12);

        return response()->json($products);
    }

    /**
     * Muestra el detalle de un producto por su slug.
     * Usado en la página de detalle del producto.
     * Ejemplo: /api/products/polo-eso-2024
     */
    public function show(string $slug)
    {
        // Busca el producto por slug con su categoría
        $product = Product::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json($product);
    }
}