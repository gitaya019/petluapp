<?php

namespace App\Filament\Resources\LoteVacunas\Pages;

use App\Filament\Resources\LoteVacunas\LoteVacunaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoteVacuna extends EditRecord
{
    protected static string $resource = LoteVacunaResource::class;

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
