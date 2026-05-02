<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;
use Filament\Facades\Filament;
use Spatie\Permission\PermissionRegistrar;

class SetPermissionsTeam
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $tenant = Filament::getTenant();

        if ($tenant) {
            $permissionRegistrar = app(PermissionRegistrar::class);

            $permissionRegistrar->setPermissionsTeamId($tenant->id);
            $permissionRegistrar->forgetCachedPermissions();
        }

        return $next($request);
    }
}
