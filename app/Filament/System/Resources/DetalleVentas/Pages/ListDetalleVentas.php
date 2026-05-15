<?php

namespace App\Filament\System\Resources\DetalleVentas\Pages;

use App\Filament\System\Resources\DetalleVentas\DetalleVentaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDetalleVentas extends ListRecords
{
    protected static string $resource = DetalleVentaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
