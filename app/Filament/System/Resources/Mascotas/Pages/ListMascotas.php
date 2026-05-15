<?php

namespace App\Filament\System\Resources\Mascotas\Pages;

use App\Filament\System\Resources\Mascotas\MascotaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMascotas extends ListRecords
{
    protected static string $resource = MascotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
