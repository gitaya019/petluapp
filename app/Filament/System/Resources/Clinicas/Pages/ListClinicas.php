<?php

namespace App\Filament\System\Resources\Clinicas\Pages;

use App\Filament\System\Resources\Clinicas\ClinicaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClinicas extends ListRecords
{
    protected static string $resource = ClinicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
