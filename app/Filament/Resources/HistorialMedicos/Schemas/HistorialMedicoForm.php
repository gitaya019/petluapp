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
                            ->placeholder('Selecciona una mascota')
                            ->loadingMessage('Cargando mascotas...')
                            ->noSearchResultsMessage('No se encontraron mascotas')
                            ->noOptionsMessage('No hay mascotas disponibles')
                            ->searchingMessage('buscando mascotas...')
                            ->searchDebounce(500)
                            ->searchPrompt('Buscar por nombre...')
                            ->required(),

                        Select::make('veterinario_id')
                            ->relationship('veterinario', 'name')
                            ->placeholder('Selecciona un veterinario')
                            ->loadingMessage('Cargando veterinarios...')
                            ->noSearchResultsMessage('No se encontraron veterinarios')
                            ->noOptionsMessage('No hay veterinarios disponibles')
                            ->searchingMessage('buscando veterinarios...')
                            ->searchDebounce(500)
                            ->searchPrompt('Buscar por nombre...')
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
