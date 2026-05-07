<?php

namespace App\Filament\Resources\Mascotas\CopilotTools;

use App\Models\Mascota;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class ViewMascotaTool extends BaseTool
{
    public function description(): string
    {
        return 'Muestra información detallada de una mascota.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('ID de la mascota')
                ->required(),
        ];
    }

    public function handle(Request $request): string
    {
        $mascota = Mascota::find($request['id']); //ignore this error intelephense bug

        if (!$mascota) {
            return 'Mascota no encontrada.';
        }

        return "
ID: {$mascota->id}
Nombre: {$mascota->nombre}
Especie: {$mascota->especie}
Raza: {$mascota->raza}
Sexo: {$mascota->sexo}
Peso: {$mascota->peso}
";
    }
}