<?php

namespace App\AI\Actions;

class ExecuteAction
{
    public function run(array $data)
    {
        $models = include app_path(
            'AI/models.php'
        );

        $config =
            $models[$data['model'] ?? '']
            ?? null;

        if (!$config) {
            return 'Modelo inválido';
        }

        $modelClass =
            $config['model'];

        switch (
            $data['action'] ?? ''
        ) {

            /**
             * SEARCH
             */

            case 'search':

                $query =
                    $modelClass::query();

                /**
                 * RELACIONES
                 */

                if (
                    !empty($config['relations'])
                ) {

                    $query->with(
                        $config['relations']
                    );
                }

                /**
                 * FILTROS NORMALES
                 */

                foreach (
                    ($data['filters'] ?? [])
                    as $field => $value
                ) {

                    $this->applyFilter(
                        $query,
                        $field,
                        $value
                    );
                }

                /**
                 * FILTROS RELACIONALES
                 */

                foreach (
                    ($data['relation_filters'] ?? [])
                    as $relation => $filters
                ) {

                    $query->whereHas(
                        $relation,
                        function ($q)
                        use ($filters) {

                            foreach (
                                $filters
                                as $field => $value
                            ) {

                                $this->applyFilter(
                                    $q,
                                    $field,
                                    $value
                                );
                            }
                        }
                    );
                }

                return $query
                    ->limit(10)
                    ->get();

            /**
             * COUNT
             */

            case 'count':

                return $modelClass::count();

            /**
             * CREATE
             */

            case 'create':

                return $modelClass::create(
                    $data['data'] ?? []
                );

            /**
             * UPDATE
             */

            case 'update':

                $record =
                    $modelClass::find(
                        $data['id']
                    );

                if (!$record) {
                    return 'Registro no encontrado';
                }

                $record->update(
                    $data['data'] ?? []
                );

                return $record;
        }

        return 'Acción inválida';
    }

    /**
     * APLICAR FILTROS
     */

    protected function applyFilter(
        $query,
        string $field,
        mixed $value
    ): void {

        /**
         * ARRAYS
         */

        if (is_array($value)) {

            $flatValues =
                $this->flattenArray($value);

            $query->where(
                function ($sub)
                use ($field, $flatValues) {

                    foreach (
                        $flatValues as $v
                    ) {

                        $sub->orWhere(
                            $field,
                            'like',
                            '%' . $v . '%'
                        );
                    }
                }
            );

            return;
        }

        /**
         * STRING NORMAL
         */

        $query->where(
            $field,
            'like',
            '%' . $value . '%'
        );
    }

    /**
     * LIMPIAR ARRAYS ANIDADOS
     */

    protected function flattenArray(
        array $array
    ): array {

        $result = [];

        array_walk_recursive(
            $array,
            function ($item)
            use (&$result) {

                if (
                    is_scalar($item)
                ) {

                    $result[] =
                        (string) $item;
                }
            }
        );

        return $result;
    }
}