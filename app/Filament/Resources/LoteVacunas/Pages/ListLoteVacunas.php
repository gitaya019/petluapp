<?php

namespace App\Filament\Resources\LoteVacunas\Pages;

use App\Filament\Resources\LoteVacunas\LoteVacunaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLoteVacunas extends ListRecords
{
    protected static string $resource = LoteVacunaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
