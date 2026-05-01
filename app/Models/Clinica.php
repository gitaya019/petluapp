<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class Clinica extends Model implements HasName
{
    use SoftDeletes;
    protected $fillable = ['nombre', 'nit', 'direccion', 'telefono', 'email', 'estado'];

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

    /** @return HasMany<\App\Models\HistorialMedico, self> */
    public function historialMedicos(): HasMany
    {
        return $this->hasMany(\App\Models\HistorialMedico::class);
    }


    /** @return HasMany<\App\Models\LoteVacuna, self> */
    public function loteVacunas(): HasMany
    {
        return $this->hasMany(\App\Models\LoteVacuna::class);
    }


    /** @return HasMany<\App\Models\MovimientoStock, self> */
    public function movimientoStocks(): HasMany
    {
        return $this->hasMany(\App\Models\MovimientoStock::class);
    }


    /** @return HasMany<\App\Models\Recordatorio, self> */
    public function recordatorios(): HasMany
    {
        return $this->hasMany(\App\Models\Recordatorio::class);
    }


    /** @return HasMany<\App\Models\VacunaAplicada, self> */
    public function vacunaAplicadas(): HasMany
    {
        return $this->hasMany(\App\Models\VacunaAplicada::class);
    }


    /** @return HasMany<\App\Models\Venta, self> */
    public function ventas(): HasMany
    {
        return $this->hasMany(\App\Models\Venta::class);
    }


    /** @return HasMany<\App\Models\Role, self> */
    public function roles(): HasMany
    {
        return $this->hasMany(\App\Models\Role::class);
    }

    protected static function booted()
    {
        // 🔵 SOLO soft delete
        static::deleting(function ($clinica) {

            if (! $clinica->isForceDeleting()) {
                return; // ❌ no tocar nada en soft delete
            }

            // 🔴 SOLO si es delete definitivo (forceDelete)

            // 1. limpiar usuarios (pivot clinica_user)
            $clinica->users()->detach();

            // 2. obtener roles de esa clínica
            $roles = Role::where('clinica_id', $clinica->id)->get(); //ignore error intelephense

            $roleIds = $roles->pluck('id');

            // 3. borrar relaciones model_has_roles
            DB::table('model_has_roles')
                ->whereIn('role_id', $roleIds)
                ->delete();

            // 4. borrar roles de la clínica
            Role::where('clinica_id', $clinica->id)->delete(); //ignore error intelephense

            // 5. limpiar cache Spatie
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        });
    }
}
