<?php

namespace App\Filament\Admin\Resources\Products\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // Sección información principal — solo lectura
            Section::make('Información del producto')
    ->schema([
        // Muestra el nombre del producto
        TextEntry::make('name')
            ->label('Nombre'),

        // Muestra el slug generado
        TextEntry::make('slug')
            ->label('Slug (URL)'),

        // Muestra el nombre de la categoría relacionada
        TextEntry::make('category.name')
            ->label('Categoría')
            ->badge()
            ->placeholder('Sin categoría'),

        // Muestra la descripción completa
        TextEntry::make('description')
            ->label('Descripción')
            ->columnSpanFull(),
    ])->columns(2),

            // Sección precios e inventario — solo lectura
            Section::make('Precios e inventario')
                ->schema([
                    // Precio en soles
                    TextEntry::make('price')
                        ->label('Precio')
                        ->money('PEN'),

                    // Precio oferta
                    TextEntry::make('sale_price')
                        ->label('Precio oferta')
                        ->money('PEN')
                        ->placeholder('Sin oferta'),

                    // Stock con color según cantidad
                    TextEntry::make('stock')
                        ->label('Stock')
                        ->badge()
                        ->color(fn ($state) => $state <= 5 ? 'danger' : 'success'),

                    // Código SKU
                    TextEntry::make('sku')
                        ->label('Código SKU')
                        ->placeholder('Sin SKU'),
                ])->columns(2),

            // Sección imágenes — solo lectura
            Section::make('Imágenes')
                ->schema([
                   // Imagen principal del producto
// Imagen principal del producto
ImageEntry::make('image')
    ->label('Imagen principal')
    ->disk('public')
    ->url(fn ($record) => asset('storage/' . $record->image)),

// Galería de imágenes adicionales
ImageEntry::make('images')
    ->label('Galería')
    ->disk('public')
    ->stacked(),
                ]),

            // Sección estado del producto — solo lectura
            Section::make('Opciones')
                ->schema([
                    // Ícono verde/rojo si está activo
                    IconEntry::make('is_active')
                        ->label('Activo en tienda')
                        ->boolean(),

                    // Ícono si es destacado
                    IconEntry::make('is_featured')
                        ->label('Producto destacado')
                        ->boolean(),
                ])->columns(2),
        ]);
    }
}