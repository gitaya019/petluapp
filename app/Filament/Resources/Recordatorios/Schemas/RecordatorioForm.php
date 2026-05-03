<?php

namespace App\Filament\Resources\Recordatorios\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;

class RecordatorioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Recordatorio')
                    ->icon('heroicon-o-bell')
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

                        TextInput::make('tipo'),

                        TextInput::make('mensaje'),

                        DatePicker::make('fecha_programada'),

                        Select::make('estado')
                            ->options([
                                'pendiente' => 'Pendiente',
                                'enviado' => 'Enviado',
                            ])
                            ->default('pendiente'),
                    ])->columns(2)
            ]);
    }
}
