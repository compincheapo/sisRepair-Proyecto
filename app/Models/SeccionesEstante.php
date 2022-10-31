<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Estante;
use App\Models\Equipo;

class SeccionesEstante extends Model
{
    use HasFactory;

    protected $table = 'seccionesestante';

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_estante',
    ];

    public $timestamps = false;


    public function estante(){
        return $this->belongsTo(Estante::class, 'id_estante', 'id');
    }

    public function equipos(){
        return $this->hasMany(Equipo::class, 'id_seccionestante', 'id');
    }
}
