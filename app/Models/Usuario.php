<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
    ];

    public function personalInstituto() {
        return $this->hasMany(PersonalInstituto::class, 'id_usuario', 'id_usuario');
    }

}
