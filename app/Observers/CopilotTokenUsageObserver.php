<?php

namespace App\Observers;

use App\Models\Clinica;
use EslamRedaDiv\FilamentCopilot\Models\CopilotTokenUsage;
use EslamRedaDiv\FilamentCopilot\Models\CopilotConversation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CopilotTokenUsageObserver
{
    public function creating(CopilotTokenUsage $tokenUsage): void
    {
        if ($tokenUsage->tenant_id) {
            return;
        }

        // Método 1: tenant desde la conversación asociada (si existe)
        if ($tokenUsage->conversation_id) {
            $conversation = CopilotConversation::find($tokenUsage->conversation_id);
            if ($conversation && $conversation->tenant_id) {
                $tokenUsage->tenant_id = $conversation->tenant_id;
                $tokenUsage->tenant_type = $conversation->tenant_type;
                Log::info('TokenUsage Observer - Tenant asignado desde conversación', ['tenant_id' => $conversation->tenant_id]);
                return;
            }
        }

        // Método 2: tenant desde ruta
        $tenantId = request()->route('tenant');
        if ($tenantId) {
            $tokenUsage->tenant_id = $tenantId;
            $tokenUsage->tenant_type = Clinica::class;
            Log::info('TokenUsage Observer - Tenant asignado desde ruta', ['tenant_id' => $tenantId]);
            return;
        }

        // Método 3: desde el referer
        $referer = request()->header('referer');
        if ($referer && preg_match('#/clinica/(\d+)/#', $referer, $matches)) {
            $tokenUsage->tenant_id = $matches[1];
            $tokenUsage->tenant_type = Clinica::class;
            Log::info('TokenUsage Observer - Tenant asignado desde referer', ['tenant_id' => $matches[1]]);
            return;
        }

        // Método 4: primer tenant del usuario
        if ($user = Auth::user()) {
            $clinica = $user->clinicas()->first();
            if ($clinica) {
                $tokenUsage->tenant_id = $clinica->id;
                $tokenUsage->tenant_type = Clinica::class;
                Log::info('TokenUsage Observer - Tenant asignado por fallback', ['tenant_id' => $clinica->id]);
            }
        }
    }
}