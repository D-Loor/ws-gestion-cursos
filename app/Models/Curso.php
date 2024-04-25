<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table = 'cursos';
    protected $primaryKey = 'id_curso';

    protected $fillable = [
        'id_modalidad',
        'nombre',
        'hora_inicio',
        'hora_fin',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'inscritos'
    ];

    public function cursosEstudiante() {
        return $this->hasOne(CursosEstudiante::class,'id_curso','id_curso');
    }

    public function modalidad() {
        return $this->belongsTo(Modalidad::class, 'id_modalidad', 'id_modalidad');
    }

    public function estudiantes(){
        return $this->hasManyThrough(
            Estudiante::class, 
            CursosEstudiante::class, 
            'id_curso', 
            'id_estudiante', 
            'id_curso', 
            'id_estudiante')->with('usuario');
    }

}
