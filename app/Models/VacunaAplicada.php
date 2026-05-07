<?php

namespace App\Models;

use App\Models\Traits\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class VacunaAplicada extends Model
{
    use BelongsToClinica, SoftDeletes;

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
        'fecha_aplicacion' => 'datetime',
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

    public static function esquemaDosis($mascotaId, $vacunaId)
    {
        $vacuna = Vacuna::find($vacunaId); //ignore bug intelephense

        if (!$vacuna) {
            return [0, 1];
        }

        $dosisBase = $vacuna->dosis ?? 1;

        $aplicadas = self::porMascotaVacuna($mascotaId, $vacunaId)->count(); //ignore bug intelephense

        if ($aplicadas == 0) {
            return [0, $dosisBase];
        }

        // 🔥 calcular ciclo actual
        $ciclos = ceil($aplicadas / $dosisBase);

        $totalEsperado = $ciclos * $dosisBase;

        return [$aplicadas, $totalEsperado];
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

        return $ultima->fecha_aplicacion->copy()->addDays($dias);
    }

    // =========================
    // ✅ VALIDACIÓN CENTRAL
    // =========================

    public static function validarAplicacion($model)
    {
        $lote = $model->lote;

        if (!$lote) {
            return 'Debe seleccionar un lote válido';
        }

        if ($lote->stock_actual <= 0) {
            return 'No hay stock disponible en este lote';
        }

        $dosisMax = $model->vacuna->dosis ?? 1;

        $aplicadas = self::esquemaDosis(
            $model->mascota_id,
            $model->vacuna_id
        );

        // 🔴 ya inició pero no terminó
        if ($aplicadas > 0 && $aplicadas < $dosisMax) {
            return 'Esta vacuna ya está en proceso. Use "Aplicar dosis".';
        }

        // 🔴 ya completó → validar refuerzo
        if ($aplicadas >= $dosisMax) {

            $ultima = self::ultimaAplicacion(
                $model->mascota_id,
                $model->vacuna_id
            );

            if (!$ultima) {
                return 'Error en historial de vacuna';
            }

            $dias = $model->vacuna->dias_refuerzo ?? 0;
            $proxima = $ultima->fecha_aplicacion->copy()->addDays($dias);

            if (now()->lt($proxima)) {
                return 'Vacuna completa. Próximo refuerzo: ' . $proxima->format('Y-m-d');
            }
        }

        return null; // ✅ OK
    }

    // =========================
    // ⚙️ EVENTOS AUTOMÁTICOS
    // =========================

    protected static function booted()
    {
        static::creating(function ($model) {

            // 🔥 AUTO veterinario
            if (!$model->veterinario_id) {
                $model->veterinario_id = Auth::id();
            }

            // ❌ YA NO VALIDAMOS AQUÍ (IMPORTANTE)
        });

        static::created(function ($model) {

            $lote = $model->lote;
            $vacuna = $model->vacuna;
            $user = Auth::user();

            // =========================
            // 📦 STOCK
            // =========================
            $lote->descontarStock(1);

            MovimientoStock::create([
                'clinica_id' => $model->clinica_id,
                'lote_id' => $lote->id,
                'tipo' => 'salida',
                'cantidad' => 1,
                'motivo' => 'Aplicación de vacuna',
                'fecha' => now(),
            ]);

            // =========================
            // 💰 CREAR VENTA
            // =========================
            $precio = $vacuna->precio_dosis ?? 0;

            $venta = \App\Models\Venta::create([
                'clinica_id' => $model->clinica_id,
                'usuario_id' => $user?->id,
                'cliente_id' => $model->mascota?->user_id,
                'total' => $precio,
                'estado' => 'pendiente',
                'fecha' => now(),
            ]);

            // =========================
            // 📄 DETALLE VENTA
            // =========================
            \App\Models\DetalleVenta::create([
                'venta_id' => $venta->id,
                'tipo_item' => 'vacuna',
                'descripcion' => $vacuna->nombre,
                'precio' => $precio,
                'cantidad' => 1,
                'subtotal' => $precio,
            ]);
        });
    }

    public function getProximaDosisAttribute()
    {
        $dias = $this->vacuna?->dias_refuerzo ?? 0;

        if (!$dias) {
            return null;
        }

        return $this->fecha_aplicacion
            ? $this->fecha_aplicacion->copy()->addDays($dias)
            : null;
    }
}
