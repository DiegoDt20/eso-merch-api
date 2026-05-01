<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

/**
 * Controlador para buscar pedidos por email del cliente.
 * No requiere autenticación — el cliente solo necesita su email.
 * URL: GET /api/orders/by-email?email=cliente@email.com
 */
class OrderByEmailController extends Controller
{
    /**
     * Busca todos los pedidos asociados a un email.
     * Retorna la lista de órdenes con sus productos.
     */
    public function __invoke(Request $request)
    {
        // Valida que el email sea requerido y tenga formato válido
        $request->validate([
            'email' => 'required|email',
        ]);

        // Busca el cliente por su email
        $customer = Customer::where('email', $request->email)->first();

        // Si no existe el cliente retorna lista vacía
        if (!$customer) {
            return response()->json(['orders' => []]);
        }

        // Obtiene todos los pedidos del cliente
        // Incluye los productos con cantidad y precio de la tabla pivot
        $orders = $customer->orders()
            ->with(['products' => function ($query) {
                // Trae cantidad y precio de la tabla order_items
                $query->withPivot(['quantity', 'price']);
            }])
            ->latest()    // Ordena del más reciente al más antiguo
            ->get();

        return response()->json(['orders' => $orders]);
    }
}