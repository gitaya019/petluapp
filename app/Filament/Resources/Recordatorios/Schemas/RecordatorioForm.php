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

                        // =========================
                        // 🐶 MASCOTA (PRO SELECT)
                        // =========================
                        Select::make('mascota_id')
                            ->relationship('mascota', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Selecciona una mascota')
                            ->loadingMessage('Cargando mascotas...')
                            ->noSearchResultsMessage('No se encontraron mascotas')
                            ->noOptionsMessage('No hay mascotas disponibles')
                            ->searchingMessage('Buscando mascotas...')
                            ->searchDebounce(500)
                            ->searchPrompt('Buscar por nombre...'),

                        // =========================
                        // 💉 VACUNA (IGUAL DE PRO)
                        // =========================
                        Select::make('vacuna_id')
                            ->relationship('vacuna', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('Selecciona una vacuna')
                            ->loadingMessage('Cargando vacunas...')
                            ->noSearchResultsMessage('No se encontraron vacunas')
                            ->noOptionsMessage('No hay vacunas disponibles')
                            ->searchingMessage('Buscando vacunas...')
                            ->searchDebounce(500)
                            ->searchPrompt('Buscar por nombre de vacuna...'),

                        // =========================
                        // 🧠 TIPO
                        // =========================
                        TextInput::make('tipo')
                            ->required()
                            ->maxLength(100),

                        // =========================
                        // 📝 MENSAJE
                        // =========================
                        TextInput::make('mensaje')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),

                        // =========================
                        // 📅 FECHA
                        // =========================
                        DatePicker::make('fecha_programada')
                            ->required(),

                        // =========================
                        // 📊 ESTADO
                        // =========================
                        Select::make('estado')
                            ->placeholder('Selecciona una estado')
                            ->loadingMessage('Cargando estados...')
                            ->noSearchResultsMessage('No se encontraron estados')
                            ->noOptionsMessage('No hay estados disponibles')
                            ->searchingMessage('Buscando estados...')
                            ->searchDebounce(500)
                            ->searchPrompt('Buscar por nombre de estado...')
                            ->options([
                                'pendiente' => 'Pendiente',
                                'enviado' => 'Enviado',
                            ])
                            ->default('pendiente')
                            ->required(),

                    ])
                    ->columns(2),
            ]);
    }
}