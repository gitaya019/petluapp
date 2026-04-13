<?php

namespace App\Models;

use App\Models\Traits\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;

class LoteVacuna extends Model
{
    use BelongsToClinica;
    protected $table = 'lote_vacunas';

    protected $fillable = [
        'clinica_id',
        'vacuna_id',
        'numero_lote',
        'fecha_vencimiento',
        'stock_inicial',
        'stock_actual'
    ];

    public function vacuna()
    {
        return $this->belongsTo(Vacuna::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoStock::class, 'lote_id');
    }

    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }

    public function descontarStock($cantidad = 1)
    {
        if ($this->stock_actual < $cantidad) {
            throw new \Exception('Stock insuficiente');
        }

        $this->decrement('stock_actual', $cantidad);

        // 🔥 recargar modelo actualizado
        $this->refresh();

        // 🔥 actualizar estado de vacuna
        $this->actualizarEstadoVacuna();
    }

    public function ingresarStock($cantidad)
    {
        $this->increment('stock_actual', $cantidad);

        MovimientoStock::create([
            'clinica_id' => $this->clinica_id,
            'lote_id' => $this->id,
            'tipo' => 'entrada',
            'cantidad' => $cantidad,
            'motivo' => 'Ingreso de inventario',
            'fecha' => now(),
        ]);
    }

    protected static function booted()
    {
        static::saved(function ($lote) {

            $vacuna = $lote->vacuna;

            if (!$vacuna) return;

            // 🔥 verificar si algún lote tiene stock
            $tieneStock = $vacuna->lotes()
                ->where('stock_actual', '>', 0)
                ->exists();

            // 🟢 activar / 🔴 desactivar vacuna
            $vacuna->update([
                'estado' => $tieneStock
            ]);
        });
    }

    public function actualizarEstadoVacuna()
    {
        $vacuna = $this->vacuna;

        if (!$vacuna) return;

        $tieneStock = $vacuna->lotes()
            ->where('stock_actual', '>', 0)
            ->exists();

        if ($vacuna->estado !== $tieneStock) {
            $vacuna->update([
                'estado' => $tieneStock
            ]);
        }
    }
}
