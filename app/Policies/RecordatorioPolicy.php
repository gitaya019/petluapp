<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Recordatorio;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecordatorioPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Recordatorio');
    }

    public function view(AuthUser $authUser, Recordatorio $recordatorio): bool
    {
        return $authUser->can('View:Recordatorio');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Recordatorio');
    }

    public function update(AuthUser $authUser, Recordatorio $recordatorio): bool
    {
        return $authUser->can('Update:Recordatorio');
    }

    public function delete(AuthUser $authUser, Recordatorio $recordatorio): bool
    {
        return $authUser->can('Delete:Recordatorio');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Recordatorio');
    }

    public function restore(AuthUser $authUser, Recordatorio $recordatorio): bool
    {
        return $authUser->can('Restore:Recordatorio');
    }

    public function forceDelete(AuthUser $authUser, Recordatorio $recordatorio): bool
    {
        return $authUser->can('ForceDelete:Recordatorio');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Recordatorio');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Recordatorio');
    }

    public function replicate(AuthUser $authUser, Recordatorio $recordatorio): bool
    {
        return $authUser->can('Replicate:Recordatorio');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Recordatorio');
    }

}