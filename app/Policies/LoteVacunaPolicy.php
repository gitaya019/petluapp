<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LoteVacuna;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoteVacunaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LoteVacuna');
    }

    public function view(AuthUser $authUser, LoteVacuna $loteVacuna): bool
    {
        return $authUser->can('View:LoteVacuna');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LoteVacuna');
    }

    public function update(AuthUser $authUser, LoteVacuna $loteVacuna): bool
    {
        return $authUser->can('Update:LoteVacuna');
    }

    public function delete(AuthUser $authUser, LoteVacuna $loteVacuna): bool
    {
        return $authUser->can('Delete:LoteVacuna');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LoteVacuna');
    }

    public function restore(AuthUser $authUser, LoteVacuna $loteVacuna): bool
    {
        return $authUser->can('Restore:LoteVacuna');
    }

    public function forceDelete(AuthUser $authUser, LoteVacuna $loteVacuna): bool
    {
        return $authUser->can('ForceDelete:LoteVacuna');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LoteVacuna');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LoteVacuna');
    }

    public function replicate(AuthUser $authUser, LoteVacuna $loteVacuna): bool
    {
        return $authUser->can('Replicate:LoteVacuna');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LoteVacuna');
    }

}