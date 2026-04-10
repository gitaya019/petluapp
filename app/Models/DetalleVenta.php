<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'venta_id',
        'tipo_item',
        'descripcion',
        'precio',
        'cantidad',
        'subtotal'
    ];
}
