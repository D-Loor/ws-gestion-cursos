<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EstudianteController
{
    protected $reglasValidacion = [
        'usuario.nombre' => 'required|string|max:100',
        'usuario.apellido' => 'required|string|max:100',
        'usuario.email' => 'required|string|email|unique:usuarios,email',
        'ci' => 'required|string|max:11',
        'edad' => 'required|integer|min:18'
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $response = Estudiante::with('usuario')->with('cursos')->get();

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

            $usuarioController = new UsuarioController();
            $usuarioCreado = $usuarioController->crearUsuario($request);
            if ($usuarioCreado == 0) {
                return response()->json(['result' => "Usuario ya se encuentra registrado", 'code' => '409']);
            }
            $estudiante = new Estudiante();
            $estudiante->id_usuario = $usuarioCreado;
            $estudiante->edad = $request->edad;
            $estudiante->ci = $request->ci;
            $estudiante->save();

            return response()->json(['result' => 'Dato Registrado', 'code' => '200']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
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
            $reglasEspecificas['id_estudiante'] = 'required|integer|exists:estudiantes,id_estudiante';
            $reglasEspecificas['usuario.email'] = [
                'required',
                'string',
                'email',
                Rule::unique('usuarios', 'email')->ignore($request->id_usuario, 'id_usuario')
            ];

            $validator = Validator::make($request->all(), $reglasEspecificas);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors(), 'code' => '400']);
            }

            $data = Estudiante::find($request->id_estudiante);
            if ($data) {
                $usuarioController = new UsuarioController();
                $usuarioActualizado = $usuarioController->actualizarUsuario($request, $data->id_usuario);
                if ($usuarioActualizado == 0) {
                    return response()->json(['result' => "Estudiante ya se encuentra registrado", 'code' => '409']);
                }
                $data->edad = $request->edad;
                $data->ci = $request->ci;
                $data->update();
                return response()->json(['result' => "Dato Actualizado", 'code' => '200']);
            }
            return response()->json(['result' => "Registro no encontrado", 'code' => '404']);
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
            $response = Estudiante::find($id);
            if ($response) {
                $usuarioController = new UsuarioController();
                $id_usuario = $response->id_usuario;
                $response->delete();
                if ($usuarioController->eliminarUsuario($id_usuario) == 1) {
                    return response()->json(['result' => "Dato Eliminado", 'code' => '200']);
                }
            }
            return response()->json(['result' => "Registro no encontrado", 'code' => '404']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }

    public function topEstudiantesCursos($nTop)
    {
        try {
            $topEstudiantes = Estudiante::withCount('cursosEstudiante')
                ->orderByDesc('cursos_estudiante_count')
                ->take($nTop)
                ->with('usuario')
                ->get();

            if ($topEstudiantes) {
                return response()->json(['result' => $topEstudiantes, 'code' => '200']);
            } else
                return response()->json(['mensaje' => "No hay registros", 'code' => '404']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }

    public function totalEstudiantes()
    {
        try {
            $totalEstudiantes = Estudiante::count();
            return response()->json(['result' => $totalEstudiantes, 'code' => '200']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }
}
