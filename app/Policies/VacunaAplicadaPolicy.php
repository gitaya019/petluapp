<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\VacunaAplicada;
use Illuminate\Auth\Access\HandlesAuthorization;

class VacunaAplicadaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:VacunaAplicada');
    }

    public function view(AuthUser $authUser, VacunaAplicada $vacunaAplicada): bool
    {
        return $authUser->can('View:VacunaAplicada');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:VacunaAplicada');
    }

    public function update(AuthUser $authUser, VacunaAplicada $vacunaAplicada): bool
    {
        return $authUser->can('Update:VacunaAplicada');
    }

    public function delete(AuthUser $authUser, VacunaAplicada $vacunaAplicada): bool
    {
        return $authUser->can('Delete:VacunaAplicada');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:VacunaAplicada');
    }

    public function restore(AuthUser $authUser, VacunaAplicada $vacunaAplicada): bool
    {
        return $authUser->can('Restore:VacunaAplicada');
    }

    public function forceDelete(AuthUser $authUser, VacunaAplicada $vacunaAplicada): bool
    {
        return $authUser->can('ForceDelete:VacunaAplicada');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:VacunaAplicada');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:VacunaAplicada');
    }

    public function replicate(AuthUser $authUser, VacunaAplicada $vacunaAplicada): bool
    {
        return $authUser->can('Replicate:VacunaAplicada');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:VacunaAplicada');
    }

}