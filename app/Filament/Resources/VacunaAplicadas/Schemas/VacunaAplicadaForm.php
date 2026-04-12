<?php

namespace App\Filament\Resources\VacunaAplicadas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;

class VacunaAplicadaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Aplicación de Vacuna')
                    ->icon('heroicon-o-check-badge')
                    ->schema([
                        Select::make('mascota_id')
                            ->relationship('mascota', 'nombre')
                            ->searchable()
                            ->required(),

                        Select::make('vacuna_id')
                            ->relationship('vacuna', 'nombre')
                            ->required(),

                        Select::make('lote_id')
                            ->relationship('lote', 'numero_lote')
                            ->required(),

                        Select::make('veterinario_id')
                            ->relationship('veterinario', 'name')
                            ->required(),

                        DatePicker::make('fecha_aplicacion')
                            ->required(),

                        Textarea::make('observaciones')
                            ->rows(3),
                    ])->columns(2)
            ]);
    }
}
