<?php

namespace App\Filament\System\Resources\Clinicas\Pages;

use App\Filament\System\Resources\Clinicas\ClinicaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class EditClinica extends EditRecord
{
    protected static string $resource = ClinicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $authUser = Auth::user();

        $users = $this->data['users'] ?? [];
        $users[] = $authUser->id;
        $users = array_unique($users);

        $this->record->users()->sync($users);

        $role = \App\Models\Role::where('name', 'super_admin')
            ->where('clinica_id', $this->record->id)
            ->first();

        if (! $role) return;

        \DB::transaction(function () use ($users, $role) {

            \DB::table('model_has_roles')
                ->where('model_type', \App\Models\User::class)
                ->where('clinica_id', $this->record->id)
                ->delete();

            foreach ($users as $userId) {
                \DB::table('model_has_roles')->updateOrInsert(
                    [
                        'role_id' => $role->id,
                        'model_type' => \App\Models\User::class,
                        'model_id' => $userId,
                        'clinica_id' => $this->record->id,
                    ],
                    []
                );
            }
        });
    }
}
