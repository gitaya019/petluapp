<?php

namespace App\Filament\System\Resources\Users\Pages;

use App\Filament\System\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;


class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function afterCreate(): void
    {
        foreach ($this->data['clinica_roles'] ?? [] as $item) {

            $clinicaId = $item['clinica_id'];
            $roleId = $item['role_id'];

            // 1. identificador (TENANTS)
            $this->record->clinicas()->syncWithoutDetaching([$clinicaId]);

            // 2. roles (Spatie)
            $registrar = app(\Spatie\Permission\PermissionRegistrar::class);
            $registrar->setPermissionsTeamId($clinicaId);

            $role = \App\Models\Role::find($roleId);

            if ($role) {
                $this->record->assignRole($role);
            }
        }
    }
}
