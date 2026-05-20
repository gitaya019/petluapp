<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

use App\AI\Agent\AdminAgent;
use App\AI\Actions\ExecuteAction;
use App\AI\Services\ResultFormatter;

class AiCopilot extends Page
{
    protected string $view =
        'filament.pages.ai-copilot';

    protected static string|\BackedEnum|null $navigationIcon =
        'heroicon-o-sparkles';

    protected static ?string $navigationLabel =
        'AI Copilot';

    protected static ?string $title =
        'AI Copilot';

    public string $message = '';

    public array $messages = [];

    public bool $loading = false;

    public function send(
        AdminAgent $agent,
        ExecuteAction $executor,
        ResultFormatter $formatter
    ): void {

        /**
         * VALIDAR INPUT
         */

        if (!trim($this->message)) {
            return;
        }

        $userMessage = trim(
            $this->message
        );

        /**
         * MENSAJE USER
         */

        $this->messages[] = [

            'type' => 'user',

            'content' => $userMessage,
        ];

        /**
         * LOADING
         */

        $this->loading = true;

        /**
         * LIMPIAR INPUT
         */

        $this->message = '';

        /**
         * IA
         */

        $aiResponse =
            $agent->handle(
                $userMessage
            );

        /**
         * EXTRAER TEXTO OLLAMA
         */

        $text =
            $aiResponse['response']
            ?? '';

        /**
         * LIMPIAR MARKDOWN
         */

        $text = str_replace(
            ['```json', '```'],
            '',
            $text
        );

        $text = trim($text);

        /**
         * JSON
         */

        $json = json_decode(
            $text,
            true
        );

        /**
         * JSON INVÁLIDO
         */

        if (!$json) {

            $this->messages[] = [

                'type' => 'error',

                'content' =>
                    "JSON inválido:\n\n" .
                    $text,
            ];

            $this->loading = false;

            return;
        }

        /**
         * CHAT NORMAL
         */

        if (
            ($json['type'] ?? null)
            === 'chat'
        ) {

            $this->messages[] = [

                'type' => 'assistant',

                'content' =>
                    $json['message']
                    ?? 'Hola 👋',
            ];

            $this->loading = false;

            return;
        }

        /**
         * VALIDAR TYPE
         */

        if (
            ($json['type'] ?? null)
            !== 'action'
        ) {

            $this->messages[] = [

                'type' => 'assistant',

                'content' =>
                    'No entendí la solicitud 🤔',
            ];

            $this->loading = false;

            return;
        }

        /**
         * VALIDAR ACCIONES
         */

        $allowedActions = [

            'search',
            'count',
            'create',
            'update',
        ];

        if (
            !in_array(
                $json['action'] ?? '',
                $allowedActions
            )
        ) {

            $this->messages[] = [

                'type' => 'assistant',

                'content' =>
                    'Acción inválida 🤔',
            ];

            $this->loading = false;

            return;
        }

        /**
         * VALIDAR MODELOS
         */

        $models = array_keys(
            include app_path(
                'AI/models.php'
            )
        );

        if (
            !in_array(
                $json['model'] ?? '',
                $models
            )
        ) {

            $this->messages[] = [

                'type' => 'assistant',

                'content' =>
                    'No entendí la solicitud 🤔',
            ];

            $this->loading = false;

            return;
        }

        /**
         * EJECUTAR ACCIÓN
         */

        $result =
            $executor->run(
                $json
            );

        /**
         * FORMATEAR
         */

        $formatted =
            $formatter->format(
                $result
            );

        /**
         * RESPUESTA IA
         */

        $this->messages[] = [

            'type' => 'assistant',

            'content' => $formatted,
        ];

        /**
         * FINALIZAR
         */

        $this->loading = false;
    }

    /**
     * Enviar una sugerencia predefinida
     */
    public function sendSuggestion(string $suggestion): void
    {
        $this->message = $suggestion;
        $this->send(
            app(AdminAgent::class), 
            app(ExecuteAction::class), 
            app(ResultFormatter::class)
        );
    }
}