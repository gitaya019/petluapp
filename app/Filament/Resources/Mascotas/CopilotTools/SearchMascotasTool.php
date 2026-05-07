<?php

namespace App\Filament\Resources\Mascotas\CopilotTools;

use App\Models\Mascota;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class SearchMascotasTool extends BaseTool
{
    public function description(): string
    {
        return 'Busca mascotas por nombre.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'nombre' => $schema->string()
                ->description('Nombre de la mascota')
                ->required(),
        ];
    }

    public function handle(Request $request): string
    {
        $nombre = $request['nombre'];

        $mascotas = Mascota::query()
            ->where('nombre', 'like', "%{$nombre}%")
            ->get();

        if ($mascotas->isEmpty()) {
            return 'No se encontraron mascotas.';
        }

        return $mascotas->map(function ($mascota) {
            return "
ID: {$mascota->id}
Nombre: {$mascota->nombre}
Especie: {$mascota->especie}
";
        })->implode("\n-----------------\n");
    }
}
