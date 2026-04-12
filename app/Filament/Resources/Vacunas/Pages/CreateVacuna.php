<?php

namespace App\Filament\Resources\Vacunas\Pages;

use App\Filament\Resources\Vacunas\VacunaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVacuna extends CreateRecord
{
    protected static string $resource = VacunaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
