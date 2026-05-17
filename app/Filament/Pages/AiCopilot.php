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

    public string $message = '';

    public string $response = '';

    public function send(
        AdminAgent $agent,
        ExecuteAction $executor,
        ResultFormatter $formatter
    ): void {

        $ai = $agent->handle($this->message);

        $text =
            $ai['candidates'][0]['content']['parts'][0]['text']
            ?? '{}';

        /**
         * Gemini puede devolver:
         * ```json
         * {}
         * ```
         */

        $text = str_replace(
            ['```json', '```'],
            '',
            $text
        );

        $json = json_decode(
            trim($text),
            true
        );

        if (!$json) {

            $this->response =
                'JSON inválido devuelto por Gemini';

            return;
        }

        $result = $executor->run($json);

        /**
         * FORMATEAR RESPUESTA
         */

        $this->response =
            $formatter->format($result);
    }
}