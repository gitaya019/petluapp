<?php

namespace App\Filament\Resources\Vacunas;

use App\Filament\Resources\Vacunas\Pages\CreateVacuna;
use App\Filament\Resources\Vacunas\Pages\EditVacuna;
use App\Filament\Resources\Vacunas\Pages\ListVacunas;
use App\Filament\Resources\Vacunas\Schemas\VacunaForm;
use App\Filament\Resources\Vacunas\Tables\VacunasTable;
use App\Models\Vacuna;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use EslamRedaDiv\FilamentCopilot\Contracts\CopilotResource;

class VacunaResource extends Resource implements CopilotResource
{
    protected static ?string $model = Vacuna::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Beaker;

    protected static string|\UnitEnum|null $navigationGroup = 'Inventario';

    public static function form(Schema $schema): Schema
    {
        return VacunaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VacunasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVacunas::route('/'),
            'create' => CreateVacuna::route('/create'),
            'edit' => EditVacuna::route('/{record}/edit'),
        ];
    }

    public static function copilotResourceDescription(): ?string
    {
        return '
            Gestiona vacunas veterinarias.
            Controla stock, fechas de vencimiento,
            lotes y disponibilidad.
        ';
    }

    public static function copilotTools(): array
    {
        return [
            new \App\Filament\Resources\Vacunas\CopilotTools\ListVacunasTool(),
            new \App\Filament\Resources\Vacunas\CopilotTools\SearchVacunasTool(),
            new \App\Filament\Resources\Vacunas\CopilotTools\VacunasVencidasTool(),
        ];
    }
}