<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Equipo;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public $timestamps = false;


    public function equipos(){
        return $this->hasMany(Equipo::class, 'id_marca', 'id');
    }

}
