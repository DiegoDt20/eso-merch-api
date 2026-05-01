<?php

namespace App\Filament\Admin\Resources\Customers\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Nombre del cliente con búsqueda
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                // Correo electrónico con búsqueda
                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->sortable(),

                // Teléfono del cliente
                TextColumn::make('phone')
                    ->label('Teléfono')
                    ->placeholder('Sin teléfono'),

                // Ciudad del cliente
                TextColumn::make('city')
                    ->label('Ciudad')
                    ->placeholder('Sin ciudad'),

                // Departamento como badge
                TextColumn::make('department')
                    ->label('Departamento')
                    ->badge(),

                // Ícono verde/rojo si está activo
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),

                // Fecha de registro
                TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filtro por departamento
                SelectFilter::make('department')
                    ->label('Departamento')
                    ->options([
                        'lima'     => 'Lima',
                        'arequipa' => 'Arequipa',
                        'cusco'    => 'Cusco',
                        'trujillo' => 'Trujillo',
                        'piura'    => 'Piura',
                        'chiclayo' => 'Chiclayo',
                        'iquitos'  => 'Iquitos',
                        'huancayo' => 'Huancayo',
                        'tacna'    => 'Tacna',
                        'otro'     => 'Otro',
                    ]),
            ])
            ->recordActions([
                // Ver detalle del cliente
                ViewAction::make(),

                // Editar cliente
                EditAction::make(),
            ])
            ->toolbarActions([
                // Eliminar varios clientes a la vez
                DeleteBulkAction::make(),
            ]);
    }
}