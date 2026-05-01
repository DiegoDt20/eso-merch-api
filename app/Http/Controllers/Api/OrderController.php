<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmationMail;         // Mailable para el email de confirmación
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;                        // Para obtener el admin y enviarle la notificación
use App\Notifications\NewOrderNotification; // Notificación de nuevo pedido para Filament
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;        // Facade para enviar emails

class OrderController extends Controller
{
    /**
     * Lista todas las órdenes del usuario autenticado.
     */
    public function index(Request $request)
    {
        $customer = Customer::where('email', $request->user()->email)->first();

        if (!$customer) {
            return response()->json(['message' => 'No tienes órdenes registradas.', 'orders' => []]);
        }

        $orders = Order::where('customer_id', $customer->id)
            ->with(['customer', 'products'])
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    /**
     * Crea una nueva orden de compra y descuenta el stock.
     * Al finalizar envía email al cliente y notificación al admin.
     */
    public function store(Request $request)
    {
        // Valida los datos recibidos del frontend
        $request->validate([
            'name'                => 'required|string',
            'email'               => 'required|email',
            'phone'               => 'nullable|string',
            'address'             => 'required|string',
            'city'                => 'required|string',
            'department'          => 'required|string',
            'payment_method'      => 'required|string',
            'products'            => 'required|array|min:1',
            'products.*.id'       => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        // Verifica que haya stock suficiente antes de crear la orden
        foreach ($request->products as $item) {
            $product = Product::findOrFail($item['id']);

            // Si no hay suficiente stock retorna error 422
            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'message' => "Stock insuficiente para el producto: {$product->name}. Solo quedan {$product->stock} unidades.",
                ], 422);
            }
        }

        // Busca o crea el cliente con los datos recibidos
        // updateOrCreate actualiza los datos si el cliente ya existe
        $customer = Customer::updateOrCreate(
            ['email' => $request->email],  // busca por email
            [
                'name'       => $request->name,       // actualiza nombre
                'phone'      => $request->phone,      // actualiza teléfono
                'address'    => $request->address,    // actualiza dirección
                'city'       => $request->city,       // actualiza ciudad
                'department' => $request->department, // actualiza departamento
            ]
        );

        // Calcula el subtotal sumando precio x cantidad
        $subtotal      = 0;
        $orderProducts = [];

        foreach ($request->products as $item) {
            $product  = Product::findOrFail($item['id']);
            $price    = $product->sale_price ?? $product->price;
            $subtotal += $price * $item['quantity'];

            // Prepara los datos para la tabla pivot order_items
            $orderProducts[$item['id']] = [
                'quantity' => $item['quantity'],
                'price'    => $price,
            ];
        }

        // Costo de envío fijo
        $shipping = 10.00;
        $total    = $subtotal + $shipping;

        // Crea la orden en la base de datos
        $order = Order::create([
            'customer_id'      => $customer->id,
            'status'           => 'pending',
            'subtotal'         => $subtotal,
            'shipping'         => $shipping,
            'discount'         => 0,
            'total'            => $total,
            'payment_method'   => $request->payment_method,
            'shipping_address' => $request->address . ', ' . $request->city,
            'is_paid'          => false,
        ]);

        // Asocia los productos a la orden con cantidad y precio
        $order->products()->attach($orderProducts);

        // Descuenta el stock de cada producto vendido
        foreach ($request->products as $item) {
            Product::where('id', $item['id'])
                ->decrement('stock', $item['quantity']);
        }

        // Carga las relaciones necesarias para el email y la respuesta
        $order->load(['customer', 'products']);

        // Envía el email de confirmación al cliente
        // Contiene: número de orden, productos, precios y dirección de envío
        Mail::to($request->email)->send(new OrderConfirmationMail($order));

        // Envía notificación a todos los admins en Filament
        // Aparece en el ícono de campana del panel de administración
        User::all()->each(function ($admin) use ($order) {
            $admin->notify(new NewOrderNotification($order));
        });

        return response()->json([
            'message' => 'Orden creada correctamente.',
            'order'   => $order,
        ], 201);
    }

    /**
     * Muestra el detalle de una orden específica.
     */
    public function show(Request $request, int $id)
    {
        $customer = Customer::where('email', $request->user()->email)->firstOrFail();

        $order = Order::where('id', $id)
            ->where('customer_id', $customer->id)
            ->with(['customer', 'products'])
            ->firstOrFail();

        return response()->json($order);
    }
}