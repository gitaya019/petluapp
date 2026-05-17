<?php

namespace App\AI\Services;

use Illuminate\Database\Eloquent\Collection;

class ResultFormatter
{
    public function format($result): string
    {
        /**
         * COUNT
         */

        if (is_int($result)) {

            return "Resultado: {$result}";
        }

        /**
         * COLLECTION
         */

        if ($result instanceof Collection) {

            if ($result->isEmpty()) {
                return 'No se encontraron registros.';
            }

            $text = '';

            foreach ($result as $item) {

                $text .= "• Registro #{$item->id}\n";

                foreach (
                    $item->getAttributes()
                    as $key => $value
                ) {

                    if (
                        in_array($key, [
                            'created_at',
                            'updated_at',
                            'deleted_at',
                        ])
                    ) {
                        continue;
                    }

                    $label = str_replace(
                        '_',
                        ' ',
                        ucfirst($key)
                    );

                    $text .= "{$label}: {$value}\n";
                }

                $text .= "\n";
            }

            return $text;
        }

        /**
         * MODEL
         */

        if (is_object($result)) {

            $text = "Registro creado/actualizado\n\n";

            foreach (
                $result->getAttributes()
                as $key => $value
            ) {

                if (
                    in_array($key, [
                        'created_at',
                        'updated_at',
                        'deleted_at',
                    ])
                ) {
                    continue;
                }

                $label = str_replace(
                    '_',
                    ' ',
                    ucfirst($key)
                );

                $text .= "{$label}: {$value}\n";
            }

            return $text;
        }

        return (string) $result;
    }
}