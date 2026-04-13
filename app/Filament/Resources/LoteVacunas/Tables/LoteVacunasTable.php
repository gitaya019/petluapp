<?php

namespace App\Filament\Resources\LoteVacunas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;


class LoteVacunasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vacuna.nombre')
                    ->label('Vacuna')
                    ->searchable(),

                TextColumn::make('numero_lote'),

                TextColumn::make('fecha_vencimiento')
                    ->date('d M Y')
                    ->color(function ($record) {
                        if (now()->greaterThan($record->fecha_vencimiento)) {
                            return 'danger'; // vencido
                        }

                        if (now()->addDays(30)->greaterThan($record->fecha_vencimiento)) {
                            return 'warning'; // por vencer
                        }

                        return 'success';
                    }),
                TextColumn::make('stock_inicial'),

                TextColumn::make('stock_actual')
                    ->label('Stock')
                    ->badge()
                    ->color(fn($state) => match (true) {
                        $state == 0 => 'danger',     // 🔴 agotado
                        $state <= 5 => 'warning',    // 🟡 bajo
                        default => 'success',        // 🟢 normal
                    }),
            ])
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
