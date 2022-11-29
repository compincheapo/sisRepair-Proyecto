<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Repuesto;

class TipoRepuesto extends Model
{
    use HasFactory;

    protected $table = 'tiporepuestos';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public $timestamps = false;
}
