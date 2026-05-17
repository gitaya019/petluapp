<?php

namespace App\AI\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    public function ask(string $prompt)
    {
        $response = Http::post(
            'https://generativelanguage.googleapis.com/v1beta/models/' .
            config('ai.model') .
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
}