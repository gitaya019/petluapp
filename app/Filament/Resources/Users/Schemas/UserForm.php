<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Datos del Usuario')
                ->icon('heroicon-o-user')
                ->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('email')->email()->required(),
                    TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn($state) => bcrypt($state))
                        ->required(fn(string $operation) => $operation === 'create'),

                    TextInput::make('numero_documento'),
                    Select::make('tipo_documento')
                        ->options([
                            'CC' => 'Cédula',
                            'TI' => 'Tarjeta',
                        ])
                        ->default('CC'),

                    TextInput::make('telefono'),
                    Toggle::make('estado')->default(true),
                ])->columns(2),
        ]);
    }
}
