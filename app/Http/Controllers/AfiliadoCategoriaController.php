<?php

namespace App\Http\Controllers;

use App\Models\Afiliado;
use App\Models\AfiliadoCategoria;
use App\Models\Notificacao;
use Illuminate\Http\Request;

class AfiliadoCategoriaController extends Controller
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
     * @param  \App\Models\AfiliadoCategoria  $afiliadoCategoria
     * @return \Illuminate\Http\Response
     */
    public function show(AfiliadoCategoria $afiliadoCategoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AfiliadoCategoria  $afiliadoCategoria
     * @return \Illuminate\Http\Response
     */
    public function edit(AfiliadoCategoria $afiliadoCategoria)
    {
        //
    }
    public function alterar_status($afiliado_categoria_id, Request $request){
        $categoria_afiliado = AfiliadoCategoria::where("id", $afiliado_categoria_id)->where("status","pendente")->first();
        if($categoria_afiliado){
            $afiliado = Afiliado::where("id", $categoria_afiliado->afiliado_id)->first();
            if($afiliado){
                $usuarioApp = $afiliado->usuarioApp;
            }
            
            if($request['status']=="aprovado"){
                $categoria_afiliado->status = "aprovado";
                $categoria_afiliado->franqueado_id = isset($this->user_franqueado->id) ? $this->user_franqueado->id : null;
                $categoria_afiliado->update();
                
                
                $res = "";
                if($usuarioApp && $usuarioApp->token_notification)
                    $res = FCM::send($usuarioApp->token_notification, "Casa do Síndico", "Sua categoria ".$categoria_afiliado->categoria->nome." foi aprovada.", 
                    ["tipo"=>"navegacao", "texto_botao"=>"Ver", "url_botao"=>"afiliado/categoria/listar", "categoria_afiliado" => $categoria_afiliado]);
                
                Notificacao::painelNotificarAfiliadoCategoriaAprovadaReprovada($categoria_afiliado->categoria, $afiliado, "aprovado");
                return ["res"=>true, "user" => $res];
            }elseif($request['status']=="reprovado"){
                $motivo = $request['motivo'];
                $categoria_afiliado->status = "reprovado";
                $categoria_afiliado->motivo_reprovado = $motivo;
                $categoria_afiliado->franqueado_id = isset($this->user_franqueado->id) ? $this->user_franqueado->id : null;
                $categoria_afiliado->update();
                $res = "";
                if($usuarioApp && $usuarioApp->token_notification)
                    $res = FCM::send($usuarioApp->token_notification, "Casa do Síndico", "Sua categoria ".$categoria_afiliado->categoria->nome." foi reprovada. ". $categoria_afiliado->motivo_reprovado, 
                    ["tipo"=>"navegacao", "texto_botao"=>"Ver", "url_botao"=>"afiliado/categoria/listar", "categoria_afiliado" => $categoria_afiliado]);
                
                Notificacao::painelNotificarAfiliadoCategoriaAprovadaReprovada($categoria_afiliado->categoria, $afiliado, "reprovado", $motivo);
                return ["res"=>true, "user" => $res];
            }
            return ["res"=>false];
        }
        return ["res"=>false];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AfiliadoCategoria  $afiliadoCategoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AfiliadoCategoria $afiliadoCategoria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AfiliadoCategoria  $afiliadoCategoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(AfiliadoCategoria $afiliadoCategoria)
    {
        //
    }
}
