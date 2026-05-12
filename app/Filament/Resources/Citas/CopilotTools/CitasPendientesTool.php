<?php

namespace App\Filament\Resources\Citas\CopilotTools;

use App\Models\Cita;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class CitasPendientesTool extends BaseTool
{
    public function description(): string
    {
        return 'Lista citas pendientes o confirmadas.';
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
            ])

            ->whereIn('estado', [
                'pendiente',
                'confirmada',
            ])

            ->orderBy('fecha')

            ->orderBy('hora')

            ->get();

        if ($citas->isEmpty()) {

            return 'No hay citas pendientes.';
        }

        return $citas->map(function ($cita) {

            return "
Cita #{$cita->id}

Mascota:
{$cita->mascota?->nombre}

Veterinario:
{$cita->veterinario?->name}

Fecha:
{$cita->fecha?->format('d/m/Y')}

Hora:
{$cita->hora}

Estado:
{$cita->estado}
";
        })->implode("\n====================\n");
    }
}