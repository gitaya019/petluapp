<?php

namespace App\Filament\Resources\Vacunas\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VacunaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de la Vacuna')
                    ->icon('heroicon-o-beaker')
                    ->schema([
                        TextInput::make('nombre')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('descripcion')
                            ->rows(3),

                        TextInput::make('dosis'),

                        TextInput::make('dias_refuerzo')
                            ->numeric()
                            ->label('Días para refuerzo'),

                        TextInput::make('fabricante'),

                        TextInput::make('precio_dosis'),

                        Toggle::make('estado')
                            ->default(true),
                    ])->columns(2),
            ]);
    }
}
