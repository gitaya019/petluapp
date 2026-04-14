<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinica extends Model implements HasName
{
    use SoftDeletes;
    protected $fillable = ['nombre','nit','direccion','telefono','email','estado'];

    public function getFilamentName(): string
    {
        return $this->nombre;
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function mascotas()
    {
        return $this->hasMany(Mascota::class);
    }

    public function vacunas()
    {
        return $this->hasMany(Vacuna::class);
    }
}