<?php

use App\Http\Controllers\CursoController;
use App\Http\Controllers\CursosEstudianteController;
use App\Http\Controllers\CursosProfesorController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\ModalidadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonalInstitutoController;
use App\Http\Controllers\RolController;

Route::post('login', [PersonalInstitutoController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {

    Route::resource('estudiante', EstudianteController::class);
    Route::resource('persona-instituto', PersonalInstitutoController::class);
    Route::resource('curso', CursoController::class);
    Route::resource('modalidad', ModalidadController::class);
    Route::resource('rol', RolController::class);
    Route::resource('curso-estudiantes', CursosEstudianteController::class);
    Route::resource('curso-profesores', CursosProfesorController::class);
    Route::get('top-cursos-estudiantes/{nTop}/{nMeses}', [CursoController::class, 'topCursosEstudiantes']);
    Route::get('top-estudiantes-cursos/{nTop}', [EstudianteController::class, 'topEstudiantesCursos']);
    Route::get('total-cursos', [CursoController::class, 'totalCursos']);
    Route::get('total-estudiantes', [EstudianteController::class, 'totalEstudiantes']);

    Route::get('modalidades-activas', [ModalidadController::class, 'getModalidadesActivas']);
    Route::get('cursos-activos', [CursoController::class, 'getCursosActivos']);
    Route::get('obtener-personal-rol/{rol}', [PersonalInstitutoController::class, 'getPersonalByRol']);

    Route::get('obtener-cursos-estudiante/{id}', [CursosEstudianteController::class, 'getCursosByEstudiante']);

    Route::get('validar-token', [PersonalInstitutoController::class, 'validToken']);
    Route::get('logout/{id}', [PersonalInstitutoController::class, 'logout'])->name('logout');
});
