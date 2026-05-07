<?php

namespace App\Observers;

use App\Models\Clinica;
use EslamRedaDiv\FilamentCopilot\Models\CopilotAgentMemory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CopilotAgentMemoryObserver
{
    public function creating(CopilotAgentMemory $memory): void
    {
        // Si ya tiene tenant, no hacer nada
        if ($memory->tenant_id) {
            return;
        }

        // Método 1: tenant desde la ruta
        $tenantId = request()->route('tenant');
        if ($tenantId) {
            $memory->tenant_id = $tenantId;
            $memory->tenant_type = Clinica::class;
            Log::info('AgentMemory Observer - Tenant asignado desde ruta', ['tenant_id' => $tenantId]);
            return;
        }

        // Método 2: desde el referer
        $referer = request()->header('referer');
        if ($referer && preg_match('#/clinica/(\d+)/#', $referer, $matches)) {
            $memory->tenant_id = $matches[1];
            $memory->tenant_type = Clinica::class;
            Log::info('AgentMemory Observer - Tenant asignado desde referer', ['tenant_id' => $matches[1]]);
            return;
        }

        // Método 3: primer tenant del usuario autenticado
        if ($user = Auth::user()) {
            $clinica = $user->clinicas()->first();
            if ($clinica) {
                $memory->tenant_id = $clinica->id;
                $memory->tenant_type = Clinica::class;
                Log::info('AgentMemory Observer - Tenant asignado por fallback', ['tenant_id' => $clinica->id]);
            }
        }
    }
}
