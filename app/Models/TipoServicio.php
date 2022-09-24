<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Servicio;

class TipoServicio extends Model
{
    use HasFactory;

    protected $table = 'tiposervicios';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_servicio'
    ];

    public function servicio(){
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }
}
