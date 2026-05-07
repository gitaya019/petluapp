<?php

namespace App\Observers;

use App\Models\Clinica;
use EslamRedaDiv\FilamentCopilot\Models\CopilotRateLimit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CopilotRateLimitObserver
{
    public function creating(CopilotRateLimit $rateLimit): void
    {
        if ($rateLimit->tenant_id) {
            return;
        }

        // Método 1: tenant desde la ruta
        $tenantId = request()->route('tenant');
        if ($tenantId) {
            $rateLimit->tenant_id = $tenantId;
            $rateLimit->tenant_type = Clinica::class;
            Log::info('RateLimit Observer - Tenant asignado desde ruta', ['tenant_id' => $tenantId]);
            return;
        }

        // Método 2: desde el referer
        $referer = request()->header('referer');
        if ($referer && preg_match('#/clinica/(\d+)/#', $referer, $matches)) {
            $rateLimit->tenant_id = $matches[1];
            $rateLimit->tenant_type = Clinica::class;
            Log::info('RateLimit Observer - Tenant asignado desde referer', ['tenant_id' => $matches[1]]);
            return;
        }

        // Método 3: primer tenant del usuario (si existe participante)
        if ($user = Auth::user()) {
            $clinica = $user->clinicas()->first();
            if ($clinica) {
                $rateLimit->tenant_id = $clinica->id;
                $rateLimit->tenant_type = Clinica::class;
                Log::info('RateLimit Observer - Tenant asignado por fallback', ['tenant_id' => $clinica->id]);
            }
        }
    }
}