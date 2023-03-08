<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Repuesto;
use Carbon\Carbon;

class TipoRepuesto extends Model
{
    use HasFactory;

    protected $table = 'tiporepuestos';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }

}
