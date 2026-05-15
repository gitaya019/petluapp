<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

use App\Models\Role;

use App\Observers\CopilotConversationObserver;
use App\Observers\CopilotAuditLogObserver;
use App\Observers\CopilotAgentMemoryObserver;
use App\Observers\CopilotRateLimitObserver;
use App\Observers\CopilotTokenUsageObserver;

use EslamRedaDiv\FilamentCopilot\Models\CopilotConversation;
use EslamRedaDiv\FilamentCopilot\Models\CopilotAuditLog;
use EslamRedaDiv\FilamentCopilot\Models\CopilotAgentMemory;
use EslamRedaDiv\FilamentCopilot\Models\CopilotRateLimit;
use EslamRedaDiv\FilamentCopilot\Models\CopilotTokenUsage;

use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app(PermissionRegistrar::class)
            ->setPermissionClass(Permission::class)
            ->setRoleClass(Role::class);


        Gate::before(function ($user, $ability) {
            return $user->isSuperAdmin() ? true : null;
        });

        CopilotConversation::observe(CopilotConversationObserver::class);
        CopilotAuditLog::observe(CopilotAuditLogObserver::class);
        CopilotAgentMemory::observe(CopilotAgentMemoryObserver::class);
        CopilotRateLimit::observe(CopilotRateLimitObserver::class);
        CopilotTokenUsage::observe(CopilotTokenUsageObserver::class);

        Health::checks([
            OptimizedAppCheck::new(),
            DebugModeCheck::new(),
            EnvironmentCheck::new(),
        ]);
    }
}
