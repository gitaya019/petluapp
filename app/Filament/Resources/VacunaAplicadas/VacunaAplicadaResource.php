<?php

namespace App\Filament\Resources\VacunaAplicadas;

use App\Filament\Resources\VacunaAplicadas\Pages\CreateVacunaAplicada;
use App\Filament\Resources\VacunaAplicadas\Pages\EditVacunaAplicada;
use App\Filament\Resources\VacunaAplicadas\Pages\ListVacunaAplicadas;
use App\Filament\Resources\VacunaAplicadas\Schemas\VacunaAplicadaForm;
use App\Filament\Resources\VacunaAplicadas\Tables\VacunaAplicadasTable;
use App\Models\VacunaAplicada;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VacunaAplicadaResource extends Resource
{
    protected static ?string $model = VacunaAplicada::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CheckBadge;

    protected static string | \UnitEnum | null $navigationGroup = 'Veterinaria';
    
    public static function form(Schema $schema): Schema
    {
        return VacunaAplicadaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VacunaAplicadasTable::configure($table);
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
            'index' => ListVacunaAplicadas::route('/'),
            'create' => CreateVacunaAplicada::route('/create'),
            'edit' => EditVacunaAplicada::route('/{record}/edit'),
        ];
    }
}
