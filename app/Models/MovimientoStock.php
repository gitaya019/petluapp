<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoStock extends Model
{
    protected $fillable = [
        'clinica_id','lote_id','tipo','cantidad','motivo','fecha'
    ];
}
