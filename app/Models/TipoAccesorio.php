<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoAccesorio extends Model
{
    use HasFactory;

    protected $table = 'tipoaccesorios';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public $timestamps = false;
}
