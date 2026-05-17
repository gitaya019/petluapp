<?php

namespace App\Filament\Resources\Citas;

use App\Filament\Resources\Citas\Pages\CreateCita;
use App\Filament\Resources\Citas\Pages\EditCita;
use App\Filament\Resources\Citas\Pages\ListCitas;
use App\Filament\Resources\Citas\Schemas\CitaForm;
use App\Filament\Resources\Citas\Tables\CitasTable;
use App\Models\Cita;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Facades\Filament;

class CitaResource extends Resource
{
    protected static ?string $model = Cita::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    protected static string|\UnitEnum|null $navigationGroup = 'Veterinaria';

    public static function form(Schema $schema): Schema
    {
        return CitaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CitasTable::configure($table);
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
            'index' => ListCitas::route('/'),
            'create' => CreateCita::route('/create'),
            'edit' => EditCita::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('clinica_id', Filament::getTenant()->id);
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }


    public static function copilotResourceDescription(): ?string
    {
        return '
        Gestiona citas veterinarias.
        Incluye mascotas, veterinarios,
        vacunas, fechas, horas,
        estados, motivos y observaciones.
    ';
    }

}
