<?php

namespace App\Filament\Resources\VacunaAplicadas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use App\Models\LoteVacuna;

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
                            ->searchingMessage('Buscando vacunas...')
                            ->searchDebounce(500)
                            ->searchPrompt('Buscar por nombre...')
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {

                                $lote = LoteVacuna::query()
                                    ->where('vacuna_id', $state)
                                    ->where('stock_actual', '>', 0)
                                    ->first();

                                $set('lote_id', $lote?->id);
                            }),
                        Select::make('lote_id')
                            ->relationship(
                                'lote',
                                'numero_lote',
                                fn($query, Get $get) =>
                                $query
                                    ->where('vacuna_id', $get('vacuna_id'))
                                    ->where('stock_actual', '>', 0)
                            )
                            ->label('Lote asignado')
                            ->disabled()
                            ->dehydrated()
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('El lote se asigna automáticamente según la vacuna seleccionada'),

                        DatePicker::make('fecha_aplicacion')
                            ->required(),

                        Textarea::make('observaciones')
                            ->rows(3),
                    ])->columns(2)
            ]);
    }
}
