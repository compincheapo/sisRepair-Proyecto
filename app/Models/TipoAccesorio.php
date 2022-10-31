<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Equipo;

class TipoAccesorio extends Model
{
    use HasFactory;

    protected $table = 'tipoaccesorios';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public $timestamps = false;


    public function equipos(){
        return $this->belongsToMany(
            Equipo::class,
            'equipos_accesorios',
            'id_accesorio',
            'id_equipo'
           );
    }
}
