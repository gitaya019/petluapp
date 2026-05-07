<?php

namespace App\Filament\Resources\Mascotas\CopilotTools;

use App\Models\Mascota;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class ListMascotasTool extends BaseTool
{
    public function description(): string
    {
        return 'Lista las mascotas registradas.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }

    public function handle(Request $request): string
    {
        $mascotas = Mascota::latest() //ignore bug intelephense
            ->limit(20)
            ->get();

        if ($mascotas->isEmpty()) {
            return 'No hay mascotas registradas.';
        }

        return $mascotas->map(function ($mascota) {
            return "
ID: {$mascota->id}
Nombre: {$mascota->nombre}
Especie: {$mascota->especie}
Raza: {$mascota->raza}
";
        })->implode("\n-----------------\n");
    }
}
