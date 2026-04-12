<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToClinica;

class HistorialMedico extends Model
{
    use BelongsToClinica;

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

    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }

    public function veterinario()
    {
        return $this->belongsTo(User::class, 'veterinario_id');
    }

    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }
}
