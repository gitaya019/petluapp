<?php

namespace App\Filament\System\Resources\HistorialMedicos\Pages;

use App\Filament\System\Resources\HistorialMedicos\HistorialMedicoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHistorialMedicos extends ListRecords
{
    protected static string $resource = HistorialMedicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
