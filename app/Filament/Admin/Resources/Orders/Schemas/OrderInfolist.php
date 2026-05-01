<?php

namespace App\Filament\Admin\Resources\Orders\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // Sección información principal — solo lectura
            Section::make('Información de la orden')
                ->schema([
                    // Número de orden
                    TextEntry::make('order_number')
                        ->label('Número de orden')
                        ->badge()
                        ->color('info'),

                    // Nombre del cliente
                    TextEntry::make('customer.name')
                        ->label('Cliente'),

                    // Estado con color según valor
                    TextEntry::make('status')
                        ->label('Estado')
                        ->badge()
                        ->color(fn ($state) => match($state) {
                            'pending'    => 'warning',
                            'processing' => 'info',
                            'shipped'    => 'primary',
                            'delivered'  => 'success',
                            'cancelled'  => 'danger',
                        }),

                    // Método de pago
                    TextEntry::make('payment_method')
                        ->label('Método de pago')
                        ->placeholder('No especificado'),
                ])->columns(2),

            // Sección montos — solo lectura
            Section::make('Montos')
                ->schema([
                    // Subtotal
                    TextEntry::make('subtotal')
                        ->label('Subtotal')
                        ->money('PEN'),

                    // Costo de envío
                    TextEntry::make('shipping')
                        ->label('Envío')
                        ->money('PEN'),

                    // Descuento
                    TextEntry::make('discount')
                        ->label('Descuento')
                        ->money('PEN'),

                    // Total final en grande
                    TextEntry::make('total')
                        ->label('Total')
                        ->money('PEN')
                        ->weight('bold'),
                ])->columns(2),

            // Sección envío y notas — solo lectura
            Section::make('Envío y notas')
                ->schema([
                    // Dirección de envío
                    TextEntry::make('shipping_address')
                        ->label('Dirección de envío')
                        ->placeholder('Sin dirección')
                        ->columnSpanFull(),

                    // Notas adicionales
                    TextEntry::make('notes')
                        ->label('Notas')
                        ->placeholder('Sin notas')
                        ->columnSpanFull(),

                    // Si el pago fue confirmado
                    IconEntry::make('is_paid')
                        ->label('Pago confirmado')
                        ->boolean(),
                ]),
        ]);
    }
}