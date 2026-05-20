<?php

namespace App\AI\Support;

class AIResponseParser
{
    public static function text(
        array $response
    ): string {

        /**
         * GEMINI
         */

        if (
            isset(
                $response['candidates'][0]['content']['parts'][0]['text']
            )
        ) {

            return
                $response['candidates'][0]['content']['parts'][0]['text'];
        }

        /**
         * OLLAMA
         */

        if (
            isset(
                $response['response']
            )
        ) {

            return
                $response['response'];
        }

        return '';
    }
}