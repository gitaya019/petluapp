<?php

namespace App\Filament\Resources\MovimientoStocks\Pages;

use App\Filament\Resources\MovimientoStocks\MovimientoStockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMovimientoStocks extends ListRecords
{
    protected static string $resource = MovimientoStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
