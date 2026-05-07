<?php

namespace App\Filament\Resources\Ventas;

use App\Filament\Resources\Ventas\Pages\CreateVenta;
use App\Filament\Resources\Ventas\Pages\EditVenta;
use App\Filament\Resources\Ventas\Pages\ListVentas;
use App\Filament\Resources\Ventas\Schemas\VentaForm;
use App\Filament\Resources\Ventas\Tables\VentasTable;
use App\Models\Venta;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use EslamRedaDiv\FilamentCopilot\Contracts\CopilotResource;

class VentaResource extends Resource implements CopilotResource
{
    protected static ?string $model = Venta::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CurrencyDollar;

    protected static string|\UnitEnum|null $navigationGroup = 'Finanzas';

    public static function form(Schema $schema): Schema
    {
        return VentaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VentasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVentas::route('/'),
            'create' => CreateVenta::route('/create'),
            'edit' => EditVenta::route('/{record}/edit'),
        ];
    }

    public static function copilotResourceDescription(): ?string
    {
        return '
            Gestiona ventas veterinarias,
            ingresos, pagos, productos
            y transacciones del sistema.
        ';
    }

    public static function copilotTools(): array
    {
        return [
            new \App\Filament\Resources\Ventas\CopilotTools\ListVentasTool(),
            new \App\Filament\Resources\Ventas\CopilotTools\VentasHoyTool(),
            new \App\Filament\Resources\Ventas\CopilotTools\VentasMesTool(),
        ];
    }
}