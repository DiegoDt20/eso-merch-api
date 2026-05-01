<?php

namespace App\Filament\Admin\Resources\Products\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Imagen miniatura circular del producto
                ImageColumn::make('image')
                    ->label('Imagen')
                    ->circular(),

                // Nombre con búsqueda y ordenamiento
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                // Categoría como badge de color
                // Muestra el nombre de la categoría relacionada
TextColumn::make('category.name')
    ->label('Categoría')
    ->badge(),

                // Precio en soles con ordenamiento
                TextColumn::make('price')
                    ->label('Precio')
                    ->money('PEN')
                    ->sortable(),

                // Stock — rojo si es 5 o menos, verde si hay suficiente
                TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state <= 5 ? 'danger' : 'success'),

                // Ícono verde/rojo si el producto está activo en tienda
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),

                // Ícono si el producto está marcado como destacado
                IconColumn::make('is_featured')
                    ->label('Destacado')
                    ->boolean(),

                // Fecha de creación formateada
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filtro por categoría de producto
                SelectFilter::make('category')
                    ->label('Categoría')
                    ->options([
                        'polos'    => 'Polos',
                        'gorras'   => 'Gorras',
                        'stickers' => 'Stickers',
                        'mochilas' => 'Mochilas',
                        'otros'    => 'Otros',
                    ]),

                // Filtro para ver productos eliminados (softdelete)
                TrashedFilter::make(),
            ])
            ->recordActions([
                // Botón para ver detalle del producto
                ViewAction::make(),

                // Botón para editar el producto
                EditAction::make(),
            ])
            ->toolbarActions([
                // Eliminar suave (softdelete)
                DeleteBulkAction::make(),

                // Eliminar permanentemente
                ForceDeleteBulkAction::make(),

                // Restaurar productos eliminados
                RestoreBulkAction::make(),
            ]);
    }
}