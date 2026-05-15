<?php

namespace App\Filament\System\Resources\VacunaAplicadas\Pages;

use App\Filament\System\Resources\VacunaAplicadas\VacunaAplicadaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVacunaAplicadas extends ListRecords
{
    protected static string $resource = VacunaAplicadaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
