<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolController
{
    protected $reglasValidacion = [
        'nombre' => 'required|string|max:50|unique:roles,nombre',
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $response = Rol::get();
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

            $data = new Rol();
            $data->fill($request->all());
            $data->save();
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
            $reglasEspecificas['id_rol'] = 'required|integer|exists:roles,id_rol';
            $validator = Validator::make($request->all(), $reglasEspecificas);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors(), 'code' => '400']);
            }

            $data = Rol::find($request->id_rol);
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
            $response = Rol::find($id);
            if ($response) {
                $response->delete();
                return response()->json(['result' => "Dato Eliminado", 'code' => '200']);
            }
            return response()->json(['result' => "Registro no encontrado", 'code' => '404']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'code' => '500']);
        }
    }
}
