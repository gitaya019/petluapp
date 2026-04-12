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
        'tipo',
        'mensaje',
        'fecha_programada',
        'estado'
    ];

    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }

    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }
}
