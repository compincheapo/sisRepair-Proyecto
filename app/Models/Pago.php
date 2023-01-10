<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TipoPago;
use App\Models\OrdenServicio;


class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'fechapago',
        'id_tipopago',
    ];

    public $timestamps = false;

    public function tipopago(){
        return $this->belongsTo(TipoPago::class, 'id_tipopago', 'id');
    }

    public function ordenespago(){
        return $this->belongsToMany(
            OrdenServicio::class,
            'ordenservicios_pagos',
            'id_pago',
            'id_orden'
            );
    }

}
