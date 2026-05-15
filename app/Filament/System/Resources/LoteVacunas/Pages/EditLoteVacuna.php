<?php

namespace App\Filament\System\Resources\LoteVacunas\Pages;

use App\Filament\System\Resources\LoteVacunas\LoteVacunaResource;
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
}
