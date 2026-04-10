<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'clinica_id',
    'name',
    'email',
    'numero_documento',
    'tipo_documento',
    'password',
    'telefono',
    'estado'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =========================
    // 🔗 RELACIONES
    // =========================

    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }

    public function mascotas()
    {
        return $this->hasMany(Mascota::class);
    }

    // Como empleado (vende)
    public function ventasEmpleado()
    {
        return $this->hasMany(Venta::class, 'usuario_id');
    }

    // Como cliente (compra)
    public function ventasCliente()
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }

    // Como veterinario (aplica vacunas)
    public function vacunasAplicadas()
    {
        return $this->hasMany(VacunaAplicada::class, 'veterinario_id');
    }

    // Como veterinario (historial médico)
    public function historiales()
    {
        return $this->hasMany(HistorialMedico::class, 'veterinario_id');
    }
}
