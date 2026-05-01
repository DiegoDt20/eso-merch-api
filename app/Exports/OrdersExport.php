<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * Obtiene todos los pedidos con su cliente para exportar.
     */
    public function collection()
    {
        return Order::with('customer')->latest()->get();
    }

    /**
     * Define los encabezados de las columnas del Excel.
     */
    public function headings(): array
    {
        return [
            'Número de Orden',
            'Cliente',
            'Email',
            'Teléfono',
            'Estado',
            'Método de Pago',
            'Subtotal (S/.)',
            'Envío (S/.)',
            'Descuento (S/.)',
            'Total (S/.)',
            'Pagado',
            'Dirección de Envío',
            'Fecha de Creación',
        ];
    }

    /**
     * Mapea cada fila de datos para el Excel.
     * Convierte los valores al formato correcto.
     */
    public function map($order): array
    {
        return [
            $order->order_number,
            $order->customer?->name ?? 'Sin cliente',
            $order->customer?->email ?? '-',
            $order->customer?->phone ?? '-',
            match($order->status) {
                'pending'    => 'Pendiente',
                'processing' => 'En proceso',
                'shipped'    => 'Enviado',
                'delivered'  => 'Entregado',
                'cancelled'  => 'Cancelado',
                default      => $order->status,
            },
            ucfirst($order->payment_method ?? '-'),
            number_format($order->subtotal, 2),
            number_format($order->shipping, 2),
            number_format($order->discount, 2),
            number_format($order->total, 2),
            $order->is_paid ? 'Sí' : 'No',
            $order->shipping_address ?? '-',
            $order->created_at->format('d/m/Y H:i'),
        ];
    }

    /**
     * Aplica estilos visuales al Excel.
     * Pone los encabezados en negrita y con fondo oscuro.
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            // Fila 1 (encabezados) en negrita con fondo oscuro
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '1a1a2e']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}