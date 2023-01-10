<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrdenServicio;

class OrdenPresupuesto extends Model
{
    use HasFactory;

    protected $table = 'ordenservicios_presupuestos';

    protected $fillable = [
        'presupuesto',
        'presupuestado',   
        'id_orden',
    ];

    public $timestamps = false;

    public function orden()
    {
        return $this->belongsTo(OrdenServicio::class, 'id_orden', 'id');
    }
}
