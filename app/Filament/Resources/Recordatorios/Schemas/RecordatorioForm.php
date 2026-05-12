<?php

namespace App\Filament\Resources\Recordatorios\Schemas;

use App\Models\User;
use App\Models\Mascota;

use Filament\Schemas\Schema;

use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;

class RecordatorioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Recordatorio')
                    ->icon('heroicon-o-bell')

                    ->description(
                        'Busca un cliente mediante su correo electrónico y selecciona una de sus mascotas.'
                    )

                    ->schema([

                        // =========================
                        // 📧 CORREO CLIENTE
                        // =========================
                        TextInput::make('cliente_email')
                            ->label('Correo del cliente')

                            ->email()

                            ->placeholder('cliente@email.com')

                            ->live(debounce: 600)

                            ->helperText(
                                'Escribe el correo electrónico del cliente para cargar automáticamente sus mascotas.'
                            )

                            ->dehydrated(false)

                            ->afterStateUpdated(function ($state, callable $set) {

                                $cliente = User::query()
                                    ->where('email', $state)
                                    ->first();

                                // limpiar mascota al cambiar email
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

                                // ❌ NO ENCONTRADO
                                $set('cliente_id_temp', null);

                                $set('cliente_nombre', null);

                                $set(
                                    'cliente_estado',
                                    '❌ No existe un cliente con ese correo electrónico'
                                );
                            })

                            ->required(),

                        // =========================
                        // 🔒 CLIENTE TEMP
                        // =========================
                        Hidden::make('cliente_id_temp')
                            ->dehydrated(false),

                        // =========================
                        // 👤 NOMBRE CLIENTE
                        // =========================
                        TextInput::make('cliente_nombre')
                            ->label('Cliente encontrado')
                            ->readOnly()
                            ->dehydrated(false)

                            ->placeholder(
                                'Aquí aparecerá el nombre del cliente'
                            ),

                        // =========================
                        // 📢 ESTADO BÚSQUEDA
                        // =========================
                        Placeholder::make('estado_cliente')
                            ->label('Estado de búsqueda')

                            ->content(function (callable $get) {

                                return $get('cliente_estado')
                                    ?: 'Esperando búsqueda...';
                            }),

                        // =========================
                        // 🐶 MASCOTAS CLIENTE
                        // =========================
                        Select::make('mascota_id')
                            ->label('Mascota')

                            ->options(function (callable $get) {

                                $clienteId = $get('cliente_id_temp');

                                if (! $clienteId) {
                                    return [];
                                }

                                return Mascota::query()
                                    ->where('user_id', $clienteId)
                                    ->pluck('nombre', 'id');
                            })

                            ->searchable()

                            ->preload()

                            ->placeholder('Selecciona una mascota')

                            ->loadingMessage('Cargando mascotas...')

                            ->noSearchResultsMessage('No se encontraron mascotas')

                            ->noOptionsMessage(
                                'Este cliente no tiene mascotas registradas'
                            )

                            ->searchingMessage('Buscando mascotas...')

                            ->searchDebounce(500)

                            ->searchPrompt('Buscar por nombre...')

                            ->required(),

                        // =========================
                        // 💉 VACUNA
                        // =========================
                        Select::make('vacuna_id')
                            ->relationship('vacuna', 'nombre')

                            ->searchable()

                            ->preload()

                            ->required()

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

                            ->searchPrompt(
                                'Buscar por nombre de vacuna...'
                            ),

                        // =========================
                        // 🧠 TIPO
                        // =========================
                        TextInput::make('tipo')
                            ->label('Tipo de recordatorio')
                            ->required()
                            ->maxLength(100),

                        // =========================
                        // 📝 MENSAJE
                        // =========================
                        TextInput::make('mensaje')
                            ->label('Mensaje')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),

                        // =========================
                        // 📅 FECHA
                        // =========================
                        DatePicker::make('fecha_programada')
                            ->label('Fecha programada')
                            ->required(),

                        // =========================
                        // 📊 ESTADO
                        // =========================
                        Select::make('estado')
                            ->label('Estado')
                            ->placeholder('Selecciona un estado')

                            ->loadingMessage('Cargando estados...')

                            ->noSearchResultsMessage('No se encontraron estados')

                            ->noOptionsMessage(
                                'No hay estados disponibles'
                            )

                            ->searchingMessage('Buscando estados...')

                            ->searchDebounce(500)

                            ->searchPrompt('Buscar por nombre...')

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
