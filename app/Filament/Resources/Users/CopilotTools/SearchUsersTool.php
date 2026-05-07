<?php

namespace App\Filament\Resources\Users\CopilotTools;

use App\Models\User;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class SearchUsersTool extends BaseTool
{
    public function description(): string
    {
        return 'Busca usuarios por nombre.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'nombre' => $schema->string()
                ->required(),
        ];
    }

    public function handle(Request $request): string
    {
        $users = User::query()
            ->where('name', 'like', '%' . $request['nombre'] . '%')
            ->get();

        if ($users->isEmpty()) {
            return 'No se encontraron usuarios.';
        }

        return $users->map(function ($user) {
            return "
Nombre: {$user->name}
Email: {$user->email}
";
        })->implode("\n-----------------\n");
    }
}