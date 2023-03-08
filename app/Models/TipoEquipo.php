<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TipoEquipo extends Model
{
    use HasFactory;

    protected $table = 'tipoequipos';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function equipos(){
        return $this->hasMany(Equipo::class, 'id_tipoequipo', 'id');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }

}
