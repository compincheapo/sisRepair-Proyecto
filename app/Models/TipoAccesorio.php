<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Equipo;
use Carbon\Carbon;

class TipoAccesorio extends Model
{
    use HasFactory;

    protected $table = 'tipoaccesorios';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function equipos(){
        return $this->belongsToMany(
            Equipo::class,
            'equipos_accesorios',
            'id_accesorio',
            'id_equipo'
           );
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
