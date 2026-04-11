<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Mascota;
use Illuminate\Auth\Access\HandlesAuthorization;

class MascotaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Mascota');
    }

    public function view(AuthUser $authUser, Mascota $mascota): bool
    {
        return $authUser->can('View:Mascota');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Mascota');
    }

    public function update(AuthUser $authUser, Mascota $mascota): bool
    {
        return $authUser->can('Update:Mascota');
    }

    public function delete(AuthUser $authUser, Mascota $mascota): bool
    {
        return $authUser->can('Delete:Mascota');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Mascota');
    }

    public function restore(AuthUser $authUser, Mascota $mascota): bool
    {
        return $authUser->can('Restore:Mascota');
    }

    public function forceDelete(AuthUser $authUser, Mascota $mascota): bool
    {
        return $authUser->can('ForceDelete:Mascota');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Mascota');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Mascota');
    }

    public function replicate(AuthUser $authUser, Mascota $mascota): bool
    {
        return $authUser->can('Replicate:Mascota');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Mascota');
    }

}