<?php

namespace App\Filament\Resources\Citas\CopilotTools;

use App\Models\Cita;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class CitasHoyTool extends BaseTool
{
    public function description(): string
    {
        return 'Lista las citas programadas para hoy.';
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

            ->whereDate('fecha', today())

            ->orderBy('hora')

            ->get();

        if ($citas->isEmpty()) {

            return 'No hay citas para hoy.';
        }

        return $citas->map(function ($cita) {

            return "
Mascota:
{$cita->mascota?->nombre}

Veterinario:
{$cita->veterinario?->name}

Hora:
{$cita->hora}

Estado:
{$cita->estado}
";
        })->implode("\n====================\n");
    }
}