<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TipoRepuesto;
use App\Models\Marca;
use App\Models\SeccionesEstante;

class Repuesto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'serie',
        'modelo',
        'cantidad',   
        'precio',
        'descripcion',
    ];

    protected $guarded = [];

    public $timestamps = false;

    public function seccionEstante(){
        return $this->belongsTo(SeccionesEstante::class, 'id_seccionestante', 'id');
    }

    public function marca(){
        return $this->belongsTo(Marca::class, 'id_marca', 'id');
    }

    public function tiporepuesto(){
        return $this->belongsTo(TipoRepuesto::class, 'id_tiporepuesto', 'id');
    }
}
