<?php

namespace App\AI\Agent;

use App\AI\Services\GeminiService;

class AdminAgent
{
    public function __construct(
        protected GeminiService $gemini
    ) {}

    public function handle(
        string $message
    ): array {

        $modelsData = include app_path(
            'AI/models.php'
        );

        $modelsText = json_encode(
            $modelsData,
            JSON_PRETTY_PRINT |
                JSON_UNESCAPED_UNICODE
        );

        $prompt = "

Eres un agente administrativo IA
de un sistema veterinario.

IMPORTANTE:

- Responde SOLO JSON válido
- Nunca uses markdown
- Nunca uses ```json
- Nunca expliques
- Nunca inventes columnas
- Nunca inventes relaciones
- Usa SOLO metadata disponible

MODELOS DISPONIBLES:

{$modelsText}

ACCIONES DISPONIBLES:

1. search
2. count
3. create
4. update

REGLAS IMPORTANTES:

- Si el usuario pregunta:
  cuántos,
  cuantas,
  cantidad,
  total
  => usa action=count

- Si el usuario quiere ver información
  => usa action=search

- Puedes usar:
  relation_filters

EJEMPLO:

{
  \"action\": \"search\",
  \"model\": \"citas\",
  \"filters\": {
    \"estado\": \"confirmada\"
  },
  \"relation_filters\": {
    \"mascota\": {
      \"nombre\": \"luna\"
    }
  }
}

MENSAJE:
{$message}

";

        return $this->gemini->ask(
            $prompt
        );
    }
}
