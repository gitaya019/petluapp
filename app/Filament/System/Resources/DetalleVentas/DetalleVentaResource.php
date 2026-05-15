<?php

namespace App\Filament\System\Resources\DetalleVentas;

use App\Filament\System\Resources\DetalleVentas\Pages\CreateDetalleVenta;
use App\Filament\System\Resources\DetalleVentas\Pages\EditDetalleVenta;
use App\Filament\System\Resources\DetalleVentas\Pages\ListDetalleVentas;
use App\Filament\System\Resources\DetalleVentas\Schemas\DetalleVentaForm;
use App\Filament\System\Resources\DetalleVentas\Tables\DetalleVentasTable;
use App\Models\DetalleVenta;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use YusufGenc34\FilamentApiForge\Contracts\HasApi;


class DetalleVentaResource extends Resource implements HasApi
{
    protected static ?string $model = DetalleVenta::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DetalleVentaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DetalleVentasTable::configure($table);
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
            'index' => ListDetalleVentas::route('/'),
            'create' => CreateDetalleVenta::route('/create'),
            'edit' => EditDetalleVenta::route('/{record}/edit'),
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
