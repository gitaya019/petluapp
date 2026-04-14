<?php

namespace App\Filament\Resources\Vacunas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;

class VacunasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // 💉 NOMBRE
                TextColumn::make('nombre')
                    ->label('Vacuna')
                    ->icon('heroicon-o-beaker')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // 🔢 DOSIS
                TextColumn::make('dosis')
                    ->label('Dosis')
                    ->badge()
                    ->color('info'),

                // 🏭 FABRICANTE
                TextColumn::make('fabricante')
                    ->label('Fabricante')
                    ->icon('heroicon-o-building-office')
                    ->searchable()
                    ->toggleable(),

                // 💰 PRECIO
                TextColumn::make('precio_dosis')
                    ->label('Precio')
                    ->money('COP', true)
                    ->color('success')
                    ->weight('bold')
                    ->sortable(),

                // 🟢 ESTADO
                IconColumn::make('estado')
                    ->label('Activo')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                // 📅 FECHA
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d M Y')
                    ->since() // 🔥 hace X tiempo
                    ->color('gray')
                    ->toggleable(),
            ])

            ->defaultSort('created_at', 'desc')

            ->filters([
                TrashedFilter::make(),
            ])

            ->recordActions([
                EditAction::make()
                    ->label('Editar'),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
