<?php

namespace App\Filament\Resources\Vacunas\Pages;

use App\Filament\Resources\Vacunas\VacunaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVacuna extends EditRecord
{
    protected static string $resource = VacunaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
