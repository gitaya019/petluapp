<?php

namespace App\Models;

use App\Models\Traits\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;

class MovimientoStock extends Model
{
    use BelongsToClinica;

    protected $fillable = [
        'clinica_id',
        'lote_id',
        'tipo',
        'cantidad',
        'motivo',
        'fecha'
    ];

    public function lote()
    {
        return $this->belongsTo(LoteVacuna::class, 'lote_id');
    }

    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }


    protected static function booted()
    {
        static::created(function ($movimiento) {

            $lote = $movimiento->lote;

            if (!$lote) return;

            if ($movimiento->tipo === 'entrada') {
                $lote->increment('stock_actual', $movimiento->cantidad);
            }

            if ($movimiento->tipo === 'salida') {
                $lote->decrement('stock_actual', $movimiento->cantidad);
            }

            // 🔥 ahora sí existe
            $lote->refresh();
            $lote->actualizarEstadoVacuna();
        });
    }
}
