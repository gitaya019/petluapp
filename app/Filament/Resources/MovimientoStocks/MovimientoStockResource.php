<?php

namespace App\Filament\Resources\MovimientoStocks;

use App\Filament\Resources\MovimientoStocks\Pages\CreateMovimientoStock;
use App\Filament\Resources\MovimientoStocks\Pages\EditMovimientoStock;
use App\Filament\Resources\MovimientoStocks\Pages\ListMovimientoStocks;
use App\Filament\Resources\MovimientoStocks\Schemas\MovimientoStockForm;
use App\Filament\Resources\MovimientoStocks\Tables\MovimientoStocksTable;
use App\Models\MovimientoStock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MovimientoStockResource extends Resource
{
    protected static ?string $model = MovimientoStock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowsRightLeft;

    public static function form(Schema $schema): Schema
    {
        return MovimientoStockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MovimientoStocksTable::configure($table);
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
            'index' => ListMovimientoStocks::route('/'),
            'create' => CreateMovimientoStock::route('/create'),
            'edit' => EditMovimientoStock::route('/{record}/edit'),
        ];
    }
}
