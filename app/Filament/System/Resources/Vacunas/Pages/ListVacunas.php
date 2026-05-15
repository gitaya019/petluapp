<?php

namespace App\Filament\System\Resources\Vacunas\Pages;

use App\Filament\System\Resources\Vacunas\VacunaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVacunas extends ListRecords
{
    protected static string $resource = VacunaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
