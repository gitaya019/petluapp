<?php

namespace App\Filament\Resources\Ventas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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

                // 📅 FECHA
                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->dateTime('d M Y - H:i')
                    ->sortable()
                    ->color('gray'),

                // ⏱️ HACE CUÁNTO
                TextColumn::make('created_at')
                    ->label('Hace')
                    ->since() // 🔥 tipo "hace 2 horas"
                    ->color('gray'),
            ])

            ->defaultSort('fecha', 'desc') // 🔥 más reciente arriba

            ->filters([
                //
            ])

            ->recordActions([
                EditAction::make()
                    ->label('Editar'),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
