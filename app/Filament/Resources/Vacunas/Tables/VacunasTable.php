<?php

namespace App\Filament\Resources\Vacunas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VacunasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->Columns([
                TextColumn::make('nombre')->searchable()->sortable(),

                TextColumn::make('dosis'),

                TextColumn::make('fabricante'),

                IconColumn::make('estado')
                    ->boolean(),

                TextColumn::make('precio_dosis')->money('COP'),


                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Creado'),

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
