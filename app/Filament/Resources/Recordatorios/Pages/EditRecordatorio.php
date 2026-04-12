<?php

namespace App\Filament\Resources\Recordatorios\Pages;

use App\Filament\Resources\Recordatorios\RecordatorioResource;
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
