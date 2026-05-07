<?php

namespace App\Models;

use App\Models\Traits\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;

class Recordatorio extends Model
{
    use BelongsToClinica;

    protected $fillable = [
        'clinica_id',
        'mascota_id',
        'vacuna_id',
        'vacuna_aplicada_id',
        'tipo',
        'mensaje',
        'fecha_programada',
        'estado',
        'enviado',
        'enviado_at',
        'correo_destino',
    ];

    protected $casts = [
        'fecha_programada' => 'datetime',
        'enviado_at' => 'datetime',
        'enviado' => 'boolean',
    ];

    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }

    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }

    public function vacuna()
    {
        return $this->belongsTo(Vacuna::class);
    }

    public function vacunaAplicada()
    {
        return $this->belongsTo(VacunaAplicada::class);
    }
}