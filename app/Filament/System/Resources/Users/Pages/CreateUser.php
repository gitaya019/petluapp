<?php

namespace App\Filament\System\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $tenant = Filament::getTenant();

        $this->record->clinicas()->syncWithoutDetaching([
            $tenant->id
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
