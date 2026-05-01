<?php

namespace App\Filament\Admin\Resources\Categories\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Imagen miniatura de la categoría
                ImageColumn::make('image')
                    ->label('Imagen')
                    ->circular(),

                // Nombre con búsqueda y ordenamiento
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                // Slug de la categoría
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),

                // Orden de aparición en el menú
                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable(),

                // Ícono verde/rojo si está activa
                IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean(),

                // Fecha de creación
                TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filtro para ver categorías eliminadas
                TrashedFilter::make(),
            ])
            ->recordActions([
                // Ver detalle de la categoría
                ViewAction::make(),

                // Editar la categoría
                EditAction::make(),
            ])
            ->toolbarActions([
                // Eliminar suave varias categorías
                DeleteBulkAction::make(),

                // Eliminar permanentemente
                ForceDeleteBulkAction::make(),

                // Restaurar categorías eliminadas
                RestoreBulkAction::make(),
            ]);
    }
}