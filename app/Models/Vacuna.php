<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
    protected $fillable = ['clinica_id', 'nombre', 'descripcion', 'dosis', 'fabricante', 'estado'];

    public function lotes()
    {
        return $this->hasMany(LoteVacuna::class);
    }
}
