<?php

namespace App\Observers;

use App\Models\Clinica;
use EslamRedaDiv\FilamentCopilot\Models\CopilotAuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CopilotAuditLogObserver
{
    public function creating(CopilotAuditLog $auditLog): void
    {
        // Si ya tiene tenant asignado (por el paquete), lo respetamos
        if ($auditLog->tenant_id) {
            return;
        }

        // Método 1: tenant desde la ruta actual
        $tenantId = request()->route('tenant');
        if ($tenantId) {
            $auditLog->tenant_id = $tenantId;
            $auditLog->tenant_type = Clinica::class;
            Log::info('AuditLog Observer - Tenant asignado desde ruta', ['tenant_id' => $tenantId]);
            return;
        }

        // Método 2: desde el referer
        $referer = request()->header('referer');
        if ($referer && preg_match('#/clinica/(\d+)/#', $referer, $matches)) {
            $auditLog->tenant_id = $matches[1];
            $auditLog->tenant_type = Clinica::class;
            Log::info('AuditLog Observer - Tenant asignado desde referer', ['tenant_id' => $matches[1]]);
            return;
        }

        // Método 3: desde la conversación asociada (si existe)
        if ($auditLog->conversation_id) {
            $conversation = \EslamRedaDiv\FilamentCopilot\Models\CopilotConversation::find($auditLog->conversation_id);
            if ($conversation && $conversation->tenant_id) {
                $auditLog->tenant_id = $conversation->tenant_id;
                $auditLog->tenant_type = $conversation->tenant_type;
                Log::info('AuditLog Observer - Tenant asignado desde conversación', ['tenant_id' => $conversation->tenant_id]);
                return;
            }
        }

        // Método 4: primer tenant del usuario autenticado
        if ($user = Auth::user()) {
            $clinica = $user->clinicas()->first();
            if ($clinica) {
                $auditLog->tenant_id = $clinica->id;
                $auditLog->tenant_type = Clinica::class;
                Log::info('AuditLog Observer - Tenant asignado por fallback', ['tenant_id' => $clinica->id]);
            }
        }
    }
}