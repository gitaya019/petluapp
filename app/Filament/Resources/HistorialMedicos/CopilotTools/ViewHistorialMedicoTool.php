<?php

namespace App\Filament\Resources\HistorialMedicos\CopilotTools;

use App\Models\HistorialMedico;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class ViewHistorialMedicoTool extends BaseTool
{
    public function description(): string
    {
        return 'Muestra un historial médico específico por ID.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('ID del historial médico')
                ->required(),
        ];
    }

    public function handle(Request $request): string
    {
        $historial = HistorialMedico::query()
            ->with(['mascota', 'veterinario'])
            ->find($request['id']);

        if (!$historial) {
            return 'Historial médico no encontrado.';
        }

        return "
Historial #{$historial->id}

Mascota:
{$historial->mascota?->nombre}

Veterinario:
{$historial->veterinario?->name}

Fecha:
{$historial->fecha}

Motivo Consulta:
{$historial->motivo_consulta}

Diagnóstico:
{$historial->diagnostico}

Tratamiento:
{$historial->tratamiento}

Observaciones:
{$historial->observaciones}
";
    }
}