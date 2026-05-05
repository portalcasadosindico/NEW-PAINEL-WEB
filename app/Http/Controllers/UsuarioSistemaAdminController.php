<?php

namespace App\Http\Controllers;

use App\Models\UsuarioSistemaAdmin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UsuarioSistemaAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UsuarioSistemaAdmin  $usuarioSistemaAdmin
     * @return \Illuminate\Http\Response
     */
    public function show(UsuarioSistemaAdmin $usuarioSistemaAdmin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UsuarioSistemaAdmin  $usuarioSistemaAdmin
     * @return \Illuminate\Http\Response
     */
    public function edit(UsuarioSistemaAdmin $usuarioSistemaAdmin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UsuarioSistemaAdmin  $usuarioSistemaAdmin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UsuarioSistemaAdmin $usuarioSistemaAdmin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UsuarioSistemaAdmin  $usuarioSistemaAdmin
     * @return \Illuminate\Http\Response
     */
    public function destroy(UsuarioSistemaAdmin $usuarioSistemaAdmin)
    {
        //
    }

    /** Metodos para atualizar o perfil do admin_usuario_sistema_admin */

    public function profilePage()
    {
        $usuario_sistema_admin = UsuarioSistemaAdmin::findOrFail(Auth::guard('admins')->user()->id);
        return view('admin.perfil.show', compact('usuario_sistema_admin'));
    }
    public function updateProfilePage()
    {
        $usuario_sistema_admin = UsuarioSistemaAdmin::findOrFail(Auth::guard('admins')->user()->id);
        return view('admin.perfil.edit', compact('usuario_sistema_admin'));
    }
    public function updateProfile(Request $request)
    {
        try {
            $usuario_sistema_admin = UsuarioSistemaAdmin::findOrFail(Auth::guard('admins')->user()->id);
            $usuario_sistema_admin->nome = $request['nome'];
            $usuario_sistema_admin->email = $request['email'];
            if($request['senha'] != null){
                $usuario_sistema_admin->senha = Hash::make($request['senha']);
            }
            $usuario_sistema_admin->update();
            return redirect()->route('admin.index')
                ->with('success_message', 'Perfil foi atualizado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Erro ao tentar editar perfil');
        }
    }
}
