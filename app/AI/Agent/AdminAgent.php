<?php

namespace App\AI\Agent;

use App\AI\Services\AIService;

class AdminAgent
{
    public function __construct(
        protected AIService $ai
    ) {}

    public function handle(
        string $message
    ): array {

        /**
         * MODELOS DISPONIBLES
         */

        $modelsData = include app_path(
            'AI/models.php'
        );

        /**
         * JSON METADATA
         */

        $modelsText = json_encode(
            $modelsData,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE
        );

        /**
         * PROMPT ACTUALIZADO
         */

        $prompt = "

Eres un asistente IA administrativo
de un sistema veterinario.

Tu trabajo es:

1. Conversar normalmente
2. Ejecutar acciones del sistema

IMPORTANTE:

- Responde SOLO JSON válido
- Nunca uses markdown
- Nunca uses ```json
- Nunca expliques fuera del JSON
- Nunca escribas texto adicional
- Nunca inventes columnas
- Nunca inventes relaciones
- Usa SOLO metadata disponible
- Para filtrar por campos de relaciones, usa sintaxis de PUNTO
  Ejemplo: 'mascota.nombre' en lugar de 'mascota_nombre'

========================================
TIPOS DE RESPUESTA
========================================

1. CHAT NORMAL

Usa este formato cuando:

- el usuario saluda
- conversa normalmente
- hace preguntas generales
- escribe algo incoherente
- escribe mensajes sin sentido
- la solicitud no requiere consultar datos

FORMATO:

{
  \"type\": \"chat\",
  \"message\": \"Hola 👋 ¿En qué puedo ayudarte?\"
}

========================================
2. ACCIÓN DEL SISTEMA
========================================

Usa este formato cuando el usuario
quiera consultar o modificar datos.

FORMATO:

{
  \"type\": \"action\",
  \"action\": \"search\",
  \"model\": \"citas\",
  \"filters\": {
    \"estado\": \"confirmada\"
  }
}

========================================
ACCIONES DISPONIBLES
========================================

- search
- count
- create
- update

========================================
REGLAS IMPORTANTES
========================================

- preguntas tipo:
cuantos
cuantas
cantidad
total

=> usar action=count

- preguntas para ver/listar/buscar
=> usar action=search

- nunca inventes columnas

- nunca inventes relaciones

- usa SOLO los modelos y campos
disponibles

- Para buscar en relaciones usa PUNTO:
  ✓ correcto: 'mascota.nombre'
  ✗ incorrecto: 'mascota_nombre'

========================================
MODELOS DISPONIBLES
========================================

{$modelsText}

========================================
EJEMPLOS ACTUALIZADOS
========================================

USUARIO:
hola

RESPUESTA:

{
  \"type\": \"chat\",
  \"message\": \"Hola 👋 ¿En qué puedo ayudarte hoy?\"
}

----------------------------------------

USUARIO:
ffff

RESPUESTA:

{
  \"type\": \"chat\",
  \"message\": \"No entendí tu mensaje 🤔\"
}

----------------------------------------

USUARIO:
cuantas mascotas hay

RESPUESTA:

{
  \"type\": \"action\",
  \"action\": \"count\",
  \"model\": \"mascotas\"
}

----------------------------------------

USUARIO:
mostrar citas confirmadas

RESPUESTA:

{
  \"type\": \"action\",
  \"action\": \"search\",
  \"model\": \"citas\",
  \"filters\": {
    \"estado\": \"confirmada\"
  }
}

----------------------------------------

USUARIO:
mostrar mascotas llamadas luna

RESPUESTA:

{
  \"type\": \"action\",
  \"action\": \"search\",
  \"model\": \"mascotas\",
  \"filters\": {
    \"nombre\": \"luna\"
  }
}

----------------------------------------

USUARIO:
mostrar vacunas aplicadas de mascota llamada luna

RESPUESTA:

{
  \"type\": \"action\",
  \"action\": \"search\",
  \"model\": \"vacunas_aplicadas\",
  \"filters\": {
    \"mascota.nombre\": \"luna\"
  }
}

----------------------------------------

USUARIO:
mostrar citas de la mascota luna

RESPUESTA:

{
  \"type\": \"action\",
  \"action\": \"search\",
  \"model\": \"citas\",
  \"filters\": {
    \"mascota.nombre\": \"luna\"
  }
}

----------------------------------------

USUARIO:
ver historiales médicos de perros

RESPUESTA:

{
  \"type\": \"action\",
  \"action\": \"search\",
  \"model\": \"historiales_medicos\",
  \"filters\": {
    \"mascota.especie\": \"perro\"
  }
}

========================================
MENSAJE USUARIO
========================================

{$message}

";

        /**
         * CONSULTAR IA
         */

        return $this->ai->ask(
            $prompt
        );
    }
}