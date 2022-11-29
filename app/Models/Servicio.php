<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TipoServicio;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public $timestamps = false;

    public function tipoServicio(){
        return $this->hasMany(TipoServicio::class, 'id');
    }

    public function ordenes(){
        return $this->hasMany(OrdenServicio::class, 'id_servicio', 'id');
    }
}
