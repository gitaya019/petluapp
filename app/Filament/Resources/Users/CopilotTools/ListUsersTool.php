<?php

namespace App\Filament\Resources\Users\CopilotTools;

use App\Models\User;
use EslamRedaDiv\FilamentCopilot\Tools\BaseTool;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;

class ListUsersTool extends BaseTool
{
    public function description(): string
    {
        return 'Lista usuarios del sistema.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }

    public function handle(Request $request): string
    {
        $users = User::limit(20)->get();

        return $users->map(function ($user) {
            return "
ID: {$user->id}
Nombre: {$user->name}
Email: {$user->email}
";
        })->implode("\n-----------------\n");
    }
}