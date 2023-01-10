<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SeccionesEstante;
use App\Models\Marca;
use App\Models\TipoEquipo;
use App\Models\User;
use App\Models\TipoAccesorio;
use App\Models\Estado;
use App\Models\OrdenServicio;
use OwenIt\Auditing\Contracts\Auditable;

class Equipo extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'serie',
        'modelo',   
        'id_seccionestante',
        'id_marca',
        'id_tipoequipo',
        'id_user',
    ];

    protected $guarded = [];

    public $timestamps = false;


    public function seccionEstante(){
        return $this->belongsTo(SeccionesEstante::class, 'id_seccionestante', 'id');
    }

    public function marca(){
        return $this->belongsTo(Marca::class, 'id_marca', 'id');
    }
    
    public function tipoEquipo(){
        return $this->belongsTo(TipoEquipo::class, 'id_tipoequipo', 'id');
    }
    
    public function user(){
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function accesorios(){
        return $this->belongsToMany(
            TipoAccesorio::class,
            'equipos_accesorios',
            'id_equipo',
            'id_accesorio'
            );
    }

    public function estados()
    {
        return $this->belongsToMany(
            Estado::class, 'equipos_estados_users_ordenes', 'id_estado', 'id'
        );
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class, 'equipos_estados_users_ordenes', 'id_user', 'id'
        );
    
    }
    public function ordenes()
    {
        return $this->belongsToMany(
            OrdenServicio::class, 'equipos_estados_users_ordenes', 'id_orden', 'id'
        );
    }

    public function orden(){
        return $this->hasMany(OrdenServicio::class, 'id_equipo', 'id');
    }

}
