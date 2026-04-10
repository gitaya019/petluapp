<?php

namespace App\Models;

use App\Models\Traits\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use BelongsToClinica;
    
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
