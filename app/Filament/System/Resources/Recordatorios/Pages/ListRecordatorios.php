<?php

namespace App\Filament\System\Resources\Recordatorios\Pages;

use App\Filament\System\Resources\Recordatorios\RecordatorioResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRecordatorios extends ListRecords
{
    protected static string $resource = RecordatorioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
