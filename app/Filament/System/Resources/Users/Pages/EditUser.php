<?php

namespace App\Filament\System\Resources\Users\Pages;

use App\Filament\System\Resources\Users\UserResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
            \Filament\Actions\ForceDeleteAction::make(),
            \Filament\Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function mutateFormDataBeforeFill(array $data): array
    {
        $roleIds = \Illuminate\Support\Facades\DB::table('model_has_roles')
            ->where('model_id', $this->record->id)
            ->pluck('role_id');

        $roles = \App\Models\Role::whereIn('id', $roleIds)->get(); //ignore this error, is a valid code. Intelephense bug

        $data['clinica_roles'] = $roles->map(fn($role) => [
            'clinica_id' => $role->clinica_id,
            'role_id' => $role->id,
        ])->values()->toArray();

        return $data;
    }


    protected function afterSave(): void
    {
        $registrar = app(\Spatie\Permission\PermissionRegistrar::class);

        // 1. Sync clínicas
        $clinicas = collect($this->data['clinica_roles'] ?? [])
            ->pluck('clinica_id')
            ->unique()
            ->toArray();

        $this->record->clinicas()->sync($clinicas);

        // 2. 🔥 LIMPIEZA REAL EN BD
        \DB::table('model_has_roles') //ignore this error, is a valid code. Intelephense bug
            ->where('model_id', $this->record->id)
            ->where('model_type', \App\Models\User::class)
            ->delete();

        // 3. Reasignar roles correctamente por clínica
        foreach ($this->data['clinica_roles'] ?? [] as $item) {

            $registrar->setPermissionsTeamId($item['clinica_id']);

            $role = \App\Models\Role::find($item['role_id']); //ignore this error, is a valid code. Intelephense bug

            if ($role) {
                $this->record->assignRole($role);
            }
        }

        // 4. limpiar cache
        $registrar->forgetCachedPermissions();
    }
}
