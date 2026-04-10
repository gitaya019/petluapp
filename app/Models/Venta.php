<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'clinica_id',
        'usuario_id',
        'cliente_id',
        'total',
        'estado',
        'fecha'
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
