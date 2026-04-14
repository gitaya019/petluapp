<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Clinica;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClinicaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Clinica');
    }

    public function view(AuthUser $authUser, Clinica $clinica): bool
    {
        return $authUser->can('View:Clinica');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Clinica');
    }

    public function update(AuthUser $authUser, Clinica $clinica): bool
    {
        return $authUser->can('Update:Clinica');
    }

    public function delete(AuthUser $authUser, Clinica $clinica): bool
    {
        return $authUser->can('Delete:Clinica');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Clinica');
    }

    public function restore(AuthUser $authUser, Clinica $clinica): bool
    {
        return $authUser->can('Restore:Clinica');
    }

    public function forceDelete(AuthUser $authUser, Clinica $clinica): bool
    {
        return $authUser->can('ForceDelete:Clinica');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Clinica');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Clinica');
    }

    public function replicate(AuthUser $authUser, Clinica $clinica): bool
    {
        return $authUser->can('Replicate:Clinica');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Clinica');
    }

}