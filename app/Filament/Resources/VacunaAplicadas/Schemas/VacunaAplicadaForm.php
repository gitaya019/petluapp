<?php

namespace App\Filament\Resources\VacunaAplicadas\Schemas;

use App\Models\User;
use App\Models\Mascota;
use App\Models\LoteVacuna;

use Filament\Schemas\Schema;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;

use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class VacunaAplicadaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Aplicación de Vacuna')
                    ->icon('heroicon-o-check-badge')

                    ->description(
                        'Busca el propietario mediante su documento y selecciona una de sus mascotas.'
                    )

                    ->schema([

                        // =========================
                        // 🪪 DOCUMENTO CLIENTE
                        // =========================
                        TextInput::make('cliente_documento')
                            ->label('Documento del propietario')

                            ->placeholder(
                                'Ingresa el número de documento'
                            )

                            ->live(debounce: 600)

                            ->helperText(
                                'Busca automáticamente las mascotas asociadas al cliente.'
                            )

                            ->dehydrated(false)

                            ->afterStateUpdated(function ($state, Set $set) {

                                $cliente = User::query()
                                    ->where('numero_documento', $state)
                                    ->first();

                                // limpiar mascota al cambiar documento
                                $set('mascota_id', null);

                                if ($cliente) {

                                    $set('cliente_id_temp', $cliente->id);

                                    $set(
                                        'cliente_nombre',
                                        $cliente->name
                                    );

                                    $set(
                                        'cliente_estado',
                                        '✅ Cliente encontrado correctamente'
                                    );

                                    return;
                                }

                                // ❌ CLIENTE NO ENCONTRADO
                                $set('cliente_id_temp', null);

                                $set('cliente_nombre', null);

                                $set(
                                    'cliente_estado',
                                    '❌ No existe un cliente con ese documento'
                                );
                            })

                            ->required(),

                        // =========================
                        // 🔒 CLIENTE TEMP
                        // =========================
                        Hidden::make('cliente_id_temp')
                            ->dehydrated(false),

                        // =========================
                        // 👤 CLIENTE
                        // =========================
                        TextInput::make('cliente_nombre')
                            ->label('Cliente encontrado')

                            ->readOnly()

                            ->dehydrated(false)

                            ->placeholder(
                                'Aquí aparecerá el nombre del propietario'
                            ),

                        // =========================
                        // 📢 ESTADO
                        // =========================
                        Placeholder::make('estado_cliente')
                            ->label('Estado de búsqueda')

                            ->content(function (Get $get) {

                                return $get('cliente_estado')
                                    ?: 'Esperando búsqueda...';
                            }),

                        // =========================
                        // 🐶 MASCOTA
                        // =========================
                        Select::make('mascota_id')
                            ->label('Mascota')

                            ->options(function (Get $get) {

                                $clienteId = $get('cliente_id_temp');

                                if (! $clienteId) {
                                    return [];
                                }

                                return Mascota::query()
                                    ->where('user_id', $clienteId)
                                    ->pluck('nombre', 'id');
                            })

                            ->placeholder('Selecciona una mascota')

                            ->loadingMessage('Cargando mascotas...')

                            ->noSearchResultsMessage(
                                'No se encontraron mascotas'
                            )

                            ->noOptionsMessage(
                                'Este cliente no tiene mascotas registradas'
                            )

                            ->searchingMessage('Buscando mascotas...')

                            ->searchDebounce(500)

                            ->searchPrompt('Buscar por nombre...')

                            ->searchable()

                            ->preload()

                            ->required(),

                        // =========================
                        // 💉 VACUNA
                        // =========================
                        Select::make('vacuna_id')
                            ->relationship('vacuna', 'nombre')

                            ->placeholder('Selecciona una vacuna')

                            ->loadingMessage('Cargando vacunas...')

                            ->noSearchResultsMessage(
                                'No se encontraron vacunas'
                            )

                            ->noOptionsMessage(
                                'No hay vacunas disponibles'
                            )

                            ->searchingMessage('Buscando vacunas...')

                            ->searchDebounce(500)

                            ->searchPrompt('Buscar por nombre...')

                            ->searchable()

                            ->preload()

                            ->required()

                            ->live()

                            ->afterStateUpdated(function (
                                $state,
                                Set $set
                            ) {

                                $lote = LoteVacuna::query()
                                    ->where('vacuna_id', $state)
                                    ->where('stock_actual', '>', 0)
                                    ->first();

                                $set('lote_id', $lote?->id);
                            }),

                        // =========================
                        // 📦 LOTE
                        // =========================
                        Select::make('lote_id')
                            ->relationship(
                                'lote',
                                'numero_lote',

                                fn ($query, Get $get) =>

                                $query
                                    ->where(
                                        'vacuna_id',
                                        $get('vacuna_id')
                                    )
                                    ->where('stock_actual', '>', 0)
                            )

                            ->label('Lote asignado')

                            ->disabled()

                            ->dehydrated()

                            ->searchable()

                            ->preload()

                            ->required()

                            ->helperText(
                                'El lote se asigna automáticamente según la vacuna seleccionada.'
                            ),

                        // =========================
                        // 📅 FECHA
                        // =========================
                        DatePicker::make('fecha_aplicacion')
                            ->label('Fecha de aplicación')
                            ->required(),

                        // =========================
                        // 📝 OBSERVACIONES
                        // =========================
                        Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->rows(3)
                            ->columnSpanFull(),

                    ])

                    ->columns(2),
            ]);
    }
}