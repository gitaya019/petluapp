<?php

namespace App\Filament\Resources\Vacunas\CopilotTools;

use App\Models\Vacuna;
use Carbon\Carbon;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class VacunasVencidasTool extends BaseTool
{
    public function description(): string
    {
        return 'Muestra vacunas vencidas o próximas a vencer.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }

    public function handle(Request $request): string
    {
        $vacunas = Vacuna::query()
            ->whereDate('fecha_vencimiento', '<=', Carbon::now()->addDays(30)) //ignore intelephense bug
            ->get();

        if ($vacunas->isEmpty()) {
            return 'No hay vacunas vencidas.';
        }

        return $vacunas->map(function ($vacuna) {
            return "
Vacuna: {$vacuna->nombre}
Vence: {$vacuna->fecha_vencimiento}
";
        })->implode("\n-----------------\n");
    }
}