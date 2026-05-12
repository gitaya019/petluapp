<?php

namespace App\Filament\Resources\Citas\CopilotTools;

use App\Models\Cita;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class BuscarCitasMascotaTool extends BaseTool
{
    public function description(): string
    {
        return 'Busca citas veterinarias por nombre de mascota.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [

            'mascota' => $schema->string()
                ->description('Nombre de la mascota'),

        ];
    }

    public function handle(Request $request): string
    {
        $nombre = $request->input('mascota');

        $citas = Cita::query()

            ->with([
                'mascota',
                'veterinario',
                'vacuna',
            ])

            ->whereHas('mascota', function ($query) use ($nombre) {

                $query->where(
                    'nombre',
                    'like',
                    "%{$nombre}%"
                );
            })

            ->latest('fecha')

            ->get();

        if ($citas->isEmpty()) {

            return 'No se encontraron citas para esa mascota.';
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

Motivo:
{$cita->motivo}
";
        })->implode("\n====================\n");
    }
}
