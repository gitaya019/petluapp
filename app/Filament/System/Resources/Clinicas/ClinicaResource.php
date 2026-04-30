<?php

namespace App\Filament\System\Resources\Clinicas;

use App\Filament\System\Resources\Clinicas\Pages\CreateClinica;
use App\Filament\System\Resources\Clinicas\Pages\EditClinica;
use App\Filament\System\Resources\Clinicas\Pages\ListClinicas;
use App\Filament\System\Resources\Clinicas\Schemas\ClinicaForm;
use App\Filament\System\Resources\Clinicas\Tables\ClinicasTable;
use App\Models\Clinica;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClinicaResource extends Resource
{
    protected static ?string $model = Clinica::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static string | \UnitEnum | null $navigationGroup = 'Administración';

    public static function form(Schema $schema): Schema
    {
        return ClinicaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClinicasTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClinicas::route('/'),
            'create' => CreateClinica::route('/create'),
            'edit' => EditClinica::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}