<?php

namespace App\Filament\System\Resources\Clinicas\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;


class ClinicaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de la Clínica')
                    ->description('Datos principales de la clínica')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('nit')
                            ->label('NIT')
                            ->maxLength(50),

                        TextInput::make('telefono')
                            ->label('Teléfono')
                            ->tel(),

                        Toggle::make('estado')
                            ->label('Activa')
                            ->default(true),
                    ]),

                Section::make('Usuarios asignados')
                    ->description('Asigna los usuarios que pertenecen a esta clínica')
                    ->schema([
                        Select::make('users')
                            ->label('Usuarios')
                            ->relationship('users', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }
}