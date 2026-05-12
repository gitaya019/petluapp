<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cita extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'clinica_id',
        'mascota_id',
        'veterinario_id',
        'vacuna_id',
        'fecha',
        'hora',
        'estado',
        'motivo',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }

    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }

    public function veterinario()
    {
        return $this->belongsTo(User::class, 'veterinario_id');
    }

    public function vacuna()
    {
        return $this->belongsTo(Vacuna::class);
    }
}