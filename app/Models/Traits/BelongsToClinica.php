<?php

namespace App\Models\Traits;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToClinica
{
    protected static function bootBelongsToClinica(): void
    {
        // 🔒 Filtrar automáticamente por clínica
        static::addGlobalScope('clinica', function (Builder $query) {
            if (Auth::check() && Filament::getTenant()) {
                $query->where('clinica_id', Filament::getTenant()->id);
            }
        });

        // 🧠 Asignar automáticamente clinica_id
        static::creating(function ($model) {
            if (Auth::check() && Filament::getTenant()) {
                if (empty($model->clinica_id)) {
                    $model->clinica_id = Filament::getTenant()->id;
                }
            }
        });
    }
}