<?php

namespace App\Filament\System\Resources\Clinicas\Pages;

use App\Filament\Resources\Clinicas\ClinicaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Pages\Dashboard;

class EditClinica extends EditRecord
{
    protected static string $resource = ClinicaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function afterSave(): void
    {
        $user = Auth::user();

        $users = $this->data['users'] ?? [];

        // evitar que se quite a sí mismo (opcional pero recomendado)
        $users[] = $user->id;

        $this->record->users()->sync(array_unique($users));
    }

    protected function getRedirectUrl(): string
    {
        return Dashboard::getUrl();
    }
}
