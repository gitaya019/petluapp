<?php

namespace App\Models;

use App\Models\Traits\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vacuna extends Model
{

    use BelongsToClinica, SoftDeletes;

    protected $fillable = ['clinica_id', 'nombre', 'descripcion', 'dosis', 'dias_refuerzo', 'fabricante', 'estado', 'precio_dosis'];

    public function lotes()
    {
        return $this->hasMany(LoteVacuna::class);
    }

    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }

}
