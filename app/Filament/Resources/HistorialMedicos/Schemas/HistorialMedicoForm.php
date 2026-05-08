<?php

namespace App\Filament\Resources\HistorialMedicos\Schemas;

use App\Models\User;
use Filament\Schemas\Schema;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

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

                        // 🪪 DOCUMENTO CLIENTE
                        TextInput::make('cliente_documento')
                            ->label('Documento del cliente')
                            ->placeholder('Ingresa el número de documento')
                            ->live(debounce: 500)
                            ->dehydrated(false)

                            ->afterStateUpdated(function ($state, callable $set) {

                                $cliente = User::query()
                                    ->where('numero_documento', $state)
                                    ->first();

                                // limpiar mascota si cambia
                                $set('mascota_id', null);

                                if ($cliente) {
                                    $set('cliente_id_temp', $cliente->id);
                                } else {
                                    $set('cliente_id_temp', null);
                                }
                            })

                            ->required(),

                        // 🔒 ID TEMPORAL CLIENTE
                        Hidden::make('cliente_id_temp')
                            ->dehydrated(false),

                        // 🐶 MASCOTAS DEL CLIENTE
                        Select::make('mascota_id')
                            ->label('Mascota')

                            ->options(function (callable $get) {

                                $clienteId = $get('cliente_id_temp');

                                if (! $clienteId) {
                                    return [];
                                }

                                return \App\Models\Mascota::query()
                                    ->where('user_id', $clienteId)
                                    ->pluck('nombre', 'id');
                            })

                            ->searchable()

                            ->placeholder('Selecciona una mascota')
                            ->loadingMessage('Cargando mascotas...')
                            ->noSearchResultsMessage('No se encontraron mascotas')
                            ->noOptionsMessage('Este cliente no tiene mascotas')
                            ->searchingMessage('Buscando mascotas...')
                            ->searchDebounce(500)
                            ->searchPrompt('Buscar por nombre...')

                            ->required(),

                        // 👨‍⚕️ VETERINARIO
                        Select::make('veterinario_id')
                            ->relationship('veterinario', 'name')

                            ->placeholder('Selecciona un veterinario')
                            ->loadingMessage('Cargando veterinarios...')
                            ->noSearchResultsMessage('No se encontraron veterinarios')
                            ->noOptionsMessage('No hay veterinarios disponibles')
                            ->searchingMessage('Buscando veterinarios...')
                            ->searchDebounce(500)
                            ->searchPrompt('Buscar por nombre...')

                            ->required(),

                        // 📝 MOTIVO
                        Textarea::make('motivo_consulta')
                            ->label('Motivo de consulta')
                            ->columnSpanFull(),

                        // 🩺 DIAGNÓSTICO
                        Textarea::make('diagnostico')
                            ->label('Diagnóstico')
                            ->columnSpanFull(),

                        // 💊 TRATAMIENTO
                        Textarea::make('tratamiento')
                            ->label('Tratamiento')
                            ->columnSpanFull(),

                        // 📌 OBSERVACIONES
                        Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->columnSpanFull(),

                        // 📅 FECHA
                        DatePicker::make('fecha')
                            ->required(),

                    ])

                    ->columns(2),
            ]);
    }
}