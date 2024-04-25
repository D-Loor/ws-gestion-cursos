<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalInstituto;
use App\Http\Controllers\UsuarioController;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PersonalInstitutoController
{
    protected $reglasValidacion = [
        'usuario.nombre' => 'required|string|max:100',
        'usuario.apellido' => 'required|string|max:100',
        'usuario.email' => 'required|string|email|unique:usuarios,email',       
    ];
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $response = PersonalInstituto::with('usuario', 'rol')->get();

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
            $reglasEspecificas = $this->reglasValidacion;
            $reglasEspecificas['password'] = [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*\d)(?=.*[A-Z])(?=.*[@$!%*?&.-_#])[\w@$!%*?&.-_#]+$/'
            ];
    
            $validator = Validator::make($request->all(), $reglasEspecificas);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors(), 'code' => '400']);
            }

            $usuarioController = new UsuarioController();
            $usuarioCreado = $usuarioController->crearUsuario($request);
            if ($usuarioCreado == 0) {
                return response()->json(['result' => "Usuario ya se encuentra registrado", 'code' => '409']);
            }
            $personal = new PersonalInstituto();
            $personal->id_rol = $request->id_rol;
            $personal->id_usuario = $usuarioCreado;
            $personal->password = Hash::make($request->password);
            $personal->save();

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
            $reglasEspecificas['id_personal_instituto'] = ['required','integer','exists:personal_instituto,id_personal_instituto'];
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

            $data = PersonalInstituto::find($request->id_personal_instituto);
            $usuarioController = new UsuarioController();
            $usuarioActualizado = $usuarioController->actualizarUsuario($request, $data->id_usuario);

            if ($usuarioActualizado == 0) {
                return response()->json(['result' => "Usuario ya se encuentra registrado", 'code' => '409']);
            }
            $data->id_rol = $request->id_rol;
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
            $response = PersonalInstituto::find($id);
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

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors(), 'code' => '400']);
            }

            $personal = PersonalInstituto::whereHas('usuario', function ($query) use ($request) {
                $query->where('email', $request->email);
            })->with('usuario','rol')->first();

            if ($personal && password_verify($request->password, $personal->password)) {
                Auth::loginUsingId($personal->id_usuario);
                $token = $personal->createToken('accessToken')->plainTextToken;
                return response()->json(['result' => $personal, 'code' => '200', 'token' => $token]);
            } else
                return response()->json(['mensaje' => "No Autorizado", 'code' => '401']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }

    public function validToken()
    {
        try {
            $user = Auth::user();
            $rol = PersonalInstituto::where('id_usuario', $user->id_usuario)->with('rol')->first();

            return response()->json(['result' => $rol, 'code' => '200']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user(); // Obtener el usuario autenticado

            if ($user) {
                Auth::logout(); // Cerrar la sesión del usuario
                $request->session()->invalidate(); // Invalidar la sesión actual
                $request->session()->regenerateToken(); // Generar un nuevo token de sesión

                return response()->json(['result' => 'Sesión cerrada', 'code' => '200']);
            } else {
                return response()->json(['result' => 'No se pudo autenticar al usuario', 'code' => '401']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }

    public function getPersonalByRol(string $rol)
    {
        try {
            $response = PersonalInstituto::whereHas('rol', function ($query) use ($rol) {
                $query->where('rol', $rol);
            })->with('usuario')->get();

            if ($response) {
                return response()->json(['result' => $response, 'code' => '200']);
            } else
                return response()->json(['mensaje' => "No hay registros", 'code' => '404']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }
}
