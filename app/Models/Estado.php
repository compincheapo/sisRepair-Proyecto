<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Equipo;
use App\Models\User;
use App\Models\OrdenServicio;


class Estado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public $timestamps = false;


    //Relación que trae de la tabla pivote aquellos Equipos con el id_estado especificado.
    public function equipos()
    {
        return $this->belongsToMany(
            Equipo::class, 'equipos_estados_users_ordenes', 'id_estado', 'id_equipo'
        )->orderBy('equipos_estados_users_ordenes.created_at', 'desc')->first();
    }

    //Relación que trae de la tabla pivote aquellos Usuarios con el id_estado especificado.
    public function users()
    {
        return $this->belongsToMany(
            User::class, 'equipos_estados_users_ordenes', 'id_estado', 'id'
        );
    }

    public function ordenes()
    {
        return $this->belongsToMany(
            OrdenServicio::class, 'equipos_estados_users_ordenes', 'id_orden', 'id'
        );
    }
}
