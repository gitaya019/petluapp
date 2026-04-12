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

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'venta_id');
    }

    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }
}
