<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table = 'estudiantes';
    protected $primaryKey = 'id_estudiante';

    protected $fillable = [
        'id_usuario',
        'edad',
        'ci',
    ];

    public function cursosEstudiante() {
        return $this->hasMany(CursosEstudiante::class, 'id_estudiante', 'id_estudiante');
    }

    public function usuario() {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function cursos() {
        return $this->hasManyThrough(Curso::class , CursosEstudiante::class, 'id_estudiante', 'id_curso', 'id_estudiante', 'id_curso');
    }

}
