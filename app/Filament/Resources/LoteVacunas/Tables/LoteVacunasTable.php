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

                TextColumn::make('fecha_vencimiento')->date(),

                TextColumn::make('stock_inicial'),

                TextColumn::make('stock_actual'),
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
