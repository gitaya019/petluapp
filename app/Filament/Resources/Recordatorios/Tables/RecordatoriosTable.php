<?php

namespace App\Filament\Resources\Recordatorios\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RecordatoriosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('mascota.nombre')
                    ->label('Mascota')
                    ->searchable(),

                TextColumn::make('vacuna.nombre')
                    ->label('Vacuna')
                    ->searchable(),

                TextColumn::make('tipo')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'refuerzo_15_dias' => 'warning',
                        'refuerzo_1_dia' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('mensaje')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->mensaje),

                TextColumn::make('fecha_programada')
                    ->date()
                    ->sortable(),

                TextColumn::make('estado')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'enviado' => 'success',
                        'pendiente' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('enviado_at')
                    ->dateTime()
                    ->label('Enviado')
                    ->placeholder('No enviado'),

            ])
            ->defaultSort('fecha_programada', 'asc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}