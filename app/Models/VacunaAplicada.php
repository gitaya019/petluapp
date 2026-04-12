<?php

namespace App\Models;

use App\Models\Traits\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VacunaAplicada extends Model
{
    use BelongsToClinica;

    protected $table = 'vacuna_aplicadas';

    protected $fillable = [
        'clinica_id',
        'mascota_id',
        'vacuna_id',
        'lote_id',
        'veterinario_id',
        'fecha_aplicacion',
        'observaciones'
    ];

    protected $casts = [
        'fecha_aplicacion' => 'datetime', // 🔥 CLAVE
    ];

    // =========================
    // 🔗 RELACIONES
    // =========================

    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }

    public function vacuna()
    {
        return $this->belongsTo(Vacuna::class);
    }

    public function lote()
    {
        return $this->belongsTo(LoteVacuna::class, 'lote_id');
    }

    public function veterinario()
    {
        return $this->belongsTo(User::class, 'veterinario_id');
    }

    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }

    // =========================
    // 🧠 MÉTODOS INTELIGENTES
    // =========================

    public function scopePorMascotaVacuna($query, $mascotaId, $vacunaId)
    {
        return $query->where('mascota_id', $mascotaId)
            ->where('vacuna_id', $vacunaId);
    }

    public static function totalAplicadas($mascotaId, $vacunaId)
    {
        return self::porMascotaVacuna($mascotaId, $vacunaId)->count();
    }

    public static function ultimaAplicacion($mascotaId, $vacunaId)
    {
        return self::porMascotaVacuna($mascotaId, $vacunaId)
            ->latest('fecha_aplicacion')
            ->first();
    }

    public static function proximaDosis($mascotaId, $vacunaId)
    {
        $ultima = self::ultimaAplicacion($mascotaId, $vacunaId);

        if (!$ultima) {
            return now();
        }

        $dias = $ultima->vacuna->dias_refuerzo ?? 0;

        return $ultima->fecha_aplicacion->addDays($dias);
    }

    // =========================
    // ⚙️ EVENTOS AUTOMÁTICOS
    // =========================


protected static function booted()
{
    static::creating(function ($model) {

        // 🔥 ASIGNAR VETERINARIO AUTOMÁTICAMENTE
        if (!$model->veterinario_id) {
            $model->veterinario_id = Auth::id();
        }

        $lote = $model->lote;

        if (!$lote) {
            throw new \Exception('Debe seleccionar un lote válido');
        }

        if ($lote->stock_actual <= 0) {
            throw new \Exception('No hay stock disponible en este lote');
        }

        $dosisMax = $model->vacuna->dosis ?? 1;

        $aplicadas = self::totalAplicadas(
            $model->mascota_id,
            $model->vacuna_id
        );

        if ($aplicadas >= $dosisMax) {
            throw new \Exception('La mascota ya completó las dosis');
        }
    });

    static::created(function ($model) {

        $lote = $model->lote;

        // ✅ usar método del modelo
        $lote->descontarStock(1);

        MovimientoStock::create([
            'clinica_id' => $model->clinica_id,
            'lote_id' => $lote->id,
            'tipo' => 'salida',
            'cantidad' => 1,
            'motivo' => 'Aplicación de vacuna',
            'fecha' => now(),
        ]);
    });

    }
}
