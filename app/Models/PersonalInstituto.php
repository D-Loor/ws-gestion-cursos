<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class PersonalInstituto extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps=false;
    protected $table = 'personal_instituto';
    protected $primaryKey = 'id_personal_instituto';

    protected $fillable = [
        'id_usuario',
        'id_rol',
    ];

    protected $hidden = ['password'];


    public function usuario() {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function rol() {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }
}
