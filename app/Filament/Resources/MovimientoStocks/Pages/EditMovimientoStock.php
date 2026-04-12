<?php

namespace App\Filament\Resources\MovimientoStocks\Pages;

use App\Filament\Resources\MovimientoStocks\MovimientoStockResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMovimientoStock extends EditRecord
{
    protected static string $resource = MovimientoStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
