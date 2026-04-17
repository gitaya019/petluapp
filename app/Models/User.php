<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'name',
    'email',
    'numero_documento',
    'tipo_documento',
    'password',
    'telefono',
    'estado'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements HasTenants
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

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

    public function clinicas()
    {
        return $this->belongsToMany(Clinica::class);
    }

    public function getTenants(Panel $panel): Collection
    {
        if ($this->isSuperAdmin()) {
            return Clinica::all();
        }

        return $this->clinicas;
    }
    
    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->clinicas()->whereKey($tenant->id)->exists();
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

    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin;
    }
}
