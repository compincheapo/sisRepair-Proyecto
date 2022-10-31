<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SeccionesEstante;

class Estante extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public $timestamps = false;

    public function seccionesEstante(){
        return $this->hasMany(SeccionesEstante::class, 'id_estante', 'id');
    }
}
