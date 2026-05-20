<?php

namespace App\AI\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ResultFormatter
{
    public function format($result): string
    {
        /**
         * COUNT (entero)
         */

        if (is_int($result)) {
            return "📊 **Total:** {$result} registro(s)";
        }

        /**
         * ERROR O MENSAJE (string)
         */
        
        if (is_string($result)) {
            return "❌ {$result}";
        }

        /**
         * COLLECTION (múltiples registros)
         */

        if ($result instanceof Collection) {

            if ($result->isEmpty()) {
                return '🔍 No se encontraron registros.';
            }

            // Obtener el nombre del modelo para personalizar el formato
            $firstItem = $result->first();
            $modelName = class_basename($firstItem);
            
            // Formato específico según el tipo de modelo
            return $this->formatCollectionByType($result, $modelName);
        }

        /**
         * SINGLE MODEL (un solo registro)
         */

        if ($result instanceof Model) {
            return $this->formatSingleModel($result);
        }

        /**
         * DEFAULT
         */

        return (string) $result;
    }

    /**
     * Formatea colecciones según el tipo de modelo
     */
    protected function formatCollectionByType(Collection $collection, string $modelName): string
    {
        switch ($modelName) {
            case 'Mascota':
                return $this->formatMascotas($collection);
            case 'Cita':
                return $this->formatCitas($collection);
            case 'VacunaAplicada':
                return $this->formatVacunasAplicadas($collection);
            case 'User':
                return $this->formatUsers($collection);
            case 'Vacuna':
                return $this->formatVacunas($collection);
            case 'Clinica':
                return $this->formatClinicas($collection);
            case 'HistorialMedico':
                return $this->formatHistoriales($collection);
            default:
                return $this->formatGeneric($collection);
        }
    }

    /**
     * Formato específico para Mascotas
     */
    protected function formatMascotas(Collection $mascotas): string
    {
        $text = "🐾 **Mascotas encontradas:** (" . $mascotas->count() . ")\n\n";
        
        foreach ($mascotas as $mascota) {
            $text .= "**#{$mascota->id}** - **{$mascota->nombre}**\n";
            $text .= "  • Especie: {$mascota->especie}\n";
            
            if ($mascota->raza) {
                $text .= "  • Raza: {$mascota->raza}\n";
            }
            
            if ($mascota->sexo) {
                $text .= "  • Sexo: {$mascota->sexo}\n";
            }
            
            if ($mascota->peso) {
                $text .= "  • Peso: {$mascota->peso} kg\n";
            }
            
            if ($mascota->fecha_nacimiento) {
                $text .= "  • Edad: " . Carbon::parse($mascota->fecha_nacimiento)->age . " años\n";
            }
            
            $text .= "\n";
        }
        
        return $text;
    }

    /**
     * Formato específico para Vacunas Aplicadas
     */
    protected function formatVacunasAplicadas(Collection $vacunas): string
    {
        $text = "💉 **Vacunas aplicadas encontradas:** (" . $vacunas->count() . ")\n\n";
        
        foreach ($vacunas as $vacuna) {
            $text .= "**#{$vacuna->id}**\n";
            
            // Mostrar datos de la vacuna (relación)
            if ($vacuna->relationLoaded('vacuna') && $vacuna->vacuna) {
                $text .= "  • Vacuna: {$vacuna->vacuna->nombre}\n";
            }
            
            // Mostrar datos de la mascota (relación)
            if ($vacuna->relationLoaded('mascota') && $vacuna->mascota) {
                $text .= "  • Mascota: {$vacuna->mascota->nombre}\n";
            }
            
            // Mostrar datos del veterinario (relación)
            if ($vacuna->relationLoaded('veterinario') && $vacuna->veterinario) {
                $text .= "  • Veterinario: {$vacuna->veterinario->name}\n";
            }
            
            // Fecha de aplicación
            if ($vacuna->fecha_aplicacion) {
                $fecha = Carbon::parse($vacuna->fecha_aplicacion)->format('d/m/Y');
                $text .= "  • Fecha: {$fecha}\n";
            }
            
            // Observaciones
            if ($vacuna->observaciones) {
                $text .= "  • Observaciones: {$vacuna->observaciones}\n";
            }
            
            $text .= "\n";
        }
        
        return $text;
    }

    /**
     * Formato específico para Citas
     */
    protected function formatCitas(Collection $citas): string
    {
        $text = "📅 **Citas encontradas:** (" . $citas->count() . ")\n\n";
        
        foreach ($citas as $cita) {
            $text .= "**#{$cita->id}**\n";
            
            // Mascota
            if ($cita->relationLoaded('mascota') && $cita->mascota) {
                $text .= "  • Mascota: {$cita->mascota->nombre}\n";
            }
            
            // Fecha y hora
            if ($cita->fecha) {
                $fecha = Carbon::parse($cita->fecha)->format('d/m/Y');
                $text .= "  • Fecha: {$fecha} {$cita->hora}\n";
            }
            
            // Estado con emoji
            $estadoEmoji = match($cita->estado) {
                'pendiente' => '⏳',
                'confirmada' => '✅',
                'completada' => '✔️',
                'cancelada' => '❌',
                'no_asistio' => '⚠️',
                default => '📌'
            };
            $text .= "  • Estado: {$estadoEmoji} {$cita->estado}\n";
            
            // Motivo
            if ($cita->motivo) {
                $text .= "  • Motivo: {$cita->motivo}\n";
            }
            
            $text .= "\n";
        }
        
        return $text;
    }

