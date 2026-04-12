<?php

namespace App\Filament\Resources\VacunaAplicadas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VacunaAplicadasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mascota.nombre')->label('Mascota'),
                TextColumn::make('vacuna.nombre')->label('Vacuna'),
                TextColumn::make('lote.numero_lote')->label('Lote'),
                TextColumn::make('veterinario.name')->label('Veterinario'),
                TextColumn::make('fecha_aplicacion')->date(),
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
