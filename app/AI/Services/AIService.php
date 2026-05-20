<?php

namespace App\AI\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    public function ask(
        string $prompt
    ): array {

        /**
         * SI HAY API KEY
         * => GEMINI
         */

        if (
            filled(
                config('ai.gemini_api_key')
            )
        ) {

            return $this->askGemini(
                $prompt
            );
        }

        /**
         * SI NO
         * => OLLAMA
         */

        return $this->askOllama(
            $prompt
        );
    }

    /**
     * GEMINI
     */

    protected function askGemini(
        string $prompt
    ): array {

        $response = Http::timeout(120)
            ->post(

                'https://generativelanguage.googleapis.com/v1beta/models/' .

                    config('ai.gemini_model') .

                    ':generateContent?key=' .

                    config('ai.gemini_api_key'),

                [

                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $prompt
                                ]
                            ]
                        ]
                    ]
                ]
            );

        return $response->json();
    }

    /**
     * OLLAMA
     */

    protected function askOllama(
        string $prompt
    ): array {

        $response = Http::timeout(120)
            ->post(

                config('ai.ollama_url') .
                    '/api/generate',

                [

                    'model' => config(
                        'ai.ollama_model'
                    ),

                    'prompt' => $prompt,

                    'stream' => false,

                    'options' => [

                        /**
                         * JSON estable
                         */

                        'temperature' => 0,
                    ]
                ]
            );

        return $response->json();
    }
}