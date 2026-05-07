<?php

namespace App\Filament\Resources\Ventas\CopilotTools;

use App\Models\Venta;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class ListVentasTool extends BaseTool
{
    public function description(): string
    {
        return 'Lista ventas registradas.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }

    public function handle(Request $request): string
    {
        $ventas = Venta::latest()->limit(20)->get(); //ignore intelephense bug

        return $ventas->map(function ($venta) {
            return "
Venta #{$venta->id}
Total: {$venta->total}
Fecha: {$venta->created_at}
";
        })->implode("\n-----------------\n");
    }
}