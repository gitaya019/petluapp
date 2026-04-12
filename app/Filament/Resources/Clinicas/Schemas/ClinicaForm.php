<?php

namespace App\Filament\Resources\Clinicas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use App\Models\User;


class ClinicaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('nombre')->required(),

            TextInput::make('nit')->required(),

            TextInput::make('direccion'),

            TextInput::make('telefono'),

            TextInput::make('email')->email(),

            Toggle::make('estado')->default(true),

            Select::make('users')
                ->label('Usuarios de la clínica')
                ->multiple()
                ->relationship('users', 'name')
                ->searchable()
                ->preload()

        ]);
    }
}
