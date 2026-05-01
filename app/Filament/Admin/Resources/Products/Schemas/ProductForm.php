<?php

namespace App\Filament\Admin\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // Sección datos principales del producto
            Section::make('Información del producto')
                ->schema([
                    // Nombre — genera el slug automáticamente
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) =>
                            $set('slug', Str::slug($state))
                        ),

                    // Slug — URL amigable autogenerada
                    TextInput::make('slug')
                        ->label('Slug (URL)')
                        ->required()
                        ->unique(ignoreRecord: true),

                    // Descripción del producto
                    Textarea::make('description')
                        ->label('Descripción')
                        ->columnSpanFull(),

                    // Categoría cargada desde la base de datos
                    Select::make('category_id')
                        ->label('Categoría')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('name')
                                ->label('Nombre')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, callable $set) =>
                                    $set('slug', Str::slug($state))
                                ),
                            TextInput::make('slug')
                                ->label('Slug')
                                ->required(),
                        ]),
                ])->columns(2),

            // Sección precios e inventario
            Section::make('Precios e inventario')
                ->schema([
                    // Precio normal en soles
                    TextInput::make('price')
                        ->label('Precio (S/.)')
                        ->numeric()
                        ->required()
                        ->prefix('S/.'),

                    // Precio de oferta — opcional
                    TextInput::make('sale_price')
                        ->label('Precio oferta (S/.)')
                        ->numeric()
                        ->prefix('S/.'),

                    // Stock disponible
                    TextInput::make('stock')
                        ->label('Stock')
                        ->numeric()
                        ->default(0),

                    // Código único SKU
                    TextInput::make('sku')
                        ->label('Código SKU')
                        ->unique(ignoreRecord: true),
                ])->columns(2),

            // Sección imágenes
            Section::make('Imágenes')
                ->schema([
                    // Imagen principal del producto
// Imagen principal del producto
FileUpload::make('image')
    ->label('Imagen principal')
    ->image()
    ->disk('public')
    ->directory('products')
    ->visibility('public')
    ->fetchFileInformation(false),

// Galería de imágenes adicionales
FileUpload::make('images')
    ->label('Galería')
    ->image()
    ->multiple()
    ->disk('public')
    ->directory('products/gallery')
    ->visibility('public')
    ->fetchFileInformation(false),
                ]),

            // Sección visibilidad
            Section::make('Opciones')
                ->schema([
                    // Activo en tienda
                    Toggle::make('is_active')
                        ->label('Activo en tienda')
                        ->default(true),

                    // Producto destacado
                    Toggle::make('is_featured')
                        ->label('Producto destacado'),
                ])->columns(2),
        ]);
    }
}