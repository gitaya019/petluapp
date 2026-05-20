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
                 * FILTROS NORMALES Y RELACIONALES
                 * Ahora soporta sintaxis de punto: mascota.nombre
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
     * APLICAR FILTROS (MODIFICADO PARA SOPORTAR RELACIONES)
     */

    protected function applyFilter(
        $query,
        string $field,
        mixed $value
    ): void {

        /**
         * VERIFICAR SI ES FILTRO RELACIONAL (contiene punto)
         * Ejemplo: mascota.nombre, user.name, etc.
         */

        if (str_contains($field, '.')) {
            
            $parts = explode('.', $field);
            $relation = $parts[0];
            $relationField = $parts[1];
            
            // Aplicar filtro a la relación
            $query->whereHas($relation, function ($q) use ($relationField, $value) {
                
                if (is_array($value)) {
                    $flatValues = $this->flattenArray($value);
                    $q->where(function ($sub) use ($relationField, $flatValues) {
                        foreach ($flatValues as $v) {
                            $sub->orWhere($relationField, 'like', '%' . $v . '%');
                        }
                    });
                } else {
                    $q->where($relationField, 'like', '%' . $value . '%');
                }
            });
            
            return;
        }

        /**
         * ARRAYS (filtros múltiples)
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
         * STRING NORMAL (campos directos)
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