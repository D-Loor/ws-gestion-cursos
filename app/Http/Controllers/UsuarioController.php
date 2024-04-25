<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class UsuarioController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function crearUsuario(Request $request)
    {
        try{
            $usuario = new Usuario();
            $usuario->fill($request->usuario);
            $usuario->save();
            return  $usuario->id_usuario;

        }catch (\Exception $e){
            return 0;
        }
    }

    public function actualizarUsuario(Request $request, $id_usuario)
    {
        try{
            $usuarioExistente = Usuario::where('email', $request->email)->where('id_usuario', '!=', $id_usuario)->get()->first();
            if ($usuarioExistente) {
                return 0;
            }
            $usuario = Usuario::find($request->id_usuario);
            $usuario->fill($request->usuario);
            $usuario->update();
            return  1;

        }catch (\Exception $e){
            return 0;
        }
    }

    public function eliminarUsuario($id_usuario)
    {
        try{
            $usuario = Usuario::find($id_usuario);
            $usuario->delete();
            return  1;

        }catch (\Exception $e){
            return 0;
        }
    }
}
