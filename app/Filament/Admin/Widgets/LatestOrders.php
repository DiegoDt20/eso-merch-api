<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    /**
     * Título del widget en el dashboard.
     */
    protected static ?string $heading = 'Últimos pedidos';

    /**
     * Ocupa todo el ancho del dashboard.
     */
    protected int | string | array $columnSpan = 'full';

    /**
     * Define la tabla de últimos pedidos.
     * Muestra los 5 pedidos más recientes.
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Obtiene los últimos 5 pedidos con su cliente
                Order::query()
                    ->with('customer')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                // Número de orden como badge azul
                TextColumn::make('order_number')
                    ->label('Orden')
                    ->badge()
                    ->color('info'),

                // Nombre del cliente
                TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->searchable(),

                // Estado con color según valor
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'pending'    => 'warning',
                        'processing' => 'info',
                        'shipped'    => 'primary',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending'    => 'Pendiente',
                        'processing' => 'En proceso',
                        'shipped'    => 'Enviado',
                        'delivered'  => 'Entregado',
                        'cancelled'  => 'Cancelado',
                        default      => $state,
                    }),

                // Total del pedido en soles
                TextColumn::make('total')
                    ->label('Total')
                    ->money('PEN'),

                // Método de pago
                TextColumn::make('payment_method')
                    ->label('Pago')
                    ->placeholder('No especificado'),

                // Fecha del pedido
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ]);
    }
}