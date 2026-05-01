<?php

namespace App\Filament\Admin\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // Sección información principal de la orden
            Section::make('Información de la orden')
                ->schema([
                    // Número de orden — se autogenera pero se puede editar
                    TextInput::make('order_number')
                        ->label('Número de orden')
                        ->disabled()
                        ->placeholder('Se generará automáticamente'),

                    // Cliente que realiza la compra
                    Select::make('customer_id')
                        ->label('Cliente')
                        ->relationship('customer', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    // Estado actual de la orden
                    Select::make('status')
                        ->label('Estado')
                        ->options([
                            'pending'    => 'Pendiente',
                            'processing' => 'En proceso',
                            'shipped'    => 'Enviado',
                            'delivered'  => 'Entregado',
                            'cancelled'  => 'Cancelado',
                        ])
                        ->default('pending')
                        ->required(),

                    // Método de pago usado
                    Select::make('payment_method')
                        ->label('Método de pago')
                        ->options([
                            'efectivo'     => 'Efectivo',
                            'yape'         => 'Yape',
                            'plin'         => 'Plin',
                            'transferencia'=> 'Transferencia',
                            'tarjeta'      => 'Tarjeta',
                        ]),
                ])->columns(2),

            // Sección montos de la orden
            Section::make('Montos')
                ->schema([
                    // Subtotal antes de descuentos
                    TextInput::make('subtotal')
                        ->label('Subtotal (S/.)')
                        ->numeric()
                        ->prefix('S/.')
                        ->default(0),

                    // Costo de envío
                    TextInput::make('shipping')
                        ->label('Envío (S/.)')
                        ->numeric()
                        ->prefix('S/.')
                        ->default(0),

                    // Descuento aplicado
                    TextInput::make('discount')
                        ->label('Descuento (S/.)')
                        ->numeric()
                        ->prefix('S/.')
                        ->default(0),

                    // Total final
                    TextInput::make('total')
                        ->label('Total (S/.)')
                        ->numeric()
                        ->prefix('S/.')
                        ->default(0),
                ])->columns(2),

            // Sección envío y notas
            Section::make('Envío y notas')
                ->schema([
                    // Dirección de envío de esta orden
                    TextInput::make('shipping_address')
                        ->label('Dirección de envío')
                        ->columnSpanFull(),

                    // Notas adicionales
                    Textarea::make('notes')
                        ->label('Notas')
                        ->columnSpanFull(),

                    // Si el pago fue confirmado
                    Toggle::make('is_paid')
                        ->label('Pago confirmado')
                        ->default(false),
                ]),
        ]);
    }
}