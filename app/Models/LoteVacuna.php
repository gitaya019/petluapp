<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoteVacuna extends Model
{
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
}
