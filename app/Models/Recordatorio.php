<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recordatorio extends Model
{
    protected $fillable = [
        'clinica_id','mascota_id','tipo','mensaje','fecha_programada','estado'
    ];
}