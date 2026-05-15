<?php

namespace App\Filament\System\Resources\MovimientoStocks\Pages;

use App\Filament\System\Resources\MovimientoStocks\MovimientoStockResource;
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
