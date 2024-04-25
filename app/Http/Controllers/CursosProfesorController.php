<?php

namespace App\Http\Controllers;

use App\Models\CursosProfesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CursosProfesorController
{
    protected $reglasValidacion = [
        'id_curso' => 'required|integer|exists:cursos,id_curso',
        'id_personal_instituto' => 'required|integer|exists:personal_instituto,id_personal_instituto',
        'fecha_asignacion' => 'required|date',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $response = CursosProfesor::with('curso', 'profesor')->get();
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

            $validarRegistro = CursosProfesor::where('id_curso', $request->id_curso)
                ->where('id_personal_instituto', $request->id_personal_instituto)
                ->where('fecha_finalizacion', null)
                ->first();

            if ($validarRegistro)
                return response()->json(['result' => "Profesor ya se encuentra asignado a este curso", 'code' => '409']);

            $cursoProfesor = new CursosProfesor();
            $cursoProfesor->fill($request->all());
            $cursoProfesor->save();
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
            $reglasEspecificas['id_curso_profesor'] = 'required|integer|exists:cursos_profesores,id_curso_profesor';

            $validator = Validator::make($request->all(), $reglasEspecificas);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors(), 'code' => '400']);
            }

            $cursoProfesor = CursosProfesor::find($request->id_curso_profesor);
            $cursoProfesor->fill($request->all());
            $cursoProfesor->update();

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
            $data = CursosProfesor::find($id);
            if ($data) {
                $data->delete();
                return response()->json(['result' => "Dato Eliminado", 'code' => '200']);
            }
            return response()->json(['result' => "Registro no encontrado", 'code' => '404']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }
}
