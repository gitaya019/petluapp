<?php

namespace App\Filament\System\Resources\Clinicas\Pages;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\ForceDeleteAction;
use Spatie\Permission\PermissionRegistrar;
use App\Filament\System\Resources\Clinicas\ClinicaResource;

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

        // =====================================================
        // SINCRONIZAR USUARIOS
        // =====================================================

        $this->record->users()->sync($users);

        // =====================================================
        // CONFIGURAR TEAM
        // =====================================================

        $registrar = app(PermissionRegistrar::class);

        $registrar->setPermissionsTeamId(
            $this->record->id
        );

        $registrar->forgetCachedPermissions();

        // =====================================================
        // OBTENER ROL SUPER ADMIN
        // =====================================================

        $role = Role::query()

            ->where('name', 'super_admin')

            ->where('clinica_id', $this->record->id)

            ->first();

        if (! $role) {
            return;
        }

        // =====================================================
        // ACTUALIZAR ROLES
        // =====================================================

        DB::transaction(function () use ($users, $role) {

            DB::table('model_has_roles')

                ->where(
                    'model_type',
                    User::class
                )

                ->where(
                    'clinica_id',
                    $this->record->id
                )

                ->where(
                    'role_id',
                    $role->id
                )

                ->delete();

            foreach ($users as $userId) {

                DB::table('model_has_roles')

                    ->updateOrInsert(

                        [
                            'role_id' => $role->id,

                            'model_type' => User::class,

                            'model_id' => $userId,

                            'clinica_id' => $this->record->id,
                        ],

                        []
                    );
            }
        });

        // =====================================================
        // LIMPIAR CACHE
        // =====================================================

        $registrar->forgetCachedPermissions();
    }
}