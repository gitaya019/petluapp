<?php

namespace App\Filament\System\Resources\VacunaAplicadas\Pages;

use App\Filament\System\Resources\VacunaAplicadas\VacunaAplicadaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditVacunaAplicada extends EditRecord
{
    protected static string $resource = VacunaAplicadaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