    /**
     * Formato específico para Usuarios
     */
    protected function formatUsers(Collection $users): string
    {
        $text = "👤 **Usuarios encontrados:** (" . $users->count() . ")\n\n";
        
        foreach ($users as $user) {
            $text .= "**#{$user->id}** - **{$user->name}**\n";
            $text .= "  • Email: {$user->email}\n";
            
            if ($user->telefono) {
                $text .= "  • Teléfono: {$user->telefono}\n";
            }
            
            if ($user->numero_documento) {
                $text .= "  • Documento: {$user->numero_documento}\n";
            }
            
            $text .= "\n";
        }
        
        return $text;
    }

    /**
     * Formato específico para Vacunas
     */
    protected function formatVacunas(Collection $vacunas): string
    {
        $text = "💊 **Vacunas disponibles:** (" . $vacunas->count() . ")\n\n";
        
        foreach ($vacunas as $vacuna) {
            $text .= "**#{$vacuna->id}** - **{$vacuna->nombre}**\n";
            
            if ($vacuna->descripcion) {
                $text .= "  • Descripción: {$vacuna->descripcion}\n";
            }
            
            if ($vacuna->fabricante) {
                $text .= "  • Fabricante: {$vacuna->fabricante}\n";
            }
            
            $text .= "  • Dosis: {$vacuna->dosis}\n";
            $text .= "  • Precio: \${$vacuna->precio_dosis}\n";
            
            $text .= "\n";
        }
        
        return $text;
    }

    /**
     * Formato específico para Clínicas
     */
    protected function formatClinicas(Collection $clinicas): string
    {
        $text = "🏥 **Clínicas encontradas:** (" . $clinicas->count() . ")\n\n";
        
        foreach ($clinicas as $clinica) {
            $text .= "**#{$clinica->id}** - **{$clinica->nombre}**\n";
            $text .= "  • NIT: {$clinica->nit}\n";
            
            if ($clinica->direccion) {
                $text .= "  • Dirección: {$clinica->direccion}\n";
            }
            
            if ($clinica->telefono) {
                $text .= "  • Teléfono: {$clinica->telefono}\n";
            }
            
            if ($clinica->email) {
                $text .= "  • Email: {$clinica->email}\n";
            }
            
            $text .= "\n";
        }
        
        return $text;
    }

    /**
     * Formato específico para Historiales Médicos
     */
    protected function formatHistoriales(Collection $historiales): string
    {
        $text = "📋 **Historiales médicos encontrados:** (" . $historiales->count() . ")\n\n";
        
        foreach ($historiales as $historial) {
            $text .= "**#{$historial->id}**\n";
            
            if ($historial->relationLoaded('mascota') && $historial->mascota) {
                $text .= "  • Mascota: {$historial->mascota->nombre}\n";
            }
            
            if ($historial->fecha) {
                $fecha = Carbon::parse($historial->fecha)->format('d/m/Y');
                $text .= "  • Fecha: {$fecha}\n";
            }
            
            if ($historial->motivo_consulta) {
                $text .= "  • Motivo: {$historial->motivo_consulta}\n";
            }
            
            if ($historial->diagnostico) {
                $text .= "  • Diagnóstico: {$historial->diagnostico}\n";
            }
            
            if ($historial->tratamiento) {
                $text .= "  • Tratamiento: {$historial->tratamiento}\n";
            }
            
            $text .= "\n";
        }
        
        return $text;
    }

    /**
     * Formato genérico para cualquier modelo
     */
    protected function formatGeneric(Collection $collection): string
    {
        $text = "📋 **Registros encontrados:** (" . $collection->count() . ")\n\n";
        
        foreach ($collection as $item) {
            $text .= "**Registro #{$item->id}**\n";
            
            foreach ($item->getAttributes() as $key => $value) {
                // Omitir timestamps
                if (in_array($key, ['created_at', 'updated_at', 'deleted_at'])) {
                    continue;
                }
                
                // Formatear nombre del campo
                $label = str_replace('_', ' ', ucfirst($key));
                
                // Formatear fechas si es necesario
                if (($key === 'fecha' || $key === 'fecha_aplicacion' || $key === 'fecha_nacimiento') && $value) {
                    $value = Carbon::parse($value)->format('d/m/Y');
                }
                
                $text .= "  • {$label}: {$value}\n";
            }
            
            $text .= "\n";
        }
        
        return $text;
    }

    /**
     * Formato para un modelo individual
     */
    protected function formatSingleModel(Model $model): string
    {
        $modelName = class_basename($model);
        
        // Usar el formato específico si existe
        $collection = new Collection([$model]);
        $formatted = $this->formatCollectionByType($collection, $modelName);
        
        // Reemplazar el encabezado plural por uno singular
        $formatted = str_replace(
            "encontrados: (1)\n\n",
            "encontrado:\n\n",
            $formatted
        );
        
        return $formatted;
    }
}