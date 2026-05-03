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
                            ->placeholder('Selecciona una mascota')
                            ->loadingMessage('Cargando mascotas...')
                            ->noSearchResultsMessage('No se encontraron mascotas')
                            ->noOptionsMessage('No hay mascotas disponibles')
                            ->searchingMessage('buscando mascotas...')
                            ->searchDebounce(500)
                            ->searchPrompt('Buscar por nombre...')
                            ->searchable()
                            ->required(),

                        Select::make('vacuna_id')
                            ->relationship('vacuna', 'nombre')
                            ->placeholder('Selecciona una vacuna')
                            ->loadingMessage('Cargando vacunas...')
                            ->noSearchResultsMessage('No se encontraron vacunas')
                            ->noOptionsMessage('No hay vacunas disponibles')
                            ->searchingMessage('buscando vacunas...')
                            ->searchDebounce(500)
                            ->searchPrompt('Buscar por nombre...')
                            ->required(),

                        Select::make('lote_id')
                            ->placeholder('Selecciona un lote')
                            ->loadingMessage('Cargando lotes...')
                            ->noSearchResultsMessage('No se encontraron lotes de vacunas')
                            ->noOptionsMessage('No hay lotes disponibles')
                            ->searchingMessage('buscando lotes...')
                            ->searchDebounce(500)
                            ->searchPrompt('Buscar por numero de lote...')
                            ->relationship(
                                'lote',
                                'numero_lote',
                                fn($query) => $query->where('stock_actual', '>', 0)
                            )
                            ->required()
                            ->searchable()
                            ->preload()
                            ->helperText('Solo se muestran lotes con stock'),

                        DatePicker::make('fecha_aplicacion')
                            ->required(),

                        Textarea::make('observaciones')
                            ->rows(3),
                    ])->columns(2)
            ]);
    }
}
