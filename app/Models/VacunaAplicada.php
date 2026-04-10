<?php

namespace App\Models;

use App\Models\Traits\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;

class VacunaAplicada extends Model
{
    use BelongsToClinica;
    
    protected $table = 'vacuna_aplicadas';

    protected $fillable = [
        'clinica_id',
        'mascota_id',
        'vacuna_id',
        'lote_id',
        'veterinario_id',
        'fecha_aplicacion',
        'observaciones'
    ];

    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }
}
