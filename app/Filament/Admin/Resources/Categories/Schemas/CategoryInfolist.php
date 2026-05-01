<?php

namespace App\Filament\Admin\Resources\Categories\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // Sección información principal — solo lectura
            Section::make('Información de la categoría')
                ->schema([
                    // Nombre de la categoría
                    TextEntry::make('name')
                        ->label('Nombre'),

                    // Slug generado
                    TextEntry::make('slug')
                        ->label('Slug (URL)'),

                    // Orden de aparición
                    TextEntry::make('sort_order')
                        ->label('Orden'),

                    // Descripción completa
                    TextEntry::make('description')
                        ->label('Descripción')
                        ->columnSpanFull(),
                ])->columns(2),

            // Sección imagen — solo lectura
            Section::make('Imagen')
                ->schema([
                    // Imagen representativa de la categoría
                    ImageEntry::make('image')
                        ->label('Imagen'),
                ]),

            // Sección estado — solo lectura
            Section::make('Opciones')
                ->schema([
                    // Ícono verde/rojo según si está activa
                    IconEntry::make('is_active')
                        ->label('Activa en tienda')
                        ->boolean(),
                ]),
        ]);
    }
}