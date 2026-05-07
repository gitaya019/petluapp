<?php

namespace App\Filament\Resources\HistorialMedicos\CopilotTools;

use App\Models\HistorialMedico;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class ListHistorialMedicosTool extends BaseTool
{
    public function description(): string
    {
        return 'Lista historiales médicos veterinarios.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }

    public function handle(Request $request): string
    {
        $historiales = HistorialMedico::query()
            ->with(['mascota', 'veterinario'])
            ->latest('fecha')
            ->limit(20)
            ->get();

        if ($historiales->isEmpty()) {
            return 'No hay historiales médicos registrados.';
        }

        return $historiales->map(function ($historial) {

            $mascota = $historial->mascota?->nombre ?? 'Sin mascota';

            $veterinario = $historial->veterinario?->name ?? 'Sin veterinario';

            return "
Historial #{$historial->id}

Mascota: {$mascota}

Veterinario: {$veterinario}

Motivo:
{$historial->motivo_consulta}

Diagnóstico:
{$historial->diagnostico}

Tratamiento:
{$historial->tratamiento}

Fecha:
{$historial->fecha}
";
        })->implode("\n====================\n");
    }
}
