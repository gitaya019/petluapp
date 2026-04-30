<?php

namespace App\Filament\System\Resources\Clinicas\Pages;

use App\Filament\System\Resources\Clinicas\ClinicaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClinica extends CreateRecord
{
    protected static string $resource = ClinicaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
