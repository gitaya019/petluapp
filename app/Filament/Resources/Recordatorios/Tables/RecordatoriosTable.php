<?php

namespace App\Filament\Resources\Recordatorios\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class RecordatoriosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('mascota.nombre')
                    ->label('Mascota')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-heart'),

                TextColumn::make('vacuna.nombre')
                    ->label('Vacuna')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-sparkles'),

                BadgeColumn::make('tipo')
                    ->label('Tipo')
                    ->colors([
                        'warning' => 'refuerzo_15_dias',
                        'danger' => 'refuerzo_1_dia',
                        'gray' => true,
                    ])
                    ->formatStateUsing(fn($state) => match ($state) {
                        'refuerzo_15_dias' => '15 días',
                        'refuerzo_1_dia' => '1 día',
                        default => $state,
                    }),

                TextColumn::make('mensaje')
                    ->label('Mensaje')
                    ->limit(40)
                    ->tooltip(fn($record) => $record->mensaje)
                    ->wrap(),

                TextColumn::make('fecha_programada')
                    ->label('Fecha')
                    ->date()
                    ->sortable()
                    ->icon('heroicon-o-calendar-days'),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'success' => 'enviado',
                        'warning' => 'pendiente',
                        'danger' => 'vencido',
                        'gray' => true,
                    ])
                    ->formatStateUsing(fn($state) => ucfirst($state)),

                TextColumn::make('enviado_at')
                    ->label('Enviado')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),

            ])
            ->defaultSort('fecha_programada', 'asc')

            // 🔍 FILTROS PRO
            ->filters([

                SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'enviado' => 'Enviado',
                        'vencido' => 'Vencido',
                    ]),

                SelectFilter::make('tipo')
                    ->options([
                        'refuerzo_15_dias' => 'Refuerzo 15 días',
                        'refuerzo_1_dia' => 'Refuerzo 1 día',
                    ]),

                Filter::make('hoy')
                    ->label('Hoy')
                    ->query(fn($query) => $query->whereDate('fecha_programada', today())),

                Filter::make('proximos_7_dias')
                    ->label('Próximos 7 días')
                    ->query(fn($query) => $query->whereBetween('fecha_programada', [now(), now()->addDays(7)])),

                Filter::make('vencidos')
                    ->label('Vencidos')
                    ->query(fn($query) => $query->where('fecha_programada', '<', now())),
            ])

            // 🔎 BUSCADOR GLOBAL MÁS POTENTE
            ->searchable()

            // ⚡ ACCIONES
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-o-pencil-square'),
            ])

            // 🧨 BULK ACTIONS
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
