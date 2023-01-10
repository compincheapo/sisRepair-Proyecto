<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pago;

class TipoPago extends Model
{
    use HasFactory;

    protected $table = 'tipopagos';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public $timestamps = false;

    public function pagos(){
        return $this->hasMany(Pago::class, 'id_tipopago', 'id');
    }

}
