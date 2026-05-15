<?php

namespace App\Filament\System\Resources\Recordatorios\Pages;

use App\Filament\System\Resources\Recordatorios\RecordatorioResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRecordatorio extends EditRecord
{
    protected static string $resource = RecordatorioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
