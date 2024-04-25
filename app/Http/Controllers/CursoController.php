<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class CursoController
{
    protected $reglasValidacion = [
        'id_modalidad' => 'required|integer',
        'nombre' => 'required|string|max:50',
        'hora_inicio' => 'required|date_format:H:i:s',
        'hora_fin' => 'required|date_format:H:i:s|after:hora_inicio',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after:fecha_inicio',
        'inscritos' => 'required|integer',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $response = Curso::with('modalidad', 'estudiantes')->get();
            if ($response) {
                return response()->json(['result' => $response, 'code' => '200']);
            } else
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

            $curso = new Curso();
            $curso->fill($request->all());
            $curso->save();
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
            $reglasEspecificas['id_curso'] = 'required|integer|exists:cursos,id_curso';
            $validator = Validator::make($request->all(), $reglasEspecificas);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors(), 'code' => '400']);
            }

            $data = Curso::find($request->id_curso);
            $data->fill($request->all());
            $data->update();
            return response()->json(['result' => "Dato Actualizado", 'code' => '200']);
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
            $response = Curso::find($id);
            if ($response) {
                $response->delete();
                return response()->json(['result' => "Dato Eliminado", 'code' => '200']);
            }
            return response()->json(['result' => "Registro no encontrado", 'code' => '404']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }

    public function topCursosEstudiantes($nTop, $nMeses)
    {
        try {
            $fechaActual = Carbon::now();
            $fechaInicio = $fechaActual->subMonths($nMeses);

            $cursos = Curso::where('fecha_inicio', '>=', $fechaInicio)
                ->orderByDesc('inscritos')
                ->take($nTop)
                ->with('modalidad')
                ->get();

            if ($cursos) {
                return response()->json(['result' => $cursos, 'code' => '200']);
            } else
                return response()->json(['mensaje' => "No hay registros", 'code' => '404']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }

    public function totalCursos()
    {
        try {
            $totalCursos = Curso::count();
            return response()->json(['result' => $totalCursos, 'code' => '200']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }

    public function getCursosActivos()
    {
        try {
            $cursos = Curso::where('estado', 1)->with('modalidad')->get();

            if ($cursos) {
                return response()->json(['result' => $cursos, 'code' => '200']);
            } else
                return response()->json(['mensaje' => "No hay registros", 'code' => '404']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }
}
