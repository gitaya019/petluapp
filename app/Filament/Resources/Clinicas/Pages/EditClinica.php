<?php

namespace App\Filament\Resources\Clinicas\Pages;

use App\Filament\Resources\Clinicas\ClinicaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClinica extends EditRecord
{
    protected static string $resource = ClinicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
