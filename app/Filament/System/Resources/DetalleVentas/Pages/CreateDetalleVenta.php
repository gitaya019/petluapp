<?php

namespace App\Filament\System\Resources\DetalleVentas\Pages;

use App\Filament\System\Resources\DetalleVentas\DetalleVentaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDetalleVenta extends CreateRecord
{
    protected static string $resource = DetalleVentaResource::class;
}
