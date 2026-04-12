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

    public function vacuna()
    {
        return $this->belongsTo(Vacuna::class);
    }

    public function lote()
    {
        return $this->belongsTo(LoteVacuna::class, 'lote_id');
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
