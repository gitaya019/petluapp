<?php

namespace App\Filament\Resources\Citas\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\TrashedFilter;
use App\Models\Cita;
use App\Notifications\CitaCanceladaNotification;

class CitasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('fecha', 'desc')

            ->columns([

                TextColumn::make('mascota.nombre')
                    ->label('Mascota')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->icon('heroicon-m-heart')
                    ->description(
                        fn(Cita $record) =>
                        $record->mascota?->user?->name
                    ),

                TextColumn::make('veterinario.name')
                    ->label('Veterinario')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-user'),

                TextColumn::make('vacuna.nombre')
                    ->label('Vacuna')
                    ->placeholder('Sin vacuna')
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-m-shield-check'),

                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->icon('heroicon-m-calendar-days'),

                TextColumn::make('hora')
                    ->label('Hora')
                    ->time('h:i A')
                    ->sortable()
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-m-clock'),

                BadgeColumn::make('estado')
                    ->label('Estado')
                    ->sortable()
                    ->colors([
                        'warning' => 'pendiente',
                        'info' => 'confirmada',
                        'success' => 'completada',
                        'danger' => 'cancelada',
                        'gray' => 'no_asistio',
                    ])
                    ->icons([
                        'heroicon-m-clock' => 'pendiente',
                        'heroicon-m-check-circle' => 'confirmada',
                        'heroicon-m-check-badge' => 'completada',
                        'heroicon-m-x-circle' => 'cancelada',
                        'heroicon-m-minus-circle' => 'no_asistio',
                    ]),

                TextColumn::make('created_at')
                    ->label('Creada')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->filters([
                TrashedFilter::make(),
            ])

            ->recordActions([

                ActionGroup::make([

                    EditAction::make()
                        ->color('warning')
                        ->icon('heroicon-m-pencil-square')
                        ->slideOver(),

                    \Filament\Actions\Action::make('cambiar_estado')
                        ->label('Cambiar Estado')
                        ->icon('heroicon-m-arrow-path')
                        ->color('info')

                        ->schema([

                            Select::make('estado')
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
                                    'confirmada' => 'Confirmada',
                                    'completada' => 'Completada',
                                    'cancelada' => 'Cancelada',
                                    'no_asistio' => 'No asistió',
                                ])

                                ->required()

                                ->default(
                                    fn(Cita $record) => $record->estado
                                ),

                        ])

                        ->action(function (array $data, Cita $record) {

                            $estadoAnterior = $record->estado;

                            // =============================================
                            // ACTUALIZAR ESTADO
                            // =============================================

                            $record->update([
                                'estado' => $data['estado'],
                            ]);

                            $record->refresh();

                            $cliente = $record->mascota?->user;

                            // =============================================
                            // SOLO SI CAMBIÓ A CONFIRMADA
                            // =============================================

                            if (
                                $estadoAnterior !== 'confirmada' &&
                                $record->estado === 'confirmada' &&
                                $cliente?->email
                            ) {

                                // =========================================
                                // EVITAR DUPLICADOS
                                // =========================================

                                $existeRecordatorio = \App\Models\Recordatorio::query()

                                    ->where('mascota_id', $record->mascota_id)

                                    ->where('tipo', 'cita')

                                    ->whereDate(
                                        'fecha_programada',
                                        $record->fecha
                                    )

                                    ->exists();

                                if (! $existeRecordatorio) {

                                    // =====================================
                                    // CREAR RECORDATORIO
                                    // =====================================

                                    \App\Models\Recordatorio::create([

                                        'clinica_id' => $record->clinica_id,

                                        'mascota_id' => $record->mascota_id,

                                        'vacuna_id' => $record->vacuna_id,

                                        'tipo' => 'cita',

                                        'mensaje' =>
                                        'Tienes una cita programada para '
                                            . $record->mascota->nombre
                                            . ' el día '
                                            . $record->fecha->format('d/m/Y')
                                            . ' a las '
                                            . \Carbon\Carbon::parse(
                                                $record->hora
                                            )->format('h:i A'),

                                        'fecha_programada' => $record->fecha,

                                        'estado' => 'enviado',

                                        'enviado' => true,

                                        'enviado_at' => now(),

                                        'correo_destino' => $cliente->email,
                                    ]);
                                }

                                // =========================================
                                // ENVIAR CORREO
                                // =========================================

                                $cliente->notify(
                                    new \App\Notifications\CitaConfirmadaNotification(
                                        $record
                                    )
                                );
                            }

                            if (
                                $estadoAnterior !== 'cancelada' &&
                                $record->estado === 'cancelada' &&
                                $cliente?->email
                            ) {

                                $cliente->notify(
                                    new CitaCanceladaNotification($record)
                                );
                            }

                            Notification::make()
                                ->title('Estado actualizado')
                                ->success()
                                ->body(
                                    'La cita fue actualizada correctamente.'
                                )
                                ->send();
                        })

                        ->modalHeading('Cambiar estado de la cita')

                        ->modalIcon('heroicon-m-arrow-path'),

                    \Filament\Actions\DeleteAction::make()
                        ->color('danger')
                        ->icon('heroicon-m-trash'),

                ])
                    ->label('Acciones')
                    ->icon('heroicon-m-ellipsis-horizontal')
                    ->button()
                    ->color('gray'),

            ])

            ->toolbarActions([

                BulkActionGroup::make([

                    DeleteBulkAction::make(),

                    ForceDeleteBulkAction::make(),

                    RestoreBulkAction::make(),

                ]),

            ])

            ->emptyStateHeading('No hay citas registradas')

            ->emptyStateDescription(
                'Crea la primera cita para comenzar.'
            )

            ->emptyStateIcon('heroicon-o-calendar-days')

            ->striped();
    }
}
