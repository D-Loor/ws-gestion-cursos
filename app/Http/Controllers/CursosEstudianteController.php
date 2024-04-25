<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\CursosEstudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CursosEstudianteController
{
    protected $reglasValidacion = [
        'id_curso' => 'required|integer|exists:cursos,id_curso',
        'id_estudiante' => 'required|integer|exists:estudiantes,id_estudiante',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $response = CursosEstudiante::with('curso', 'estudiante')->get();
            if ($response) {
                return response()->json(['result' => $response, 'code' => '200']);
            }
            return response()->json(['mensaje' => "No hay registros", 'code' => '404']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->reglasValidacion);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors(), 'code' => '400']);
            }

            $validarRegistro = CursosEstudiante::where('id_curso', $request->id_curso)
            ->where('id_estudiante', $request->id_estudiante)
            ->where('fecha_finalizacion', null)
            ->first();

            if ($validarRegistro) 
                return response()->json(['result' => "Estudiante ya se encuentra inscrito a este curso", 'code' => '409']);

            $cursoEstudiante = new CursosEstudiante();
            $cursoEstudiante->fill($request->all());
            $cursoEstudiante->save();

            $curso = Curso::find($request->id_curso);
            $curso->inscritos = $curso->inscritos + 1;
            $curso->update();

            return response()->json(['result' => "Dato Registrado", 'code' => '200']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $reglasEspecificas = $this->reglasValidacion;
            $reglasEspecificas['id_curso_estudiante'] = 'required|integer|exists:cursos_estudiantes,id_curso_estudiante';

            $validator = Validator::make($request->all(), $reglasEspecificas);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors(), 'code' => '400']);
            }

            $cursoEstudiante = CursosEstudiante::find($request->id_curso_estudiante);
            $cursoEstudiante->fill($request->all());

            if ($cursoEstudiante->id_curso != $request->id_curso) {
                $cursoAnterior = Curso::find($cursoEstudiante->id_curso);
                $cursoAnterior->inscritos = $cursoAnterior->inscritos - 1;
                $cursoAnterior->update();

                $cursoNuevo = Curso::find($request->id_curso);
                $cursoNuevo->inscritos = $cursoNuevo->inscritos + 1;
                $cursoNuevo->update();
            }
            $cursoEstudiante->update();

            return response()->json(['result' => "Dato Registrado", 'code' => '200']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = CursosEstudiante::find($id);
            if ($data) {
                $curso = Curso::find($data->id_curso);
                $curso->inscritos = $curso->inscritos - 1;
                $curso->update();
                $data->delete();
                return response()->json(['result' => "Dato Eliminado", 'code' => '200']);
            }
            return response()->json(['result' => "Registro no encontrado", 'code' => '404']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }

    
    public function getCursosByEstudiante(string $id)
    {
        try {
            $response = CursosEstudiante::with('curso')
            ->where('id_estudiante', $id)
            ->get();
            if ($response) {
                return response()->json(['result' => $response, 'code' => '200']);
            }
            return response()->json(['mensaje' => "No hay registros", 'code' => '404']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }
}
