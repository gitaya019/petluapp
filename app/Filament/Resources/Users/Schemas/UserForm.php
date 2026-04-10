<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('name')
                ->required(),

            TextInput::make('email')
                ->email()
                ->required(),

            TextInput::make('password')
                ->password()
                ->required()
                ->dehydrated(fn($state) => filled($state)),

            TextInput::make('numero_documento'),

            TextInput::make('telefono'),

            Toggle::make('estado')
                ->default(true),
            //pruebas recordar
            Select::make('clinica_id')
                ->relationship('clinica', 'nombre')
                ->required(),
        ]);
    }
}
