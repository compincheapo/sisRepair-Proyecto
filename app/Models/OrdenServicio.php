<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Equipo;
use App\Models\Servicio;

class OrdenServicio extends Model
{
    use HasFactory;

    protected $table = 'ordenesservicio';

    protected $fillable = [
        'serie',
        'fechacompromiso',   
        'finalizada',
        'fechafin',
    ];

    public $timestamps = false;


    public function equipo(){
        return $this->belongsTo(Equipo::class, 'id_equipo', 'id');
    }

    //Relación que trae de la tabla pivote aquellos Estados con el id_orden dado.
    public function estados()
    {
        return $this->belongsToMany(
            Estado::class, 'equipos_estados_users_ordenes', 'id_orden', 'id_estado'
        )->orderBy('equipos_estados_users_ordenes.created_at', 'desc');
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class, 'equipos_estados_users_ordenes', 'id_user', 'id'
        );
    }

    public function equipos()
    {
        return $this->belongsToMany(
            Equipo::class, 'equipos_estados_users_ordenes', 'id_equipo', 'id'
        );
    }

    public function userOrden(){
       return $this->belongsToMany(User::class, 'users_ordenes', 'id_orden', 'id_user');
    }

    public function servicio(){
        return $this->belongsTo(Servicio::class, 'id_servicio', 'id');
    }
}
