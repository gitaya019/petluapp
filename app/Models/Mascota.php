<?php

namespace App\Models;

use App\Models\Traits\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    use BelongsToClinica;

    protected $fillable = [
        'clinica_id',
        'user_id',
        'nombre',
        'especie',
        'raza',
        'fecha_nacimiento',
        'sexo',
        'peso',
        'color',
        'estado'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }
    public function vacunasAplicadas()
    {
        return $this->hasMany(VacunaAplicada::class, 'mascota_id');
    }
}
