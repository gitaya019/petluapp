<?php

namespace App\Filament\System\Resources\Clinicas\Pages;

use App\Filament\System\Resources\Clinicas\ClinicaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Role;
use App\Models\User;

class CreateClinica extends CreateRecord
{
    protected static string $resource = ClinicaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $user = Auth::user();

        $users = $this->data['users'] ?? [];
        $users[] = $user->id;
        $users = array_unique($users);

        $this->record->users()->sync($users);

        $registrar = app(\Spatie\Permission\PermissionRegistrar::class);

        // 🔥 IMPORTANTE: fijar tenant primero
        $registrar->setPermissionsTeamId($this->record->id);
        $registrar->forgetCachedPermissions();

        // 🔥 crear o actualizar rol
        $role = \App\Models\Role::updateOrCreate([
            'name' => 'super_admin',
            'clinica_id' => $this->record->id,
        ], [
            'guard_name' => 'web',
        ]);

        // 🔥 PERMISOS CORRECTOS
        $permissions = \Spatie\Permission\Models\Permission::all();

        $role->syncPermissions($permissions);

        foreach ($users as $userId) {
            $userModel = \App\Models\User::find($userId); //ignore error intelephense

            if (! $userModel) continue;

            $userModel->assignRole($role);
        }

        $registrar->forgetCachedPermissions();
    }
}
