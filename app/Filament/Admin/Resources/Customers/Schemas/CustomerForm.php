<?php

namespace App\Filament\Admin\Resources\Customers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // Sección datos personales del cliente
            Section::make('Datos personales')
                ->schema([
                    // Nombre completo del cliente
                    TextInput::make('name')
                        ->label('Nombre completo')
                        ->required(),

                    // Correo electrónico único
                    TextInput::make('email')
                        ->label('Correo electrónico')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true),

                    // Número de celular o teléfono
                    TextInput::make('phone')
                        ->label('Teléfono / Celular')
                        ->tel(),
                ])->columns(2),

            // Sección dirección de envío
            Section::make('Dirección de envío')
                ->schema([
                    // Dirección completa
                    TextInput::make('address')
                        ->label('Dirección')
                        ->columnSpanFull(),

                    // Ciudad del cliente
                    TextInput::make('city')
                        ->label('Ciudad'),

                    // Departamento del Perú
                    Select::make('department')
                        ->label('Departamento')
                        ->options([
                            'lima'         => 'Lima',
                            'arequipa'     => 'Arequipa',
                            'cusco'        => 'Cusco',
                            'trujillo'     => 'Trujillo',
                            'piura'        => 'Piura',
                            'chiclayo'     => 'Chiclayo',
                            'iquitos'      => 'Iquitos',
                            'huancayo'     => 'Huancayo',
                            'tacna'        => 'Tacna',
                            'otro'         => 'Otro',
                        ]),
                ])->columns(2),

            // Sección notas y estado
            Section::make('Notas y estado')
                ->schema([
                    // Notas internas sobre el cliente
                    Textarea::make('notes')
                        ->label('Notas internas')
                        ->columnSpanFull(),

                    // Activo o bloqueado
                    Toggle::make('is_active')
                        ->label('Cliente activo')
                        ->default(true),
                ]),
        ]);
    }
}