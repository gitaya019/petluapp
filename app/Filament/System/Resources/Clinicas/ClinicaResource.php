<?php

namespace App\Filament\System\Resources\Clinicas;

use App\Filament\Resources\Clinicas\Pages\CreateClinica;
use App\Filament\Resources\Clinicas\Pages\EditClinica;
use App\Filament\Resources\Clinicas\Pages\ListClinicas;
use App\Filament\Resources\Clinicas\Schemas\ClinicaForm;
use App\Filament\Resources\Clinicas\Tables\ClinicasTable;
use App\Models\Clinica;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;


class ClinicaResource extends Resource
{
    protected static ?string $model = Clinica::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static string | \UnitEnum | null $navigationGroup = 'Admin';

    public static function form(Schema $schema): Schema
    {
        return ClinicaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClinicasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClinicas::route('/'),
            'edit' => EditClinica::route('/{record}/edit'),
        ];
    }

    public static function isScopedToTenant(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('id', Filament::getTenant()?->id);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return $record->id !== Filament::getTenant()?->id;
    }

    public static function canEdit($record): bool
    {
        return $record->id === Filament::getTenant()?->id;
    }

}
