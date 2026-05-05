<?php

namespace App\Http\Controllers;

use App\Models\Afiliado;
use App\Models\AfiliadoRegiao;
use App\Models\Categoria;
use App\Models\Condominio;
use App\Models\Notificacao;
use App\Models\Notificacoes;
use App\Models\NotificacoesLog;
use App\Models\Regiao;
use App\Models\RegiaoFaixaCep;
use App\Models\Sindico;
use App\Models\UsuarioApp;
use App\Uteis\Formatacao;
use App\Uteis\RotasApp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class NotificacoesController extends Controller
{

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        //$notificacoes = Notificacoes::orderBy("id","desc")->get();
        $notificacoes = Notificacoes::orderBy("id","desc")->paginate(25);
        return view('notificacoes.index', compact('notificacoes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::where("categoria_pai_id", ">", 0)->orderBy("nome","asc")->get();
        $regioes = Regiao::orderBy("nome","asc")->where("id","<>",1)->where("id","<>",12)->get();
        $sindicos = Sindico::orderBy("nome","ASC")->with("usuarioApp")->get();
        $afiliados = Afiliado::orderBy("razao_social","ASC")->with("usuarioApp")->get();

        return view('notificacoes.create', compact('regioes', 'sindicos', 'afiliados', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		if(isset($request['cancel'])){
            return redirect()->route('notificacoes.index');
        }
        try {

            $linkInterno = null;
            $labelInterno = null;
            $paramLinkInterno = null;
            $paramNameLinkInterno=null;
            if($request['categoria_id']==""){
                //Sem botão
                $categoria = null;
            } else if($request['categoria_id']==0){
                //Sem categoria
                $categoria = null;
                $linkInterno = RotasApp::$ORCAMENTO_FORMULARIO;
                $labelInterno = "Solicite agora";
                $paramLinkInterno = null;
                $paramNameLinkInterno="categoria_selecionada";
            }else if($request['categoria_id']>0){
                $categoria = Categoria::where("id", $request['categoria_id'])->first();
                $linkInterno = RotasApp::$ORCAMENTO_FORMULARIO;
                $labelInterno = "Solicite agora";
                $paramLinkInterno = $categoria;
                $paramNameLinkInterno="categoria_selecionada";
            }
            

            $isSendgrupo = $request['isSendGrupo'];


            if($isSendgrupo=="1"){
                $enviarSindicos = isset($request['grupo-sindico']) ? true : false;
                $enviarAfiliados = isset($request['grupo-afiliado']) ? true : false;
                $regioes = isset($request['regioes']) ? $request['regioes'] : null;
                
                if($regioes){
                    foreach($regioes as $regiao_id){

                        #REALIZA ENVIO PARA OS AFILIADOS
                        if($enviarAfiliados){
                            $afiliadosNotificar = [];
                            $afiliadosRegiao = AfiliadoRegiao::where("regiao_id", $regiao_id)->get();
                            foreach($afiliadosRegiao as $afiliadoRegiao){
                                if(!isset($afiliadosNotificar[$afiliadoRegiao->afiliado_id])){
                                    $afilAux = Afiliado::where("id", $afiliadoRegiao->afiliado_id)->first();
                                    if($afilAux){
                                        $afiliadosNotificar[$afiliadoRegiao->afiliado_id] = $afilAux;
                                    }
                                }
                            }

                            foreach($afiliadosNotificar as $afiliado){
                                
                                if($afiliado){
                                    $nome = $afiliado->razao_social;
                                    $usuario = UsuarioApp::where("id", $afiliado->usuario_app_id)->first();
                                    
                                    if($usuario){

                                        $notificacao = new Notificacoes();
                                        $notificacao->titulo = $request['titulo'];
                                        $notificacao->link_interno_app = $linkInterno;
                                        $notificacao->link_interno_app_label = $labelInterno;
                                        $notificacao->param_link_interno = $paramLinkInterno;
                                        $notificacao->param_name = $paramNameLinkInterno;
                                        $notificacao->corpo = $request['corpo'];
                                        $notificacao->isSendEmail = isset($request['isSendEmail']) ? true : false;
                                        $notificacao->isSendNotification = isset($request['isSendNotification']) ? true : false;
                                        $notificacao->usuario_app_id = $usuario->id;
                                        $notificacao->tipo_usuario = "afiliado";
                                        $notificacao->save();


                                        if($notificacao->isSendEmail){
                                            $res = SenderEmails::emailNotification($notificacao->titulo, $notificacao->corpo, $usuario->email, $nome);
                                        
                                            $notificao_log = new NotificacoesLog();
                                            $notificao_log->tipo = "email";
                                            $notificao_log->nome = $nome;
                                            $notificao_log->notificacoes_id = $notificacao->id;
                                            $notificao_log->usuario_app_id = $usuario->id;
                                            $notificao_log->email = $usuario->email;
                                            if($res){
                                                $notificao_log->statusEnvioEmail = 1;
                                            } else {
                                                $notificao_log->statusEnvioEmail = 0;
                                            }
                                            $notificao_log->save();
                    
                                        }
                                        if($notificacao->isSendNotification && $usuario->token_notification){
                                            $res = FCM::send($usuario->token_notification, $notificacao->titulo, $notificacao->corpo, ["tipo" => "alert"]);   
                                            $notificao_log = new NotificacoesLog();
                                            $notificao_log->tipo = "notification";
                                            $notificao_log->nome = $nome;
                                            $notificao_log->notificacoes_id = $notificacao->id;
                                            $notificao_log->usuario_app_id = $usuario->id;
                                            $notificao_log->email = $usuario->email;
                                            if($res){
                                                $notificao_log->statusEnvioNotificacao = 1;
                                            } else {
                                                $notificao_log->statusEnvioNotificacao = 0;
                                            }
                                            $notificao_log->save();
                                        }
                                            
                                    }
                                }
                            }
                        }


                        #REALIZA ENVIO PARA OS SINDICOS
                        if($enviarSindicos){
                                $sindicosAll = Sindico::get();
                                foreach($sindicosAll as $sindico){
                                    if(isset($sindico->nome)){
                                        $nome = $sindico->nome;
                                    } else {
                                        $nome = "";
                                    }
                                    
                                    $condominios = Condominio::where("sindico_id", $sindico->id)->get();
                                    foreach($condominios  as $c){
                                        if($c->bairro()){
                                            $bairro = $c->bairro()->first();
                                            $regiao_sindico_id = -1;
                                            if($bairro && $bairro->regiao_id){
                                                $regiao_sindico_id = $bairro->regiao_id;
                                            } elseif($bairro) {
                                                $faixaCep = RegiaoFaixaCep::where("cidade_id", $bairro->cidade_id)->first();
                                                if($faixaCep)
                                                    $regiao_sindico_id = $faixaCep->regiao_id;
                                            }  

                                            if($regiao_sindico_id==$regiao_id){
                                                $usuario = UsuarioApp::where("id", $sindico->usuario_app_id)->first();
                                                if($usuario){
                                                    $notificacao = new Notificacoes();
                                                    $notificacao->titulo = $request['titulo'];
                                                    $notificacao->link_interno_app = $linkInterno;
                                                    $notificacao->link_interno_app_label = $labelInterno;
                                                    $notificacao->param_link_interno = $paramLinkInterno;
                                                    $notificacao->param_name = $paramNameLinkInterno;
                                                    $notificacao->corpo = $request['corpo'];
                                                    $notificacao->isSendEmail = isset($request['isSendEmail']) ? true : false;
                                                    $notificacao->isSendNotification = isset($request['isSendNotification']) ? true : false;
                                                    $notificacao->usuario_app_id = $usuario->id;
                                                    $notificacao->tipo_usuario = "afiliado";
                                                    $notificacao->save();
        
                                                    $notifacaoLogAux = NotificacoesLog::where("notificacoes_id", $notificacao->id)->where("email", $usuario->email)->first();
                                                    if(!$notifacaoLogAux){
                                                        if($notificacao->isSendEmail){
                                                            $res = SenderEmails::emailNotification($notificacao->titulo, $notificacao->corpo, $usuario->email, $nome);
                                                        
                                                            $notificao_log = new NotificacoesLog();
                                                            $notificao_log->tipo = "email";
                                                            $notificao_log->nome = $nome;
                                                            $notificao_log->notificacoes_id = $notificacao->id;
                                                            $notificao_log->usuario_app_id = $usuario->id;
                                                            $notificao_log->email = $usuario->email;
                                                            if($res){
                                                                $notificao_log->statusEnvioEmail = 1;
                                                            } else {
                                                                $notificao_log->statusEnvioEmail = 0;
                                                            }
                                                            $notificao_log->save();
                                    
                                                        }
                                                        if($notificacao->isSendNotification){
                                                            $res = FCM::send($usuario->token_notification, $notificacao->titulo, $notificacao->corpo, ["tipo" => "alert"]);   
                                                            $notificao_log = new NotificacoesLog();
                                                            $notificao_log->tipo = "notification";
                                                            $notificao_log->nome = $nome;
                                                            $notificao_log->notificacoes_id = $notificacao->id;
                                                            $notificao_log->usuario_app_id = $usuario->id;
                                                            $notificao_log->email = $usuario->email;
                                                            if($res){
                                                                $notificao_log->statusEnvioNotificacao = 1;
                                                            } else {
                                                                $notificao_log->statusEnvioNotificacao = 0;
                                                            }
                                                            $notificao_log->save();
                                                        }
        
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }


                            }
                        }
                }

                

            } else {
                $usuariosSelecionados = isset($request['usuarios']) ? $request['usuarios'] : [];
                foreach($usuariosSelecionados as $usuario_app_id){
                    $usuario = UsuarioApp::where("id", $usuario_app_id)->first();
                    if($usuario){
                        $notificacao = new Notificacoes();
                        $notificacao->titulo = $request['titulo'];
                        $notificacao->link_interno_app = $linkInterno;
                        $notificacao->link_interno_app_label = $labelInterno;
                        $notificacao->param_link_interno = $paramLinkInterno;
                        $notificacao->param_name = $paramNameLinkInterno;
                        $notificacao->corpo = $request['corpo'];
                        $notificacao->isSendEmail = isset($request['isSendEmail']) ? true : false;
                        $notificacao->isSendNotification = isset($request['isSendNotification']) ? true : false;
                        $notificacao->usuario_app_id = $usuario->id;
                        $notificacao->tipo_usuario = "afiliado";
                        $notificacao->save();

                        if($usuario->tipo=="sindico"){
                            $nome = Sindico::where("usuario_app_id", $usuario->id)->first()->nome;
                        } else {
                            $nome = Afiliado::where("usuario_app_id", $usuario->id)->first()->razao_social;
                        }
                        if($notificacao->isSendEmail){
                            $res = SenderEmails::emailNotification($notificacao->titulo, $notificacao->corpo, $usuario->email, $nome);
                        
                            $notificao_log = new NotificacoesLog();
                            $notificao_log->tipo = "email";
                            $notificao_log->nome = $nome;
                            $notificao_log->notificacoes_id = $notificacao->id;
                            $notificao_log->usuario_app_id = $usuario->id;
                            $notificao_log->email = $usuario->email;
                            if($res){
                                $notificao_log->statusEnvioEmail = 1;
                            } else {
                                $notificao_log->statusEnvioEmail = 0;
                            }
                            $notificao_log->save();

                        }
                        if($notificacao->isSendNotification && $usuario->token_notification){
                            $res = FCM::send($usuario->token_notification, $notificacao->titulo, $notificacao->corpo, ["tipo" => "alert"]);   
                            $notificao_log = new NotificacoesLog();
                            $notificao_log->tipo = "notification";
                            $notificao_log->nome = $nome;
                            $notificao_log->notificacoes_id = $notificacao->id;
                            $notificao_log->usuario_app_id = $usuario->id;
                            $notificao_log->email = $usuario->email;
                            if($res){
                                $notificao_log->statusEnvioNotificacao = 1;
                            } else {
                                $notificao_log->statusEnvioNotificacao = 0;
                            }
                            $notificao_log->save();
                        }
                    }
                }
            }
            return redirect()->route('admin.notificacoes.index')
                ->with('success_message', 'Notificação foram enviadas.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notificacao = Notificacoes::findOrFail($id);
        $envios_push = NotificacoesLog::where("notificacoes_id", $id)->where("tipo", "notification")->get();
        $envios_email = NotificacoesLog::where("notificacoes_id", $id)->where("tipo", "email")->get();
        return view('notificacoes.show', compact('notificacao', 'envios_push', 'envios_email'));
    }

}