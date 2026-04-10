<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clinica extends Model
{
    protected $fillable = ['nombre','nit','direccion','telefono','email','estado'];

    public function users() { return $this->hasMany(User::class); }
    public function mascotas() { return $this->hasMany(Mascota::class); }
    public function vacunas() { return $this->hasMany(Vacuna::class); }
}