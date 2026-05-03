<?php

namespace App\Filament\Resources\Ventas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\TrashedFilter;


class VentasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // 👤 EMPLEADO
                TextColumn::make('usuario.name')
                    ->label('Empleado')
                    ->icon('heroicon-o-user')
                    ->searchable()
                    ->sortable(),

                // 🐾 CLIENTE
                TextColumn::make('cliente.name')
                    ->label('Cliente')
                    ->icon('heroicon-o-user-circle')
                    ->searchable()
                    ->sortable(),

                // 💰 TOTAL
                TextColumn::make('total')
                    ->label('Total')
                    ->money('COP', true)
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                // 📌 ESTADO
                TextColumn::make('estado')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->color(fn($state) => match ($state) {
                        'pendiente' => 'warning',
                        'pagado' => 'success',
                        'cancelado' => 'danger',
                        default => 'gray',
                    }),

                // ⏱️ HACE CUÁNTO
                TextColumn::make('created_at')
                    ->label('Hace')
                    ->since() // 🔥 tipo "hace 2 horas"
                    ->color('gray'),
            ])

            ->defaultSort('created_at', 'desc') // 🔥 más reciente arriba

            ->filters([
                TrashedFilter::make(),
            ])

            ->recordActions([
                EditAction::make()
                    ->label('Editar'),

                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),

                Action::make('cambiar_estado')
                    ->label('Cambiar estado')
                    ->icon('heroicon-o-pencil-square')
                    ->color('info')

                    // 🔥 reemplazo de form() → schema()
                    ->schema([
                        Select::make('estado')
                            ->label('Estado')
                            ->placeholder('Selecciona un estado')
                            ->loadingMessage('Cargando estados...')
                            ->noSearchResultsMessage('No se encontraron estados')
                            ->noOptionsMessage('No hay estados disponibles')
                            ->searchingMessage('buscando estados...')
                            ->options([
                                'pendiente' => 'Pendiente',
                                'pagado' => 'Pagado',
                                'cancelado' => 'Cancelado',
                            ])
                            ->required(),
                    ])

                    // 🔥 confirmación antes de ejecutar
                    ->requiresConfirmation()

                    ->action(function ($record, array $data) {
                        $record->update([
                            'estado' => $data['estado'],
                        ]);
                    })
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
