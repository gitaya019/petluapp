<?php

namespace App\Filament\Resources\LoteVacunas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;

class LoteVacunaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lote de Vacuna')
                    ->icon('heroicon-o-archive-box')
                    ->schema([
                        Select::make('vacuna_id')
                            ->relationship('vacuna', 'nombre')
                            ->placeholder('Selecciona una vacuna')
                            ->loadingMessage('Cargando vacunas...')
                            ->noSearchResultsMessage('No se encontraron vacunas')
                            ->noOptionsMessage('No hay vacunas disponibles')
                            ->searchingMessage('buscando vacunas...')
                            ->searchDebounce(500)
                            ->searchPrompt('Buscar por nombre...')
                            ->searchable()
                            ->required()
                            ->preload()
                            ->live(),

                        TextInput::make('numero_lote')
                            ->required(),

                        DatePicker::make('fecha_vencimiento'),

                        TextInput::make('stock_inicial')
                            ->numeric()
                            ->required(),

                        TextInput::make('stock_actual')
                            ->numeric()
                            ->required(),
                    ])->columns(2)
            ]);
    }
}
