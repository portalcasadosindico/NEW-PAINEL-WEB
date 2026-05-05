<?php

namespace App\Http\Controllers;

use App\Models\Afiliado;
use App\Models\AfiliadoRegiao;
use App\Models\ResponsavelAfiliado;
use App\Models\Sindico;
use App\Models\UsuarioApp;
use App\Uteis\Formatacao;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class UsuarioAppController extends Controller
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
     * @param  \App\Models\UsuarioApp  $usuarioApp
     * @return \Illuminate\Http\Response
     */
    public function show(UsuarioApp $usuarioApp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UsuarioApp  $usuarioApp
     * @return \Illuminate\Http\Response
     */
    public function edit(UsuarioApp $usuarioApp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UsuarioApp  $usuarioApp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UsuarioApp $usuarioApp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UsuarioApp  $usuarioApp
     * @return \Illuminate\Http\Response
     */
    public function destroy(UsuarioApp $usuarioApp)
    {
        //
    }

    public function getData(Request $request)
    {
        $rules = [
            'senha' => 'required|string',
            'email' => 'required|string'
        ];

        $data = $request->validate($rules);

        return $data;
    }

    public function reenviarEmailConfirmarcao($usuario_id)
    {
        try {
            $usuarioApp = UsuarioApp::where("id", $usuario_id)->first();
            $nome = "";
            if ($usuarioApp->tipo == "afiliado") {
                $afiliado = Afiliado::where("usuario_app_id", $usuarioApp->id)->first();
                $nome = $afiliado->razao_social;
                if ($nome == "") {
                    $responsavelAfiliado = ResponsavelAfiliado::where("afiliado_id", $afiliado->id)->first();
                    $nome = $responsavelAfiliado->nome;
                }
            } else if ($usuarioApp->tipo == "sindico") {
                $sindico = Sindico::where("usuario_app_id", $usuarioApp->id)->first();
                $nome = $sindico->nome;
            }

            $res = SenderEmails::emailConfirmacao($usuarioApp->email, $nome, $usuarioApp->id);

            $data = null;
            if ($res) {
                $res = true;
                $data = Carbon::now();
                $usuarioApp->ultimo_envio_email = $data;
                $usuarioApp->save();
                $data = Formatacao::data($data);
            }
            return ["status" => $res, "data" => "Último envio " . $data];
        } catch (Exception $e) {
            return $e;
        }
    }
}
