<?php

namespace App\Filament\Resources\Clinicas\Pages;

use App\Filament\Resources\Clinicas\ClinicaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateClinica extends CreateRecord
{
    protected static string $resource = ClinicaResource::class;

    protected function afterCreate(): void
    {
        $user = Auth::user();

        // Usuarios seleccionados en el form
        $users = $this->data['users'] ?? [];

        // Agregar también el usuario actual
        $users[] = $user->id;

        // Sync sin duplicar
        $this->record->users()->sync(array_unique($users));
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
