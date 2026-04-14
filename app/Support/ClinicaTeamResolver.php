<?php

namespace App\Support;

use Spatie\Permission\Contracts\PermissionsTeamResolver;

class ClinicaTeamResolver implements PermissionsTeamResolver
{
    protected ?int $teamId = null;

    public function getPermissionsTeamId(): ?int
    {
        // 1. Si se seteó manualmente (CLI)
        if ($this->teamId !== null) {
            return $this->teamId;
        }

        // 2. Si estamos en Filament (web)
        if (function_exists('filament') && filament()->getTenant()) {
            return filament()->getTenant()->id;
        }

        return null;
    }

    public function setPermissionsTeamId($id): void
    {
        $this->teamId = $id;
    }
}