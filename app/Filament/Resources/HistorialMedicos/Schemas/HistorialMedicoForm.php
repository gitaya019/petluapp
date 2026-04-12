<?php

namespace App\Filament\Resources\HistorialMedicos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;

class HistorialMedicoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Historial Médico')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Select::make('mascota_id')
                            ->relationship('mascota', 'nombre')
                            ->required(),

                        Select::make('veterinario_id')
                            ->relationship('veterinario', 'name')
                            ->required(),

                        Textarea::make('motivo_consulta'),

                        Textarea::make('diagnostico'),

                        Textarea::make('tratamiento'),

                        Textarea::make('observaciones'),

                        DatePicker::make('fecha')
                            ->required(),
                    ])->columns(2)
            ]);
    }
}
