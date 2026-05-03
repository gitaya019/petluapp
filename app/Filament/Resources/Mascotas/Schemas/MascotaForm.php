<?php

namespace App\Filament\Resources\Mascotas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;


class MascotaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Mascota')
                ->icon('heroicon-o-heart')
                ->schema([
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->placeholder('Selecciona un dueño')
                        ->searchable()
                        ->required(),

                    TextInput::make('nombre')->required(),
                    TextInput::make('especie')->required(),
                    TextInput::make('raza'),

                    DatePicker::make('fecha_nacimiento'),
                    TextInput::make('sexo'),
                    TextInput::make('peso')->numeric(),
                    TextInput::make('color'),

                    Toggle::make('estado')->default(true),
                ])->columns(2)
        ]);
    }
}
