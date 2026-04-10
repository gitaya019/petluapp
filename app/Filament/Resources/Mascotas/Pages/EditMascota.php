<?php

namespace App\Filament\Resources\Mascotas\Pages;

use App\Filament\Resources\Mascotas\MascotaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMascota extends EditRecord
{
    protected static string $resource = MascotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
