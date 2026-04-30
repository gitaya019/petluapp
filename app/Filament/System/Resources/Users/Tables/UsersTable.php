<?php

namespace App\Filament\System\Resources\Users\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable(),

                IconColumn::make('estado')
                    ->label('Activo')
                    ->boolean(),

                IconColumn::make('is_super_admin')
                    ->label('Super Admin')
                    ->boolean(),

                TextColumn::make('clinicas_count')
                    ->label('Clínicas')
                    ->getStateUsing(function ($record) {
                        return \Illuminate\Support\Facades\DB::table('model_has_roles')
                            ->where('model_id', $record->id)
                            ->where('model_type', \App\Models\User::class)
                            ->distinct('clinica_id')
                            ->count('clinica_id');
                    })
                    ->badge(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
