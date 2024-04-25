<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursosProfesor extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table = 'cursos_profesores';
    protected $primaryKey = 'id_curso_profesor';

    protected $fillable = [
        'id_curso',
        'id_personal_instituto',
        'estado',
        'fecha_asignacion',
        'fecha_finalizacion',
    ];

    public function curso() {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso')->with('modalidad');
    }

    public function profesor() {
        return $this->belongsTo(PersonalInstituto::class, 'id_personal_instituto', 'id_personal_instituto')->with('usuario');
    }
    
}
