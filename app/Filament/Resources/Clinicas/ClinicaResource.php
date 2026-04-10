<?php

namespace App\Filament\Resources\Clinicas;

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

class ClinicaResource extends Resource
{
    protected static ?string $model = Clinica::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
            'create' => CreateClinica::route('/create'),
            'edit' => EditClinica::route('/{record}/edit'),
        ];
    }
}
