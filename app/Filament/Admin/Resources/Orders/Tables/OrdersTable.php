<?php

namespace App\Filament\Admin\Resources\Orders\Tables;

use App\Exports\OrdersExport;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25) // Muestra 25 pedidos por página por defecto
            ->columns([
                // Número de orden como badge
                TextColumn::make('order_number')
                    ->label('Orden')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),

                // Nombre del cliente
                TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

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

                // Método de pago
                TextColumn::make('payment_method')
                    ->label('Pago')
                    ->placeholder('No especificado'),

                // Total de la orden
                TextColumn::make('total')
                    ->label('Total')
                    ->money('PEN')
                    ->sortable(),

                // Si el pago fue confirmado
                IconColumn::make('is_paid')
                    ->label('Pagado')
                    ->boolean(),

                // Fecha de creación
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                // Filtro por estado de la orden
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending'    => 'Pendiente',
                        'processing' => 'En proceso',
                        'shipped'    => 'Enviado',
                        'delivered'  => 'Entregado',
                        'cancelled'  => 'Cancelado',
                    ]),

                // Filtro por método de pago
                SelectFilter::make('payment_method')
                    ->label('Método de pago')
                    ->options([
                        'efectivo'      => 'Efectivo',
                        'yape'          => 'Yape',
                        'plin'          => 'Plin',
                        'transferencia' => 'Transferencia',
                        'tarjeta'       => 'Tarjeta',
                    ]),
            ])
            ->headerActions([
                // Botón para exportar todos los pedidos a Excel
                Action::make('exportar')
                    ->label('Exportar Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        // Descarga el archivo Excel con todos los pedidos
                        return Excel::download(
                            new OrdersExport(),
                            'pedidos-' . now()->format('Y-m-d') . '.xlsx'
                        );
                    }),
            ])
            ->recordActions([
                // Ver detalle de la orden
                ViewAction::make(),

                // Editar la orden
                EditAction::make(),
            ])
            ->toolbarActions([
                // Eliminar varias órdenes a la vez
                DeleteBulkAction::make(),
            ]);
    }
}