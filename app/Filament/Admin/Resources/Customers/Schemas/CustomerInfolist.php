<?php

namespace App\Filament\Admin\Resources\Customers\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // Sección datos personales — solo lectura
            Section::make('Datos personales')
                ->schema([
                    // Nombre completo
                    TextEntry::make('name')
                        ->label('Nombre completo'),

                    // Correo electrónico
                    TextEntry::make('email')
                        ->label('Correo electrónico'),

                    // Teléfono
                    TextEntry::make('phone')
                        ->label('Teléfono / Celular')
                        ->placeholder('Sin teléfono'),
                ])->columns(2),

            // Sección dirección — solo lectura
            Section::make('Dirección de envío')
                ->schema([
                    // Dirección completa
                    TextEntry::make('address')
                        ->label('Dirección')
                        ->placeholder('Sin dirección')
                        ->columnSpanFull(),

                    // Ciudad
                    TextEntry::make('city')
                        ->label('Ciudad')
                        ->placeholder('Sin ciudad'),

                    // Departamento
                    TextEntry::make('department')
                        ->label('Departamento')
                        ->placeholder('Sin departamento'),
                ])->columns(2),

            // Sección notas y estado — solo lectura
            Section::make('Notas y estado')
                ->schema([
                    // Notas internas
                    TextEntry::make('notes')
                        ->label('Notas internas')
                        ->placeholder('Sin notas')
                        ->columnSpanFull(),

                    // Ícono verde/rojo si está activo
                    IconEntry::make('is_active')
                        ->label('Cliente activo')
                        ->boolean(),
                ]),
        ]);
    }
}