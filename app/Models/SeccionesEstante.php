<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Estante;
use App\Models\Equipo;
use Carbon\Carbon;

class SeccionesEstante extends Model
{
    use HasFactory;

    protected $table = 'seccionesestante';

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_estante',
    ];

    public function estante(){
        return $this->belongsTo(Estante::class, 'id_estante', 'id');
    }

    public function equipos(){
        return $this->hasMany(Equipo::class, 'id_seccionestante', 'id');
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
