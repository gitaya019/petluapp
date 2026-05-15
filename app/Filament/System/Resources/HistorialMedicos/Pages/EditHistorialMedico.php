<?php

namespace App\Filament\System\Resources\HistorialMedicos\Pages;

use App\Filament\System\Resources\HistorialMedicos\HistorialMedicoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditHistorialMedico extends EditRecord
{
    protected static string $resource = HistorialMedicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
