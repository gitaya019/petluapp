<?php

namespace App\Filament\Resources\Ventas\Schemas;

use App\Models\User;

use Filament\Schemas\Schema;

use Filament\Facades\Filament;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;

class VentaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Venta')
                    ->icon('heroicon-o-currency-dollar')

                    ->description(
                        'Busca el cliente mediante su documento y registra la venta.'
                    )

                    ->schema([

                        // =========================
                        // 👨‍💼 EMPLEADO LOGUEADO
                        // =========================
                        Hidden::make('usuario_id')
                            ->default(fn() => Filament::auth()->id())
                            ->required(),

                        Placeholder::make('empleado_actual')
                            ->label('Empleado que realiza la venta')

                            ->content(
                                fn() => Filament::auth()->user()?->name
                                    ?? 'No autenticado'
                            ),

                        // =========================
                        // 🪪 DOCUMENTO CLIENTE
                        // =========================
                        TextInput::make('cliente_documento')
                            ->label('Documento del cliente')

                            ->placeholder(
                                'Ingresa el número de documento'
                            )

                            ->live(debounce: 600)

                            ->helperText(
                                'Busca automáticamente un cliente registrado.'
                            )

                            ->dehydrated(false)

                            ->afterStateUpdated(function ($state, callable $set) {

                                $cliente = User::query()
                                    ->where('numero_documento', $state)
                                    ->first();

                                if ($cliente) {

                                    $set('cliente_id', $cliente->id);

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
                                $set('cliente_id', null);

                                $set('cliente_nombre', null);

                                $set(
                                    'cliente_estado',
                                    '❌ No existe un cliente con ese documento'
                                );
                            })

                            ->required(),

                        // =========================
                        // 🔒 CLIENTE ID REAL
                        // =========================
                        Hidden::make('cliente_id')
                            ->required(),

                        // =========================
                        // 👤 CLIENTE ENCONTRADO
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
                        // 💰 TOTAL
                        // =========================
                        TextInput::make('total')
                            ->label('Total')

                            ->numeric()

                            ->prefix('$')

                            ->required(),

                        // =========================
                        // 📊 ESTADO
                        // =========================
                        Select::make('estado')
                            ->label('Estado')
                            ->placeholder('Selecciona un estado')

                            ->loadingMessage('Cargando estados...')

                            ->noSearchResultsMessage(
                                'No se encontraron estados'
                            )

                            ->noOptionsMessage(
                                'No hay estados disponibles'
                            )

                            ->searchingMessage('Buscando estados...')

                            ->searchDebounce(500)

                            ->searchPrompt('Buscar por nombre...')


                            ->options([
                                'pagado' => 'Pagado',
                                'pendiente' => 'Pendiente',
                            ])

                            ->required(),

                        // =========================
                        // 📅 FECHA
                        // =========================
                        DatePicker::make('fecha')
                            ->label('Fecha')
                            ->required(),

                    ])

                    ->columns(2),
            ]);
    }
}
