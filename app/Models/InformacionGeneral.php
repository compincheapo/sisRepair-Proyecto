<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformacionGeneral extends Model
{
    use HasFactory;

    protected $table = 'informaciongeneral';

    protected $fillable = [
        'nombre',
        'horariodesde',
        'horariohasta',
        'terminos',
        'cant_notif_cliente',
        'frecuencia_notif_cliente',
        'cant_notif_tercero',
        'frecuencia_notif_tercero',
        'mediaid',
    ];
}
