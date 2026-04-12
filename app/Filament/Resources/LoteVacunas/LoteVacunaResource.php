<?php

namespace App\Filament\Resources\LoteVacunas;

use App\Filament\Resources\LoteVacunas\Pages\CreateLoteVacuna;
use App\Filament\Resources\LoteVacunas\Pages\EditLoteVacuna;
use App\Filament\Resources\LoteVacunas\Pages\ListLoteVacunas;
use App\Filament\Resources\LoteVacunas\Schemas\LoteVacunaForm;
use App\Filament\Resources\LoteVacunas\Tables\LoteVacunasTable;
use App\Models\LoteVacuna;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LoteVacunaResource extends Resource
{
    protected static ?string $model = LoteVacuna::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArchiveBox;

    public static function form(Schema $schema): Schema
    {
        return LoteVacunaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoteVacunasTable::configure($table);
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
            'index' => ListLoteVacunas::route('/'),
            'create' => CreateLoteVacuna::route('/create'),
            'edit' => EditLoteVacuna::route('/{record}/edit'),
        ];
    }
}
