<?php

namespace App\Filament\Resources\HistorialMedicos\CopilotTools;

use App\Models\HistorialMedico;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class BuscarHistorialMascotaTool extends BaseTool
{
    public function description(): string
    {
        return '
            Busca el historial médico completo
            de una mascota por nombre.
        ';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'nombre_mascota' => $schema->string()
                ->description('Nombre de la mascota')
                ->required(),
        ];
    }

    public function handle(Request $request): string
    {
        $nombreMascota = $request['nombre_mascota'];

        $historiales = HistorialMedico::query()
            ->with(['mascota', 'veterinario'])
            ->whereHas('mascota', function ($query) use ($nombreMascota) {

                $query->where(
                    'nombre',
                    'like',
                    "%{$nombreMascota}%"
                );

            })
            ->latest('fecha')
            ->get();

        if ($historiales->isEmpty()) {
            return "No se encontró historial médico para {$nombreMascota}.";
        }

        return $historiales->map(function ($historial) {

            $mascota = $historial->mascota?->nombre ?? 'Sin mascota';

            $veterinario = $historial->veterinario?->name ?? 'Sin veterinario';

            return "
Mascota: {$mascota}

Veterinario: {$veterinario}

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

        })->implode("\n====================\n");
    }
}