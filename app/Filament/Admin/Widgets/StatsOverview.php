<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    /**
     * Define las tarjetas de estadísticas del dashboard.
     * Se actualizan automáticamente cada vez que se carga el panel.
     */
    protected function getStats(): array
    {
        // Total de ingresos sumando todos los pedidos entregados
        $totalIngresos = Order::where('status', 'delivered')
            ->sum('total');

        // Pedidos pendientes de atender
        $pedidosPendientes = Order::where('status', 'pending')->count();

        // Productos con stock bajo (5 o menos unidades)
        $stockBajo = Product::where('stock', '<=', 5)
            ->where('is_active', true)
            ->count();

        return [
            // Total de productos activos en la tienda
            Stat::make('Productos activos', Product::where('is_active', true)->count())
                ->description('Productos en la tienda')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('success'),

            // Total de clientes registrados
            Stat::make('Clientes', Customer::count())
                ->description('Clientes registrados')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            // Total de pedidos realizados
            Stat::make('Pedidos totales', Order::count())
                ->description("{$pedidosPendientes} pendientes")
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning'),

            // Ingresos totales de pedidos entregados
            Stat::make('Ingresos', 'S/. ' . number_format($totalIngresos, 2))
                ->description('De pedidos entregados')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            // Pedidos en proceso actualmente
            Stat::make('En proceso', Order::where('status', 'processing')->count())
                ->description('Pedidos en preparación')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),

            // Alerta de productos con stock bajo
            Stat::make('Stock bajo', $stockBajo)
                ->description('Productos con ≤5 unidades')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($stockBajo > 0 ? 'danger' : 'success'),
        ];
    }
}