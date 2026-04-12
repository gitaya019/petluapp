<?php

namespace App\Filament\Resources\Recordatorios;

use App\Filament\Resources\Recordatorios\Pages\CreateRecordatorio;
use App\Filament\Resources\Recordatorios\Pages\EditRecordatorio;
use App\Filament\Resources\Recordatorios\Pages\ListRecordatorios;
use App\Filament\Resources\Recordatorios\Schemas\RecordatorioForm;
use App\Filament\Resources\Recordatorios\Tables\RecordatoriosTable;
use App\Models\Recordatorio;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RecordatorioResource extends Resource
{
    protected static ?string $model = Recordatorio::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BellAlert;

    protected static string | \UnitEnum | null $navigationGroup = 'Veterinaria';

    public static function form(Schema $schema): Schema
    {
        return RecordatorioForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecordatoriosTable::configure($table);
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
            'index' => ListRecordatorios::route('/'),
            'create' => CreateRecordatorio::route('/create'),
            'edit' => EditRecordatorio::route('/{record}/edit'),
        ];
    }
}
