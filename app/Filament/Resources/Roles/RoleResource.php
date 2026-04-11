<?php

namespace App\Filament\Resources;

use App\Models\Role;
use BezhanSalleh\FilamentShield\Resources\Roles\RoleResource as ShieldRoleResource;

class RoleResource extends ShieldRoleResource
{
    protected static ?string $model = Role::class;

    // 🔥 ESTA LÍNEA SOLUCIONA TODO
    protected static ?string $tenantOwnershipRelationshipName = null;
}