<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialMedico extends Model
{
    protected $fillable = [
        'clinica_id',
        'mascota_id',
        'veterinario_id',
        'motivo_consulta',
        'diagnostico',
        'tratamiento',
        'observaciones',
        'fecha'
    ];
}
