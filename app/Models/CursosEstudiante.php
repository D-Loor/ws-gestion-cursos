<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursosEstudiante extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table = 'cursos_estudiantes';
    protected $primaryKey = 'id_curso_estudiante';

    protected $fillable = [
        'id_curso',
        'id_estudiante',
        'estado',
        'fecha_inscripcion',
        'fecha_finalizacion',
    ];

    public function estudiante() {
        return $this->belongsTo(Estudiante::class, 'id_estudiante', 'id_estudiante')->with('usuario');
    }

    public function curso() {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso')->with('modalidad');
    }
    
}
