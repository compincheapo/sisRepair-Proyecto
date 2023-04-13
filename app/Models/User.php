<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Equipo;
use App\Models\OrdenServicio;
//spatie
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'lastname',
        'username',
        'password',
        'numero',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function equipos(){
        return $this->hasMany(Equipo::class, 'id_user', 'id');
    }

    public function estadosEquipos()
    {
        return $this->belongsToMany(
            Estado::class, 'equipos_estados_users_ordenes', 'id_estado', 'id'
        );
    }

    public function equiposEstados()
    {
        return $this->belongsToMany(
            Equipo::class, 'equipos_estados_users_ordenes', 'id_equipo', 'id'
        );
    }

    public function ordenesEquipo()
    {
        return $this->belongsToMany(
            OrdenServicio::class, 'equipos_estados_users_ordenes', 'id_user', 'id_orden'
        );
    }

    public function ordenesAsignadas(){
        return $this->belongsToMany(OrdenServicio::class, 'users_ordenes', 'id_user', 'id_orden');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }

    public function getEmailverifiedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }
}
