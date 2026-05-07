<?php

namespace App\Observers;

use App\Models\Clinica;
use EslamRedaDiv\FilamentCopilot\Models\CopilotConversation;
use Illuminate\Support\Facades\Auth;

class CopilotConversationObserver
{
    public function creating(CopilotConversation $conversation): void
    {
        // Obtener tenant de la request actual
        $tenantId = request()->route('tenant');
        
        // Si hay tenant en la ruta, asignarlo
        if ($tenantId) {
            $conversation->tenant_id = $tenantId;
            $conversation->tenant_type = Clinica::class;
            return;
        }
        
        // Si no hay tenant en ruta, intentar obtenerlo de la URL
        $referer = request()->header('referer');
        if ($referer && preg_match('#/clinica/(\d+)/#', $referer, $matches)) {
            $conversation->tenant_id = $matches[1];
            $conversation->tenant_type = Clinica::class;
            return;
        }
        
        // Último recurso: obtener el primer tenant del usuario
        if ($user = Auth::user()) {
            $clinica = $user->clinicas()->first();
            if ($clinica) {
                $conversation->tenant_id = $clinica->id;
                $conversation->tenant_type = Clinica::class;
            }
        }
    }
}