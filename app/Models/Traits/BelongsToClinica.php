<?php

namespace App\Models\Traits;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToClinica
{
    protected static function bootBelongsToClinica(): void
    {
        // 🔒 Scope automático por clínica
        static::addGlobalScope('clinica', function (Builder $query) {
            $tenant = Filament::getTenant();

            if ($tenant) {
                $query->where('clinica_id', $tenant->id);
            }
        });

        // 🧠 Asignación automática
        static::creating(function ($model) {
            $tenant = Filament::getTenant();

            if ($tenant && empty($model->clinica_id)) {
                $model->clinica_id = $tenant->id;
            }
        });
    }
}