<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Equipo;
use App\Models\Servicio;
use App\Models\Pago;
use App\Models\OrdenPresupuesto;


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

    const UPDATED_AT = null;


    public function equipo(){
        return $this->belongsTo(Equipo::class, 'id_equipo', 'id');
    }

    //Relación que trae de la tabla pivote aquellos Estados con el id_orden dado.
    public function estados()
    {
        return $this->belongsToMany(
            Estado::class, 'equipos_estados_users_ordenes', 'id_orden', 'id_estado'
        )->orderBy('equipos_estados_users_ordenes.created_at', 'desc')->withPivot('created_at');;
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

    public function pagosorden(){
        return $this->belongsToMany(
            Pago::class,
            'ordenservicios_pagos',
            'id_orden',
            'id_pago'
            );
    }

    public function presupuestoOrden()
    {
        return $this->hasOne(OrdenPresupuesto::class, 'id_orden', 'id');
    }
}
