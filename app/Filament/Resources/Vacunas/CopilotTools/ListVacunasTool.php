<?php

namespace App\Filament\Resources\Vacunas\CopilotTools;

use App\Models\Vacuna;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class ListVacunasTool extends BaseTool
{
    public function description(): string
    {
        return 'Lista vacunas registradas.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }

    public function handle(Request $request): string
    {
        $vacunas = Vacuna::latest()->get(); //ignore this intelephense error

        return $vacunas->map(function ($vacuna) {
            return "
Nombre: {$vacuna->nombre}
Stock: {$vacuna->stock}
Vence: {$vacuna->fecha_vencimiento}
";
        })->implode("\n-----------------\n");
    }
}