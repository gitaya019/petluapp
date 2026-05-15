<?php

namespace App\Filament\System\Resources\DetalleVentas\Pages;

use App\Filament\System\Resources\DetalleVentas\DetalleVentaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDetalleVenta extends EditRecord
{
    protected static string $resource = DetalleVentaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
