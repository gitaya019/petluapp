<?php

namespace App\Filament\Resources\VacunaAplicadas\Pages;

use App\Filament\Resources\VacunaAplicadas\VacunaAplicadaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVacunaAplicada extends CreateRecord
{
    protected static string $resource = VacunaAplicadaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
