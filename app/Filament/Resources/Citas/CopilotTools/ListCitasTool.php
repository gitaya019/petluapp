<?php

namespace App\Filament\Resources\Citas\CopilotTools;

use App\Models\Cita;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class ListCitasTool extends BaseTool
{
    public function description(): string
    {
        return 'Lista las citas veterinarias registradas.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }

    public function handle(Request $request): string
    {
        $citas = Cita::query()

            ->with([
                'mascota',
                'veterinario',
                'vacuna',
            ])

            ->latest('fecha')

            ->limit(20)

            ->get();

        if ($citas->isEmpty()) {

            return 'No hay citas registradas.';
        }

        return $citas->map(function ($cita) {

            $mascota = $cita->mascota?->nombre ?? 'Sin mascota';

            $veterinario = $cita->veterinario?->name ?? 'Sin veterinario';

            $vacuna = $cita->vacuna?->nombre ?? 'Sin vacuna';

            return "
Cita #{$cita->id}

Mascota:
{$mascota}

Veterinario:
{$veterinario}

Vacuna:
{$vacuna}

Fecha:
{$cita->fecha?->format('d/m/Y')}

Hora:
{$cita->hora}

Estado:
{$cita->estado}

Motivo:
{$cita->motivo}

Observaciones:
{$cita->observaciones}
";
        })->implode("\n====================\n");
    }
}
