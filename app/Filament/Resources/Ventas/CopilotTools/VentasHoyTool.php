<?php

namespace App\Filament\Resources\Ventas\CopilotTools;

use App\Models\Venta;
use Carbon\Carbon;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class VentasHoyTool extends BaseTool
{
    public function description(): string
    {
        return 'Muestra ventas realizadas hoy.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }

    public function handle(Request $request): string
    {
        $ventas = Venta::query()
            ->whereDate('created_at', Carbon::today()) //ignore intelephense bug
            ->get();

        if ($ventas->isEmpty()) {
            return 'No hay ventas hoy.';
        }

        return $ventas->map(function ($venta) {
            return "
Venta #{$venta->id}
Total: {$venta->total}
";
        })->implode("\n-----------------\n");
    }
}