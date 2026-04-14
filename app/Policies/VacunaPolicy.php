<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Vacuna;
use Illuminate\Auth\Access\HandlesAuthorization;

class VacunaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Vacuna');
    }

    public function view(AuthUser $authUser, Vacuna $vacuna): bool
    {
        return $authUser->can('View:Vacuna');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Vacuna');
    }

    public function update(AuthUser $authUser, Vacuna $vacuna): bool
    {
        return $authUser->can('Update:Vacuna');
    }

    public function delete(AuthUser $authUser, Vacuna $vacuna): bool
    {
        return $authUser->can('Delete:Vacuna');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Vacuna');
    }

    public function restore(AuthUser $authUser, Vacuna $vacuna): bool
    {
        return $authUser->can('Restore:Vacuna');
    }

    public function forceDelete(AuthUser $authUser, Vacuna $vacuna): bool
    {
        return $authUser->can('ForceDelete:Vacuna');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Vacuna');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Vacuna');
    }

    public function replicate(AuthUser $authUser, Vacuna $vacuna): bool
    {
        return $authUser->can('Replicate:Vacuna');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Vacuna');
    }

}