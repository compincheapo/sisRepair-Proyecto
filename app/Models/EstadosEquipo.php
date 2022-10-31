<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Equipo;

class EstadosEquipo extends Model
{
    use HasFactory;

    protected $table = 'equipos_estados_users_ordenes';

    public $timestamps = true;


    public function equipos()
    {
        return $this->belongsToMany(Equipo::class,'equipos_estados_users_ordenes');
    }
}
