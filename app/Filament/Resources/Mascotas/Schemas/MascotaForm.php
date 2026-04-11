<?php

namespace App\Filament\Resources\Mascotas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Facades\Filament;

class MascotaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('nombre')
                ->required(),

            TextInput::make('especie')
                ->required(),

            TextInput::make('raza'),

            Select::make('user_id')
                ->label('Propietario')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('color'),

            TextInput::make('peso')
                ->numeric(),

            Toggle::make('estado')
                ->default(true),
        ]);
    }
}
