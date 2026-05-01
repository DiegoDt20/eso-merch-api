<?php

namespace App\Filament\Admin\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // Sección información principal de la categoría
            Section::make('Información de la categoría')
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

                    // Descripción de la categoría
                    Textarea::make('description')
                        ->label('Descripción')
                        ->columnSpanFull(),

                    // Orden de aparición en el menú
                    TextInput::make('sort_order')
                        ->label('Orden')
                        ->numeric()
                        ->default(0),
                ])->columns(2),

            // Sección imagen de la categoría
            Section::make('Imagen')
                ->schema([
                    // Imagen representativa de la categoría
                    FileUpload::make('image')
                        ->label('Imagen de categoría')
                        ->image()
                        ->directory('categories'),
                ]),

            // Sección visibilidad
            Section::make('Opciones')
                ->schema([
                    // Activa o desactiva la categoría en la tienda
                    Toggle::make('is_active')
                        ->label('Activa en tienda')
                        ->default(true),
                ]),
        ]);
    }
}