<?php

namespace App\Models;

use App\Models\Traits\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{

    use BelongsToClinica;

    protected $fillable = ['clinica_id', 'nombre', 'descripcion', 'dosis', 'fabricante', 'estado'];

    public function lotes()
    {
        return $this->hasMany(LoteVacuna::class);
    }
}
