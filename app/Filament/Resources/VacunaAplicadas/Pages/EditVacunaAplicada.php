<?php

namespace App\Filament\Resources\VacunaAplicadas\Pages;

use App\Filament\Resources\VacunaAplicadas\VacunaAplicadaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVacunaAplicada extends EditRecord
{
    protected static string $resource = VacunaAplicadaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
