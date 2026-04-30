<?php

namespace App\Filament\System\Resources\Clinicas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;

class ClinicaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Información de la Clínica')
                ->icon('heroicon-o-building-office')
                ->schema([
                    TextInput::make('nombre')->required()->maxLength(255),
                    TextInput::make('nit')->required(),
                    TextInput::make('direccion'),
                    TextInput::make('telefono'),
                    TextInput::make('email')->email(),
                    Toggle::make('estado')->default(true),
                ])->columns(2),
        ]);
    }
}
