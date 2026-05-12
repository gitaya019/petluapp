<?php

namespace App\Filament\Resources\Citas\CopilotTools;

use App\Models\Cita;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class ViewCitaTool extends BaseTool
{
    public function description(): string
    {
        return 'Muestra información detallada de una cita.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [

            'id' => $schema->integer()
                ->description('ID de la cita'),

        ];
    }

    public function handle(Request $request): string
    {
        $id = $request->input('id');

        $cita = Cita::query()

            ->with([
                'mascota',
                'veterinario',
                'vacuna',
            ])

            ->find($id);

        if (! $cita) {

            return 'La cita no existe.';
        }

        return "
Cita #{$cita->id}

Mascota:
{$cita->mascota?->nombre}

Veterinario:
{$cita->veterinario?->name}

Vacuna:
{$cita->vacuna?->nombre}

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

Creada:
{$cita->created_at}
";
    }
}
