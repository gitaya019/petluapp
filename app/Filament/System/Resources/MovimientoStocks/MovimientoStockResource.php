<?php

namespace App\Filament\System\Resources\MovimientoStocks;

use App\Filament\System\Resources\MovimientoStocks\Pages\CreateMovimientoStock;
use App\Filament\System\Resources\MovimientoStocks\Pages\EditMovimientoStock;
use App\Filament\System\Resources\MovimientoStocks\Pages\ListMovimientoStocks;
use App\Filament\System\Resources\MovimientoStocks\Schemas\MovimientoStockForm;
use App\Filament\System\Resources\MovimientoStocks\Tables\MovimientoStocksTable;
use App\Models\MovimientoStock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use YusufGenc34\FilamentApiForge\Contracts\HasApi;


class MovimientoStockResource extends Resource implements HasApi
{
    protected static ?string $model = MovimientoStock::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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

    public static function apiConfig(): array
    {
        return [
            'allowed_methods'   => ['index', 'show', 'store', 'update', 'destroy'],
            'allowed_filters'   => ['title', 'status', 'category_id'],
            'allowed_sorts'     => ['title', 'created_at', 'published_at'],
            'allowed_includes'  => ['author', 'category'],
            'allowed_fields'    => ['id', 'title', 'slug', 'body', 'status', 'published_at'],
            'searchable'        => ['title', 'body'],
            'scopes'            => ['read', 'write', 'delete'],
            'validation_rules'  => [
                'title'  => ['required', 'string', 'max:255'],
                'body'   => ['required', 'string'],
                'status' => ['required', 'in:draft,published,archived'],
            ],
        ];
    }
}
