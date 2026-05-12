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

        $registrar = app(PermissionRegistrar::class);

        // =====================================================
        // CONFIGURAR TENANT
        // =====================================================

        $registrar->setPermissionsTeamId($this->record->id);

        $registrar->forgetCachedPermissions();

        // =====================================================
        // SUPER ADMIN
        // =====================================================

        $superAdminRole = Role::updateOrCreate(
            [
                'name' => 'super_admin',
                'clinica_id' => $this->record->id,
            ],
            [
                'guard_name' => 'web',
            ]
        );

        $superAdminRole->syncPermissions(
            Permission::all()
        );

        // =====================================================
        // VETERINARIO
        // =====================================================

        $veterinarioRole = Role::updateOrCreate(
            [
                'name' => 'Veterinario',
                'clinica_id' => $this->record->id,
            ],
            [
                'guard_name' => 'web',
            ]
        );

        $veterinarioRole->syncPermissions([

            // Mascotas
            'ViewAny:Mascota',
            'View:Mascota',
            'Create:Mascota',
            'Update:Mascota',

            // Historial Médico
            'ViewAny:HistorialMedico',
            'View:HistorialMedico',
            'Create:HistorialMedico',
            'Update:HistorialMedico',

            // Vacunas
            'ViewAny:Vacuna',
            'View:Vacuna',

            // Vacunas Aplicadas
            'ViewAny:VacunaAplicada',
            'View:VacunaAplicada',
            'Create:VacunaAplicada',
            'Update:VacunaAplicada',

            // Citas
            'ViewAny:Cita',
            'View:Cita',
            'Create:Cita',
            'Update:Cita',

            // Recordatorios
            'ViewAny:Recordatorio',
            'View:Recordatorio',
        ]);

        // =====================================================
        // USUARIO
        // =====================================================

        $usuarioRole = Role::updateOrCreate(
            [
                'name' => 'Usuario',
                'clinica_id' => $this->record->id,
            ],
            [
                'guard_name' => 'web',
            ]
        );

        $usuarioRole->syncPermissions([

            // Mascotas
            'ViewAny:Mascota',
            'View:Mascota',

            // Citas
            'ViewAny:Cita',
            'View:Cita',

            // Recordatorios
            'ViewAny:Recordatorio',
            'View:Recordatorio',
        ]);

        // =====================================================
        // ASIGNAR SUPER ADMIN
        // =====================================================

        foreach ($users as $userId) {

            $userModel = User::find($userId);

            if (! $userModel) {
                continue;
            }

            $userModel->assignRole($superAdminRole);
        }

        // =====================================================
        // LIMPIAR CACHE
        // =====================================================

        $registrar->forgetCachedPermissions();
    }
}
