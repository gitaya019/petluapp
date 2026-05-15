<?php

namespace App\Filament\System\Resources\Vacunas\Pages;

use App\Filament\System\Resources\Vacunas\VacunaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditVacuna extends EditRecord
{
    protected static string $resource = VacunaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
