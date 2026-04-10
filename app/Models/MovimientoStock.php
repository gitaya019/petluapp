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
}
