<?php

namespace App\Http\Controllers;

use App\Models\Afiliado;
use App\Models\AfiliadoCategoria;
use App\Models\AfiliadoFranqueadoAsaas;
use App\Models\AfiliadoRegiao;
use App\Models\CartaoCnpj;
use App\Models\Categoria;
use App\Models\ContratoAssinatura;
use App\Models\ContratoSocial;
use App\Models\Franqueado;
use App\Models\FranqueadoRegiao;
use App\Models\FranqueadoRegiaoPlanoDisponibilizado;
use App\Models\Notificacao;
use App\Models\Orcamento;
use App\Models\OrcamentoAssinatura;
use App\Models\PlanoAssinaturaAfiliadoRegiao;
use App\Models\PlanoDisponivelFranqueado;
use App\Models\ResponsavelAfiliado;
use App\Models\UsuarioApp;
use App\Uteis\Asaas;
use App\Uteis\autentique\DocumentosAutentique;
use App\Uteis\Formatacao;
use App\Uteis\ModusOperandiStatus;
use App\Uteis\StatusAsass;
use App\Uteis\StatusAssinaturaPlano;
use App\Uteis\StatusOrcamento;
use App\Uteis\StatusPlano;
use App\Uteis\Url;
use App\Uteis\Util;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Psy\CodeCleaner\AssignThisVariablePass;
use Illuminate\Support\Facades\Log;

class AfiliadoController extends Controller
{
    protected $url;
    public function __construct(Request $request)
    {
        if ($request->is('admin_franqueado/*')) {
            $this->url = 'admin_franqueado';
        } else {
            $this->url = 'admin';
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $franqueado_id;
    public function index($franqueado_id = null)
    {
        $this->franqueado_id = $franqueado_id;
        $franqueados = Franqueado::all(["id", "nome"]);
        if ($this->url == 'admin') {

            $eagerLoads = ['usuarioApp', 'responsavel', 'categorias.categoria', 'regioes.PlanoAssinaturaAfiliadoRegiao', 'regioes.regiao'];

            if ($franqueado_id != null && $franqueado_id > 0) {
                $afiliados = Afiliado::join('afiliado_regiao', 'afiliado_regiao.afiliado_id', 'afiliado.id')
                    ->whereIn('afiliado_regiao.regiao_id', function ($query) {
                        $query->select('regiao_id')
                            ->from('franqueado_regiao')
                            ->where('franqueado_regiao.franqueado_id', $this->franqueado_id);
                    })->distinct()->select('afiliado.*')->with($eagerLoads)->get();
            } elseif ($franqueado_id == -1) {
                $afiliados = Afiliado::where("forma_cadastro", "app")->with($eagerLoads)->get();
            } else {
                $afiliados = Afiliado::with($eagerLoads)->get();
            }

            $afiliadoIds = $afiliados->pluck('id');

            $sF  = StatusOrcamento::$FINALIZADO;
            $sCA = implode(',', [StatusOrcamento::$CANCELADO_PELO_ADMIN, StatusOrcamento::$CANCELADO_PELO_SINDICO, StatusOrcamento::$CANCELADO_PELO_AFILIADO, StatusOrcamento::$CANCELADO_PELO_FRANQUEADO]);
            $sAn = implode(',', [StatusOrcamento::$ANALISANDO_CANDIDATOS, StatusOrcamento::$ANALISANDO_ORCAMENTOS, StatusOrcamento::$AGUARDANDO_CONTRATO, StatusOrcamento::$CONTRATO_ASSINADO, StatusOrcamento::$EM_EXECUCAO]);

            $orcamentoCounts = Orcamento::selectRaw(
                "afiliado_id,
                 SUM(CASE WHEN status = {$sF}  THEN 1 ELSE 0 END) as concluidas,
                 SUM(CASE WHEN status IN ({$sCA}) THEN 1 ELSE 0 END) as canceladas,
                 SUM(CASE WHEN status IN ({$sAn}) THEN 1 ELSE 0 END) as andamento,
                 COUNT(*) as total"
            )->whereIn('afiliado_id', $afiliadoIds)->groupBy('afiliado_id')->get()->keyBy('afiliado_id');

            $afiliadoAsaasMap = AfiliadoFranqueadoAsaas::leftJoin('franqueado', function ($join) {
                    $join->on('franqueado.id', '=', 'afiliado_franqueado_asaas.franqueado_id')
                         ->whereNull('franqueado.deleted_at');
                })
                ->whereIn('afiliado_franqueado_asaas.afiliado_id', $afiliadoIds)
                ->select('afiliado_franqueado_asaas.*', 'franqueado.nome as franqueado_nome')
                ->orderBy('afiliado_franqueado_asaas.id', 'desc')
                ->get()
                ->groupBy('afiliado_id');

            $url = Url::baseURL();
            return view('admin.afiliados.index', compact('afiliados', 'franqueados', 'franqueado_id', 'url', 'orcamentoCounts', 'afiliadoAsaasMap'));
        } else if ($this->url == 'admin_franqueado') {
            $fid = Auth::guard('franqueados')->user()->id;
            $franqueadoRegioes = FranqueadoRegiao::where("franqueado_id", $fid)->where("status", "ativo")->orderBy("id", "desc")->get();
            $franqueadoRegiaoIds = $franqueadoRegioes->pluck('regiao_id');

            $eagerLoads = ['usuarioApp', 'responsavel', 'categorias.categoria', 'regioes.PlanoAssinaturaAfiliadoRegiao', 'regioes.regiao'];

            $search = request('search');
            $query = Afiliado::where(function ($q) use ($franqueadoRegiaoIds, $fid) {
                $q->whereHas('regioes', function ($q2) use ($franqueadoRegiaoIds) {
                    $q2->whereIn('afiliado_regiao.regiao_id', $franqueadoRegiaoIds);
                })->orWhere('franqueado_id', $fid);
            });
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('razao_social', 'like', "%{$search}%")
                      ->orWhere('nome_fantasia', 'like', "%{$search}%");
                });
            }
            $afiliados = $query->with($eagerLoads)->paginate(50)->withQueryString();

            $afiliadoIds = $afiliados->pluck('id');

            $sF  = StatusOrcamento::$FINALIZADO;
            $sCA = implode(',', [StatusOrcamento::$CANCELADO_PELO_ADMIN, StatusOrcamento::$CANCELADO_PELO_SINDICO, StatusOrcamento::$CANCELADO_PELO_AFILIADO, StatusOrcamento::$CANCELADO_PELO_FRANQUEADO]);
            $sAn = implode(',', [StatusOrcamento::$ANALISANDO_CANDIDATOS, StatusOrcamento::$ANALISANDO_ORCAMENTOS, StatusOrcamento::$AGUARDANDO_CONTRATO, StatusOrcamento::$CONTRATO_ASSINADO, StatusOrcamento::$EM_EXECUCAO]);

            $orcamentoCounts = Orcamento::selectRaw(
                "afiliado_id,
                 SUM(CASE WHEN status = {$sF}  THEN 1 ELSE 0 END) as concluidas,
                 SUM(CASE WHEN status IN ({$sCA}) THEN 1 ELSE 0 END) as canceladas,
                 SUM(CASE WHEN status IN ({$sAn}) THEN 1 ELSE 0 END) as andamento,
                 COUNT(*) as total"
            )->whereIn('afiliado_id', $afiliadoIds)->groupBy('afiliado_id')->get()->keyBy('afiliado_id');

            $asaasMap = AfiliadoFranqueadoAsaas::where('franqueado_id', $fid)
                ->whereIn('afiliado_id', $afiliadoIds)
                ->orderBy('id', 'desc')
                ->get()
                ->groupBy('afiliado_id');

            $url = Url::baseURL();
            return view('admin_franqueado.afiliados.index', compact('franqueadoRegioes', 'fid', 'afiliados', 'franqueados', 'franqueado_id', 'url', 'orcamentoCounts', 'asaasMap'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::where("categoria_pai_id", "=", null)->orderBy("nome", "asc")->get();
        $usuariosApp = UsuarioApp::pluck('email', 'id')->all();
        $possuiTokenAutentique = !empty($this->user_franqueado->token_autentique) ? $this->user_franqueado->token_autentique : null;

        $cartaoCNPJ = null;
        $contratoSocial = null;
        return view($this->url . '.afiliados.create', compact('cartaoCNPJ', 'contratoSocial', 'possuiTokenAutentique', 'categorias', 'usuariosApp'));
    }

    public function removerasaas($id)
    {
        AfiliadoFranqueadoAsaas::where("franqueado_id", $this->user_franqueado->id)
            ->where("afiliado_id", $id)
            ->where("modo", Util::getModusOperandi())
            ->delete();
        return redirect()->route($this->url . '.afiliados.show', $id)
            ->with('success_message', 'Afiliado foi adicionado com sucesso.');
    }

    public function vincularasaas($id)
    {
        $afiliado = Afiliado::where("id", $id)->first();
        Asaas::novoCustomer($afiliado, Util::getTokenAsaasFranqueadoById($this->user_franqueado->id), true, $this->user_franqueado->id);
        Notificacao::painelNotificarAfiliadoPlanoAtivo($afiliado);
        return redirect()->route($this->url . '.afiliados.show', $afiliado->id)
            ->with('success_message', 'Afiliado foi adicionado com sucesso.');
    }

    public function vincularasaasexistente($id, $customer_id)
    {
        $afiliadoAsaas = new AfiliadoFranqueadoAsaas();
        $afiliadoAsaas->afiliado_id = $id;
        $afiliadoAsaas->franqueado_id = $this->user_franqueado->id;
        $afiliadoAsaas->asaas_customer_id = $customer_id;
        $afiliadoAsaas->modo = Util::getModusOperandi();
        $afiliadoAsaas->save();

        $afiliado = Afiliado::where("id", $afiliadoAsaas->afiliado_id)->first();
        Notificacao::painelNotificarAfiliadoPlanoAtivo($afiliado);

        return redirect()->route($this->url . '.afiliados.show', $id)
            ->with('success_message', 'Afiliado foi adicionado com sucesso.');
    }



    public function cancelarassinatura($afiliado_id, $assinatura_id)
    {
        $franqueado_id = null;
        // if ($this->url == 'admin') {
        //     $planoAssinado = PlanoAssinaturaAfiliadoRegiao::where("asaas_assinatura_id", $assinatura_id)->first();
        //     if ($planoAssinado) {
        //         $afiliadoRegiao = AfiliadoRegiao::withTrashed()->where("plano_assinatura_afiliado_regiao_id", $planoAssinado->id)->first();
        //         $franqueadoRegiao = FranqueadoRegiao::withTrashed()->where("regiao_id", $afiliadoRegiao->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
        //         if ($franqueadoRegiao) {
        //             $franqueado = Franqueado::where("id", $franqueadoRegiao->franqueado_id)->first();
        //             $franqueado_id = $franqueado->id;
        //         }
        //     }
        // } else {
        //     if (isset($this->user_franqueado->id) && $this->user_franqueado->id > 0) {
        //         $franqueado_id = $this->user_franqueado->id;
        //     }
        // }
        $planoAssinado = PlanoAssinaturaAfiliadoRegiao::where("asaas_assinatura_id", $assinatura_id)->first();
        if ($planoAssinado) {
            $afiliadoRegiao = AfiliadoRegiao::withTrashed()->where("plano_assinatura_afiliado_regiao_id", $planoAssinado->id)->first();
            $franqueadoRegiao = FranqueadoRegiao::withTrashed()->where("regiao_id", $afiliadoRegiao->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
            if ($franqueadoRegiao) {
                $franqueado = Franqueado::where("id", $franqueadoRegiao->franqueado_id)->first();
                $franqueado_id = $franqueado->id;
            }
        }

        if ($franqueado_id == null) {
            return redirect()->route($this->url . '.afiliados.show', $afiliado_id)
                ->with('success_message', 'Erro. Franqueado não encontrado.');
        }

        $res = Asaas::cancelarAssinatura($assinatura_id, Util::getTokenAsaasFranqueadoById($franqueado_id));
        
        $planosAssinado = PlanoAssinaturaAfiliadoRegiao::where("asaas_assinatura_id", isset($res['id']) ? $res['id'] : $assinatura_id)->get();
        foreach($planosAssinado as $planoAssinado){
            $planoAssinado->statusPlano = StatusPlano::$CANCELADO;
            $planoAssinado->data_cancelamento = Date("Y-m-d H:i:s");
            $planoAssinado->update();
        }


        return redirect()->route($this->url . '.afiliados.show', $afiliado_id)
            ->with('success_message', 'Assinatura cancelada com sucesso.');
    }

    public function vincularassinaturaexistente($afiliado_id, $plano_assinatura_asaas_id, $plano_assinatura_afiliado_id, $data_expiracao, $customer_id)
    {
        $planoAssinaturaAfiliadoREgiao = PlanoAssinaturaAfiliadoRegiao::where("id", $plano_assinatura_afiliado_id)->first();
        $planoAssinaturaAfiliadoREgiao->asaas_assinatura_id = $plano_assinatura_asaas_id;
        $planoAssinaturaAfiliadoREgiao->data_expiracao = $data_expiracao;
        $planoAssinaturaAfiliadoREgiao->asaas_customer_id = $customer_id;
        $planoAssinaturaAfiliadoREgiao->gerenciado_plano_assas_franquia = 0;
        $planoAssinaturaAfiliadoREgiao->update();

        $res = Asaas::updateExternalNumberAssinatura($planoAssinaturaAfiliadoREgiao->id, $plano_assinatura_asaas_id, Util::getTokenAsaasFranqueadoById($this->user_franqueado->id));
        return redirect()->route($this->url . '.afiliados.show', $afiliado_id)
            ->with('success_message', 'Afiliado foi adicionado com sucesso.');
    }

    public function inserirassinatura($afiliado_id, $plano_assinatura_id)
    {


        $planoAssinatura = PlanoAssinaturaAfiliadoRegiao::where("id", $plano_assinatura_id)->first();
        $planoAssinatura->gerenciado_plano_assas_franquia = 0;
        $afiliadoCustomer = Afiliado::where("id", $afiliado_id)->first();
        $afilidoAsaas = AfiliadoFranqueadoAsaas::where("afiliado_id", $afiliado_id)
            ->where("modo", Util::getModusOperandi())
            ->where("franqueado_id", $this->user_franqueado->id)
            ->orderBy("id", "desc")
            ->first();

            
        if ($afilidoAsaas) {
            $afiliadoCustomer->asaas_customer_id = $afilidoAsaas->asaas_customer_id;
        } else {
            $afiliadoCustomer->asaas_customer_id = "";
        }
        
        if ($afiliadoCustomer->asaas_customer_id == "") {
            $res = Asaas::novoCustomer($afiliadoCustomer, Util::getTokenAsaasFranqueadoById($this->user_franqueado->id), true,  $this->user_franqueado->id);
            //alterei aqui
            //$planoAssinatura->asaas_customer_id = $res['id'];
            if ($res && isset($res['id'])) {
                $planoAssinatura->asaas_customer_id = $res['id'];
            } else {
            // Tratar erro de criação de cliente no Asaas
                dd("Erro ao criar o cliente no Asaas", $res);
            }//até aqui
        } else {
            $planoAssinatura->asaas_customer_id = $afiliadoCustomer->asaas_customer_id;
        }

        $res = Asaas::criarAssinatura($planoAssinatura, Util::getTokenAsaasFranqueadoById($this->user_franqueado->id));

        //if (isset($res["id"])) { alterei aqui
        if ($res && isset($res["id"])) {
            Notificacao::painelNotificarAfiliadoPlanoAtivo($afiliadoCustomer);
            $planoAssinatura->asaas_assinatura_id = $res["id"];
            $planoAssinatura->statusPlano = StatusPlano::$ATIVO;
            $planoAssinatura->update();
            return redirect()->route($this->url . '.afiliados.show', $afiliado_id)
                ->with('success_message', 'Assinatura foi adicionado com sucesso.');
        } else {
            //alterei aqui
            //return redirect()->route($this->url . '.afiliados.show', $afiliado_id)
            //    ->with('success_message', 'A assinatura não foi inserida.');
            return redirect()->route($this->url . '.afiliados.show', $afiliado_id)
                     ->with('error_message', 'Erro ao criar a assinatura.');
        }
    }

    public function store_contrato(Request $request)
    {
        DB::beginTransaction();
        try{
            $planoAssinaturaRegiao = PlanoAssinaturaAfiliadoRegiao::where("id", $request['plano_assinatura_afiliado_regiao_id'])->first();
            $afiliadoRegiao = AfiliadoRegiao::where("plano_assinatura_afiliado_regiao_id", $planoAssinaturaRegiao->id)->first();
    
            if ($request["arquivo_contrato"]) {
                $planoAssinaturaRegiao->arquivo_original = $request['arquivo_contrato']->store('contratos');
            }
    
            if ($request["tipo_assinatura"] == "1") {
                //Integrar autentique
                DocumentosAutentique::enviarContratoAutentique($afiliadoRegiao, $planoAssinaturaRegiao, $afiliadoRegiao->afiliado, $request['email_testemunha1'], $request['email_testemunha2']);
            } elseif ($request["tipo_assinatura"] == "2") {
                //Todos assinaram. Não integrar com autentique
                $planoAssinaturaRegiao->arquivo_assinado = $request['arquivo_contrato']->store('contratos');
                $planoAssinaturaRegiao->data_assinatura = Date("Y-m-d H:m:i");
                $planoAssinaturaRegiao->status_afiliado = StatusAssinaturaPlano::$ASSINADO;
                $planoAssinaturaRegiao->status_testemunha1 = StatusAssinaturaPlano::$ASSINADO;
                $planoAssinaturaRegiao->status_testemunha2 = StatusAssinaturaPlano::$ASSINADO;
                $planoAssinaturaRegiao->status = StatusAssinaturaPlano::$ASSINADO;
            } elseif ($request["tipo_assinatura"] == "4") {
                //Enviar o contrato posteriormente. Não integrar com autentique
    
            }
            $planoAssinaturaRegiao->tipo_assinatura = $request['tipo_assinatura'];
            $planoAssinaturaRegiao->update();
            DB::commit();

            $afiliado = Afiliado::where("id", $afiliadoRegiao->afiliado_id)->first();
            $usuarioApp = $afiliado->usuarioApp;
    
            if ($usuarioApp && $usuarioApp->token_notification){
                SenderNotificacao::enviarNotificacaoNovoContrato($usuarioApp->token_notification);
            }
            SenderEmails::emailNotification("Seu contrato está disponível", "Seu contrato já está disponível. Acesse o App e confira em Gerenciar Regiões", $usuarioApp->email, $afiliado->razao_social);
            Notificacao::painelNotificarAfiliadoNovoContratoRegiao($afiliado, $afiliadoRegiao);

            return redirect()->route($this->url . '.afiliados.show', $afiliadoRegiao->afiliado_id)->with('success_message', 'Afiliado foi adicionado com sucesso.');
        } catch(Exception $e){
            DB::rollBack();
            return redirect()->route($this->url . '.afiliados.show', $afiliadoRegiao->afiliado_id)->with('error_message', 'Erro adicionando contrato.');
        }
    }

    public function store_regiao(Request $request)
    {
        
        DB::beginTransaction();

        try{

            if (isset($request['franqueado_id']) && $request['franqueado_id'] > 0) {
                $user_franqueado = Franqueado::where("id", $request['franqueado_id'])->first();
            } else if(isset($this->user_franqueado->id) && $this->user_franqueado->id>0){
                $user_franqueado = $this->user_franqueado;
            } else {
                DB::rollBack();
                return redirect()->route($this->url . '.afiliados.show', $request['afiliado_id'])->with('error_message', 'Erro ao adicionar região. Code 1.');
            }

            $tokenAsaas = Util::getTokenAsaasFranqueadoById($user_franqueado->id);

            $afiliadoRegiao = new AfiliadoRegiao();
            $afiliadoRegiao->afiliado_id = $request['afiliado_id'];
            $afiliadoRegiao->regiao_id = $request['regiao_id'];
            $afiliadoRegiao->modo = Util::getModusOperandi();
            $afiliadoRegiao->save();
            $planoEscolhido = PlanoDisponivelFranqueado::where("id", $request['plano_id'])->first();
           

            $planoAssinatura = new PlanoAssinaturaAfiliadoRegiao();
            $planoAssinatura->nome = $planoEscolhido->nome;
            $planoAssinatura->descricao = $planoEscolhido->descricao;
            $planoAssinatura->valor = $planoEscolhido->valor;
            $planoAssinatura->tipo = $planoEscolhido->tipo;
            $planoAssinatura->valor_comissao = $planoEscolhido->valor_comissao;
            $planoAssinatura->quantidade_meses_vigencia = Asaas::getMesesCiclo($planoEscolhido->ciclo);
            $planoAssinatura->dias_trial = $planoEscolhido->dias_trial;
            $planoAssinatura->desconto = $planoEscolhido->desconto;
            $planoAssinatura->isTerceirizada = $planoEscolhido->isTerceirizada;
            $planoAssinatura->ciclo = $planoEscolhido->ciclo;
            $planoDisponibilizadpRegiao = FranqueadoRegiaoPlanoDisponibilizado::where("plano_disponivel_franqueado_id", $request['plano_id'])->orderBy("id", "desc")->first();

            if ($planoDisponibilizadpRegiao == null) {
                FranqueadoRegiaoPlanoDisponibilizado::create([
                    "plano_disponivel_franqueado_id" => $request['plano_id'],
                    "franqueado_regiao_id" => $afiliadoRegiao->id
                ]);
            }

            $planoDisponibilizadpRegiao = FranqueadoRegiaoPlanoDisponibilizado::where("plano_disponivel_franqueado_id", $request['plano_id'])->orderBy("id", "desc")->first();

            $planoAssinatura->franqueado_regiao_plano_disponibilizado_id = $planoDisponibilizadpRegiao->id;
            $planoAssinatura->statusPlano = $request['status_plano_regiao'];
            $planoAssinatura->tipo_assinatura = $request['tipo_assinatura'];

            $planoAssinatura->save();


            $afiliadoRegiao->plano_assinatura_afiliado_regiao_id = $planoAssinatura->id;

            if ($request["arquivo_contrato"]) {
                $planoAssinatura->arquivo_original = $request['arquivo_contrato']->store('contratos');
            }



            if ($request["assinatura_asaas"] == "1" && $planoEscolhido->valor>=10) {
                //criar assinatura no asaas
                $planoAssinatura->gerenciado_plano_assas_franquia = 0;
                $planoAssinatura->save();

                $afiliadoCustomer = Afiliado::where("id", $afiliadoRegiao->afiliado_id)->first();
                $afilidoAsaas = AfiliadoFranqueadoAsaas::where("afiliado_id", $afiliadoRegiao->afiliado_id)
                    ->orderBy("id", "desc")
                    ->where("modo", Util::getModusOperandi())
                    ->where("franqueado_id", $user_franqueado->id)
                    ->first();
                if ($afilidoAsaas) {
                    $afiliadoCustomer->asaas_customer_id = $afilidoAsaas->asaas_customer_id;
                } else {
                    $afiliadoCustomer->asaas_customer_id = "";
                }

                if($request["tipo_assinatura"] == "2"){
                    if ($afiliadoCustomer->asaas_customer_id == "") {
                        $res = Asaas::novoCustomer($afiliadoCustomer, $tokenAsaas, true, $user_franqueado->id);
                        $planoAssinatura->asaas_customer_id = $res['id'];
                    } else {
                        $planoAssinatura->asaas_customer_id = $afiliadoCustomer->asaas_customer_id;
                    }
    
                    $res = Asaas::criarAssinatura($planoAssinatura, $tokenAsaas);
    
                    if (isset($res['errors'][0]["code"])) {
                        if ($res['errors'][0]["code"] == "invalid_customer") {
                            $res = Asaas::novoCustomer($afiliadoCustomer, $tokenAsaas, true, $user_franqueado->id);
                            $planoAssinatura->asaas_customer_id = $res['id'];
                            $res = Asaas::criarAssinatura($planoAssinatura, $tokenAsaas);
                        }
                    }
                    $planoAssinatura->asaas_assinatura_id = $res["id"];
                    Notificacao::painelNotificarAfiliadoPlanoAtivo($afiliadoCustomer);
                }
            } else {
                $planoAssinatura->gerenciado_plano_assas_franquia = 1;
                $planoAssinatura->save();
            }
            $planoAssinatura->save();

            $afiliadoRegiao->save();

            if ($request["tipo_assinatura"] == "1") {
                //Integrar autentique
                $res = DocumentosAutentique::enviarContratoAutentique($afiliadoRegiao, $planoAssinatura, $afiliadoRegiao->afiliado, $request['email_testemunha1'], $request['email_testemunha2']);
                Notificacao::painelNotificarAfiliadoNovoContratoRegiao($afiliadoCustomer, $afiliadoRegiao);
            } elseif ($request["tipo_assinatura"] == "2") {
                //Todos assinaram. Não integrar com autentique
                $planoAssinatura->arquivo_assinado = $request['arquivo_contrato']->store('contratos');
                $planoAssinatura->data_assinatura = Date("Y-m-d H:m:i");

                $planoAssinatura->status_afiliado = StatusAssinaturaPlano::$ASSINADO;
                $planoAssinatura->status_testemunha1 = StatusAssinaturaPlano::$ASSINADO;
                $planoAssinatura->status_testemunha2 = StatusAssinaturaPlano::$ASSINADO;
                $planoAssinatura->status = StatusAssinaturaPlano::$ASSINADO;
                $planoAssinatura->update();

                Notificacao::painelNotificarAfiliadoNovoContratoRegiao($afiliadoCustomer, $afiliadoRegiao);
            } elseif ($request["tipo_assinatura"] == "4") {
                //Enviar o contrato posteriormente. Não integrar com autentique
            }
            
            DB::commit();


            if ($request["assinatura_asaas"] == "1" && $planoEscolhido->valor>=10) {
                $planoAssinatura2 = PlanoAssinaturaAfiliadoRegiao::where("id", $planoAssinatura->id)->first();
                $planoAssinatura2->gerenciado_plano_assas_franquia = 0;
                $planoAssinatura2->save();
            } else {
                $planoAssinatura2 = PlanoAssinaturaAfiliadoRegiao::where("id", $planoAssinatura->id)->first();
                $planoAssinatura2->gerenciado_plano_assas_franquia = 1;
                $planoAssinatura2->save();
            }

            $afiliado = Afiliado::where("id", $afiliadoRegiao->afiliado_id)->first();
            if ($afiliado && ($request["tipo_assinatura"] == "1" || $request["tipo_assinatura"] == "2")) {
                $usuarioApp = $afiliado->usuarioApp;
                if ($usuarioApp->token_notification)
                    SenderNotificacao::enviarNotificacaoNovoContrato($usuarioApp->token_notification);

                SenderEmails::emailNotification("Seu contrato está disponível", "Seu contrato já está disponível. Acesse o App e confira e Gerenciar Regiões", $usuarioApp->email, $afiliado->razao_social);
                Notificacao::painelNotificarAfiliadoNovoContratoRegiao($afiliado, $afiliadoRegiao);
            }

            return redirect()->route($this->url . '.afiliados.show', $afiliadoRegiao->afiliado_id)->with('success_message', 'Afiliado foi adicionado com sucesso.');
        } catch(Exception $e){
            DB::rollBack();
            return redirect()->route($this->url . '.afiliados.show', $afiliadoRegiao->afiliado_id)->with('error_message', 'Erro adicionando região ao afiliado. Code 2.');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($request['cancel'])) {
            return redirect()->route($this->url . '.afiliados.index');
        }

        DB::beginTransaction();
        try {
            $this->getData($request);

            #CADASTRO USUARIO APP
            $usuario_app = new UsuarioApp();
            $usuario_app->email = isset($request['email']) ? (string) $request['email'] : "";
            $usuario_app->senha = isset($request['senha']) ? (string) $request['senha'] : "";
            $usuario_app->tipo = "afiliado";
            $usuario_app->senha = Hash::make($request['senha']);
            $usuario_app->save();

            #FIM CADASTRO USUARIO
            $request['usuario_app_id'] = $usuario_app->id;

            $afiliado = new Afiliado();
            $afiliado->razao_social = $request['razao_social'];
            $afiliado->nome_fantasia = $request['nome_fantasia'];
            $afiliado->telefone = $request['telefone'];
            $afiliado->email = $request['email'];
            $afiliado->cnpj = $request['cnpj'];

            $afiliado->inscricao_estadual = $request['inscricao_estadual'];
            $afiliado->inscricao_municipal = $request['inscricao_municipal'];
            $afiliado->cep = $request['cep'];
            $afiliado->estado = $request['estado'];
            $afiliado->cidade = $request['cidade'];
            $afiliado->bairro = $request['bairro'];
            $afiliado->rua = $request['rua'];
            $afiliado->numero = $request['numero'];
            $afiliado->complemento = $request['complemento'];
            $afiliado->rumo_atividade = $request['rumo_atividade'];
            $afiliado->numero_funcionarios = $request['numero_funcionarios'];


            if (isset($request['logo'])) {
                $image_url = $request['logo']->store('afiliado/logo');
                $afiliado->logo = $image_url;
            } else {
                $afiliado->logo = '';
            }


            if (isset($this->user_franqueado->id)) {
                $afiliado->franqueado_id = $this->user_franqueado->id;
                $afiliado->forma_cadastro = "Franquia " . $this->user_franqueado->nome;
                $afiliado->status = "ativo";
            } else {
                $afiliado->forma_cadastro = "Super Admin";
                $afiliado->status = "ativo";
            }

            $afiliado->save();

            // if (isset($this->user_franqueado->id)) {
            //     if (Util::getTokenAsaasFranqueadoById($this->user_franqueado->id) != null) {
            //         Asaas::novoCustomer($afiliado, Util::getTokenAsaasFranqueadoById($this->user_franqueado->id), true, $this->user_franqueado->id);
            //     }
            // }

            if (!empty($request['cartao_cnpj'])) {
                $localCartaoCNPJ = $request['cartao_cnpj']->store("afiliado/cartaocnpj");
                $localCnpj = new CartaoCnpj();
                $localCnpj->status = "aprovado";
                $localCnpj->arquivo = $localCartaoCNPJ;
                $localCnpj->afiliado_id = $afiliado->id;
                $localCnpj->save();
            }
            if (!empty($request['contrato_social'])) {
                $localCartaoCNPJ = $request['contrato_social']->store("afiliado/contratosocial");
                $contratoSocial = new ContratoSocial();
                $contratoSocial->status = "aprovado";
                $contratoSocial->arquivo = $localCartaoCNPJ;
                $contratoSocial->afiliado_id = $afiliado->id;
                $contratoSocial->save();
            }


            if (isset($request['categorias'])) {
                foreach ($request['categorias'] as $cat) {
                    $categoriaAfiliado = new AfiliadoCategoria();
                    $categoriaAfiliado->categoria_id = $cat;
                    $categoriaAfiliado->afiliado_id = $afiliado->id;
                    $categoriaAfiliado->status = "aprovado";
                    $categoriaAfiliado->save();
                }
            }

            $afiliado->usuario_app_id = $request['usuario_app_id'];
            // $afiliado->data_contrato = $data['data_contrato'];
            $afiliado->update();

            $responsavelAfiliado = new ResponsavelAfiliado();
            $responsavelAfiliado->nome = $request['nome_responsavel'];
            $responsavelAfiliado->email = $request['email_responsavel'] ? $request['email_responsavel'] : $request['email'];
            $responsavelAfiliado->telefone = $request['telefone_responsavel'];
            $responsavelAfiliado->cpf = $request['cpf'];
            $responsavelAfiliado->cargo = $request['cargo'];
            $responsavelAfiliado->numero_documento = $request['numero_documento'];
            $responsavelAfiliado->afiliado_id = $afiliado->id;
            $responsavelAfiliado->save();

            Notificacao::painelNotificarUsuarioBoasVindas($request['nome_responsavel'], "afiliado", $usuario_app);

            DB::commit();
            return redirect()->route($this->url . '.afiliados.show', $afiliado->id)->with('success_message', 'Afiliado foi adicionado com sucesso.');
        // [HUBBOX FIX] Prevenção de echo dos dados no frontend e exibição do erro real no cadastro
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['erro_critico' => 'Falha interna ao salvar: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Afiliado  $afiliado
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $afiliado = Afiliado::findOrFail($id);
        $cobrancasVencidas = [];

        if ($this->url == 'admin') {
            $afiliadaAsaas = AfiliadoFranqueadoAsaas::where("afiliado_id", $afiliado->id)
                ->where("modo", Util::getModusOperandi())
                ->orderBy("id", "desc")
                ->get();

            foreach ($afiliadaAsaas as $i => $afil) {
                $afiliadaAsaas[$i]->franqueado_nome = Franqueado::where("id", $afil->franqueado_id)->first()->nome;
            }

            $afiliado->asaas_customer_id = $afiliadaAsaas;
        } else {
            $afiliadaAsaas = AfiliadoFranqueadoAsaas::where("franqueado_id", $this->user_franqueado->id)
                ->where("afiliado_id", $afiliado->id)
                ->where("modo", Util::getModusOperandi())
                ->orderBy("id", "desc")
                ->first();

            $token_asaas_franqueado = Util::getTokenAsaasFranqueadoById($this->user_franqueado->id);
            
            if($token_asaas_franqueado && $afiliadaAsaas){
                $res = Asaas::getCobrancasByStatus($afiliadaAsaas->asaas_customer_id, StatusAsass::$OVERDUE, $token_asaas_franqueado);
                $cobrancasVencidas = Asaas::extractCobrancas($res);
            }

            if ($afiliadaAsaas) {
                $afiliado->asaas_customer_id_modo = $afiliadaAsaas->modo;
                $afiliado->asaas_customer_id = $afiliadaAsaas->asaas_customer_id;
            } else
                $afiliado->asaas_customer_id = null;
        }

        if (isset($this->user_franqueado->id))
            $possuiTokenAsaas = Util::getTokenAsaasFranqueadoById($this->user_franqueado->id) != null;
        else
            $possuiTokenAsaas = false;

        $customer_asaas_encontrados = null;
        $assinaturasCustomer = null;


        if ($afiliado->asaas_customer_id == "" && $possuiTokenAsaas) {
            $email = isset($afiliado->usuarioApp->email) ? $afiliado->usuarioApp->email : $afiliado->email;

            if ($email) {
                $customer_asaas_encontrados_por_email = Asaas::getCustomerByEmail($email, Util::getTokenAsaasFranqueadoById($this->user_franqueado->id));

                if (isset($customer_asaas_encontrados_por_email['data'])) {
                    $customer_asaas_encontrados_por_email = $customer_asaas_encontrados_por_email['data'];
                } else {
                    $customer_asaas_encontrados_por_email = null;
                }
            }

            if ($afiliado->cnpj) {
                $customer_asaas_encontrados_por_cnpj = Asaas::getCustomerByCNPJ(Formatacao::removerCaracteresEspeciais($afiliado->cnpj), Util::getTokenAsaasFranqueadoById($this->user_franqueado->id));

                if (isset($customer_asaas_encontrados_por_cnpj['data'])) {
                    $customer_asaas_encontrados_por_cnpj = $customer_asaas_encontrados_por_cnpj['data'];
                } else {
                    $customer_asaas_encontrados_por_cnpj = null;
                }
            }
            if (isset($customer_asaas_encontrados_por_email)) {
                foreach ($customer_asaas_encontrados_por_email as $customer_linha) {
                    $customer_asaas_encontrados[$customer_linha['id']] = $customer_linha;
                }
            }

            if (isset($customer_asaas_encontrados_por_cnpj)) {
                foreach ($customer_asaas_encontrados_por_cnpj as $customer_linha) {
                    $customer_asaas_encontrados[$customer_linha['id']] = $customer_linha;
                }
            }
        } elseif ($possuiTokenAsaas) {
            //Buscar assinaturas existentes
            $assinaturasCustomer = Asaas::getAllAssinaturaByCustomer($afiliado->asaas_customer_id, Util::getTokenAsaasFranqueadoById($this->user_franqueado->id));
            if (isset($assinaturasCustomer['data']))
                $assinaturasCustomer = $assinaturasCustomer['data'];
        }

        $regioesAux = AfiliadoRegiao::where('afiliado_id', $afiliado->id)->orderBy("id", "desc")->get();
        $regioes = [];
        foreach ($regioesAux as $key => $item) {
            if ($item->regiao) {
                if ($this->url == 'admin_franqueado') {
                    $franqueadoRegiao = FranqueadoRegiao::where('regiao_id', $item->regiao->id)->where("status", "ativo")->where("franqueado_id", $this->user_franqueado->id)->orderBy("id", "desc")->first();
                } else {
                    $franqueadoRegiao = FranqueadoRegiao::where('regiao_id', $item->regiao->id)->where("status", "ativo")->get();
                }
                $regioesAux[$key]->franqueados = $franqueadoRegiao;
                $regioesAux[$key]->orcamentos = Orcamento::where([['regiao_id', $item->regiao->id], ['afiliado_id', $afiliado->id]])->get();


                if ($this->url == 'admin_franqueado') {
                    if ($franqueadoRegiao) {
                        $regioes[] = $regioesAux[$key];
                    }
                } else {
                    $regioes[] = $regioesAux[$key];
                }
            }
        }

        $cartaoCNPJ = CartaoCnpj::where("afiliado_id", $afiliado->id)->orderBy("status", "asc")->orderBy("id", "desc")->first();
        $contratoSocial = ContratoSocial::where("afiliado_id", $afiliado->id)->orderBy("status", "asc")->orderBy("id", "desc")->first();

        $afiliado->load('usuarioApp');

        $categorias_afiliado = AfiliadoCategoria::where("afiliado_id", $id)->where("status", "<>", "reprovado")->get();



        
        $planosDisponivelPrivate = PlanoDisponivelFranqueado::where("statusPlano", 1)->where("is_public", 0)->get();
        foreach ($planosDisponivelPrivate as $planoDisponivelPrivate) {
            $franqueadosAux = Franqueado::get();
            foreach($franqueadosAux as $franqueadoAux){
                $franqueadoRegios = FranqueadoRegiao::where("franqueado_id", $franqueadoAux->id)->where("status", "ativo")->get();
                foreach ($franqueadoRegios as $franqueadoRegiao) {
                    $planoDisponivelFranqueado = FranqueadoRegiaoPlanoDisponibilizado::where("plano_disponivel_franqueado_id", $planoDisponivelPrivate->id)->where("franqueado_regiao_id", $franqueadoRegiao->id)->first();
                    if($planoDisponivelFranqueado==null){
                        FranqueadoRegiaoPlanoDisponibilizado::create([
                            "plano_disponivel_franqueado_id" => $planoDisponivelPrivate->id,
                            "franqueado_regiao_id" => $franqueadoRegiao->id
                        ]);
                    }
                }
            }
        }


        $planosDisponiveisFranqueado = [];
        $planosDisponiveis = [];
        $regioesDisponiveis = [];
        if ($this->url == 'admin_franqueado') {

            $afiliadosRegiao = FranqueadoRegiao::where("status", "ativo")->where("franqueado_id", $this->user_franqueado->id)->get();
            foreach ($afiliadosRegiao as $afiliadoRegiao) {
                if($afiliadoRegiao->regiao){
                    $regioesDisponiveis[$afiliadoRegiao->regiao->id] = $afiliadoRegiao->regiao;
                    $franqueado = Franqueado::where("id", $afiliadoRegiao->franqueado_id)->first();
                    $regioesDisponiveis[$afiliadoRegiao->regiao->id]['franqueado'] = $franqueado;
    
                    $regioesDisponiveis[$afiliadoRegiao->regiao->id]['franqueado']['possuiTokenAsaas'] = Util::getTokenAsaasFranqueadoById($franqueado->id) != null;
                    $regioesDisponiveis[$afiliadoRegiao->regiao->id]['franqueado']['possuiTokenAutentique'] = $franqueado->token_autentique ? true : false;
    
                    $regioesDisponiveis[$afiliadoRegiao->regiao->id]['franqueado']['tokenAsaas'] = Util::getTokenAsaasFranqueadoById($franqueado->id);
                    $regioesDisponiveis[$afiliadoRegiao->regiao->id]['franqueado']['tokenAutentique'] = $franqueado->token_autentique ? $franqueado->token_autentique : null;
    
                    $planosDisponiveisFranqueadoRegiao = PlanoDisponivelFranqueado::get();
                    foreach ($planosDisponiveisFranqueadoRegiao as $pdf) {
                        if ($pdf->statusPlano == 1) {
                            $planosDisponiveis[$pdf->id] = $pdf;
                        }
                    }
                }
            }

            $possuiTokenAsaas = Util::getTokenAsaasFranqueadoById($this->user_franqueado->id) != null;
            $possuiTokenAutentique = isset($this->user_franqueado->token_autentique) && $this->user_franqueado->token_autentique ? true : false;

            $tokenAsaas = Util::getTokenAsaasFranqueadoById($this->user_franqueado->id);
            $tokenAutentique = $possuiTokenAutentique ? $this->user_franqueado->token_autentique : null;
        } else {


            $afiliadosRegiao = FranqueadoRegiao::where("status", "ativo")->get();
            foreach ($afiliadosRegiao as $afiliadoRegiao) {
                if($afiliadoRegiao->regiao){
                    $regioesDisponiveis[$afiliadoRegiao->regiao->id] = $afiliadoRegiao->regiao;
                    $franqueado = Franqueado::where("id", $afiliadoRegiao->franqueado_id)->first();
                    $regioesDisponiveis[$afiliadoRegiao->regiao->id]['franqueado'] = $franqueado;
    
                    $regioesDisponiveis[$afiliadoRegiao->regiao->id]['franqueado']['possuiTokenAsaas'] = Util::getTokenAsaasFranqueadoById($franqueado->id) != null;
                    $regioesDisponiveis[$afiliadoRegiao->regiao->id]['franqueado']['possuiTokenAutentique'] = $franqueado->token_autentique ? true : false;
    
                    $regioesDisponiveis[$afiliadoRegiao->regiao->id]['franqueado']['tokenAsaas'] = Util::getTokenAsaasFranqueadoById($franqueado->id);
                    $regioesDisponiveis[$afiliadoRegiao->regiao->id]['franqueado']['tokenAutentique'] = $franqueado->token_autentique ? $franqueado->token_autentique : null;
    
                    $planosDisponiveisFranqueadoRegiao = PlanoDisponivelFranqueado::get();
                    foreach ($planosDisponiveisFranqueadoRegiao as $pdf) {
                        if ($pdf->statusPlano == 1) {
                            $planosDisponiveis[$pdf->id] = $pdf;
                        }
                    }
                }
            }

            $possuiTokenAsaas = null;
            $possuiTokenAutentique = null;

            $tokenAsaas = null;
            $tokenAutentique = null;
        }

        $url = $this->url;
// dd($planosDisponiveis);
        $possuiCobrancaVencida = Asaas::isPossuiCobrancaVencida($cobrancasVencidas);
        return view($this->url . '.afiliados.show', compact('possuiCobrancaVencida', 'cobrancasVencidas', 'url', 'tokenAutentique', 'assinaturasCustomer', 'tokenAsaas', 'possuiTokenAsaas', 'customer_asaas_encontrados', 'possuiTokenAsaas', 'possuiTokenAutentique', 'regioesDisponiveis', 'planosDisponiveis', 'categorias_afiliado', 'cartaoCNPJ', 'contratoSocial', 'afiliado', 'regioes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Afiliado  $afiliado
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categorias = Categoria::where("categoria_pai_id", "=", null)->orderBy("nome", "asc")->get();
        $categorias_afiliado = AfiliadoCategoria::where("afiliado_id", $id)->where("status", "aprovado")->get();
        $afiliado = Afiliado::findOrFail($id);
        $usuariosApp = UsuarioApp::pluck('email', 'id')->all();
        $cartaoCNPJ = CartaoCnpj::where("afiliado_id", $afiliado->id)->orderBy("status", "asc")->orderBy("id", "desc")->first();
        $contratoSocial = ContratoSocial::where("afiliado_id", $afiliado->id)->orderBy("status", "asc")->orderBy("id", "desc")->first();

        return view($this->url . '.afiliados.edit', compact('cartaoCNPJ', 'contratoSocial', 'categorias_afiliado', 'categorias', 'afiliado', 'usuariosApp'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Afiliado  $afiliado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Log::info('Aqui.');
        if (isset($request['cancel'])) {
            return redirect()->route($this->url . '.afiliados.index');
        }
        try {
            Log::info('Acessou o try.');

            #CADASTRO USUARIO APP
            $afiliado = Afiliado::findORFail($id);
            $request['usuario_app_id'] = $afiliado->usuario_app_id;
            $this->getDataUpdate($request, $id);
            $usuario_app = UsuarioApp::findOrFail($afiliado->usuario_app_id);
            $usuario_app->email = isset($request['email']) ? (string) $request['email'] : "";

            if ($request['senha'] != "") {
                $usuario_app->senha = Hash::make($request['senha']);
            }

            $usuario_exixst_app = UsuarioApp::where("email", $usuario_app->email)->where("tipo","afiliado")->first();

            if ((!$usuario_exixst_app && $usuario_app->email != null && $usuario_app->email != "") || ($usuario_app->id == $usuario_exixst_app->id)) {
                $usuario_app->update();
            } elseif ($usuario_app->email != $request['email'] && $usuario_app->email != null && $usuario_app->email != "") {
                Log::info('E-mail já cadastrado.');
                return back()
                    ->with('error_message', 'E-mail já cadastrado.');
            }

            #FIM CADASTRO USUARIO
            $request['usuario_app_id'] = $usuario_app->id;

            if (!$request->has('logo')) {
                $request['logo'] = null;
            }
            $afiliado = Afiliado::FindOrFail($id);
            $afiliado->razao_social = $request['razao_social'];
            $afiliado->nome_fantasia = $request['nome_fantasia'];
            $afiliado->telefone = $request['telefone'];
            $afiliado->email = $request['email'];
            $afiliado->cnpj = $request['cnpj'];
            $afiliado->cartao_cnpj = $request['cartao_cnpj'];
            $afiliado->inscricao_estadual = $request['inscricao_estadual'];
            $afiliado->inscricao_municipal = $request['inscricao_municipal'];
            $afiliado->cep = $request['cep'];
            $afiliado->estado = $request['estado'];
            $afiliado->cidade = $request['cidade'];
            $afiliado->bairro = $request['bairro'];
            $afiliado->rua = $request['rua'];
            $afiliado->numero = $request['numero'];
            $afiliado->complemento = $request['complemento'];
            $afiliado->rumo_atividade = $request['rumo_atividade'];
            $afiliado->numero_funcionarios = $request['numero_funcionarios'];

            if ($request['logo'] != null) {
                $image_url = $request['logo']->store('afiliado/logo');
                $afiliado->logo = $image_url;
            }

            if (isset($this->user_franqueado->id)) {
                $afiliado->status = "ativo";
            } else {
                $afiliado->status = "ativo";
            }

            $afiliado->usuario_app_id = $request['usuario_app_id'];
            // $afiliado->data_contrato = $data['data_contrato'];
            $afiliado->update();

            if (!empty($request['cartao_cnpj'])) {
                $localCartaoCNPJ = $request['cartao_cnpj']->store("afiliado/cartaocnpj");
                $localCnpj = new CartaoCnpj();
                $localCnpj->status = "aprovado";
                $localCnpj->arquivo = $localCartaoCNPJ;
                $localCnpj->afiliado_id = $afiliado->id;
                $localCnpj->save();
            }

            if (!empty($request['contrato_social'])) {
                $localCartaoCNPJ = $request['contrato_social']->store("afiliado/contratosocial");
                $contratoSocial = new ContratoSocial();
                $contratoSocial->status = "aprovado";
                $contratoSocial->arquivo = $localCartaoCNPJ;
                $contratoSocial->afiliado_id = $afiliado->id;
                $contratoSocial->save();
            }

            if (isset($request['recusar_cartao_cnpj'])) {
                CartaoCnpj::where("afiliado_id", $afiliado->id)->update(["status" => "reprovado"]);
            }
            if (isset($request['remover_cartao_cnpj'])) {
                CartaoCnpj::where("afiliado_id", $afiliado->id)->delete();
            }


            if (isset($request['recusar_contrato_social'])) {
                ContratoSocial::where("afiliado_id", $afiliado->id)->update(["status" => "reprovado"]);
            }
            if (isset($request['remover_contrato_social'])) {
                ContratoSocial::where("afiliado_id", $afiliado->id)->delete();
            }


            if (isset($request['remover_logo'])) {
                $afiliado->logo = null;
                $afiliado->update();
            }

            if (isset($request['categorias'])) {
                $categorias_afiliado = AfiliadoCategoria::where("afiliado_id", $id)->get();
                foreach ($categorias_afiliado as $cat) {
                    $cat->delete();
                }

                foreach ($request['categorias'] as $cat) {
                    $categoriaAfiliado = new AfiliadoCategoria();
                    $categoriaAfiliado->categoria_id = $cat;
                    $categoriaAfiliado->afiliado_id = $afiliado->id;
                    $categoriaAfiliado->status = "aprovado";
                    $categoriaAfiliado->save();
                }
            }

            $responsavelAfiliado = $afiliado->responsavel ?? new ResponsavelAfiliado(['afiliado_id' => $afiliado->id]);
            if (!empty($request['nome_responsavel'])) {
                $responsavelAfiliado->nome = $request['nome_responsavel'];
            }
            $responsavelAfiliado->email = $request['email_responsavel'] ?: $request['email'];
            $responsavelAfiliado->telefone = $request['telefone_responsavel'];
            $responsavelAfiliado->cpf = $request['cpf'];
            $responsavelAfiliado->cargo = $request['cargo'];
            $responsavelAfiliado->numero_documento = $request['numero_documento'];
            $responsavelAfiliado->afiliado_id = $afiliado->id;
            $responsavelAfiliado->save();

            return redirect()->route($this->url . '.afiliados.show', $afiliado->id)
                ->with('success_message', 'Afiliado foi atualizado com sucesso.');
        // [HUBBOX FIX] Prevenção de echo dos dados no frontend e exibição do erro real na atualização
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error('Erro na atualização: ' . $e->getMessage());
            return back()->withInput()->withErrors(['erro_critico' => 'Falha interna ao atualizar: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Afiliado  $afiliado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $afiliado = Afiliado::findOrFail($id);
            if($afiliado){
                //remover regiao e sua assinatura se houver
                $afiliadoRegioes = AfiliadoRegiao::where("afiliado_id", $afiliado->id)->get();
                foreach($afiliadoRegioes as $afiliadoRegiao){
                    $planoAssinaturaAfiliadoRegiao = PlanoAssinaturaAfiliadoRegiao::where("id", $afiliadoRegiao->plano_assinatura_afiliado_regiao_id)->first();
                    if($planoAssinaturaAfiliadoRegiao){
                        $planoAssinaturaAfiliadoRegiao->delete();
                    }
                    $afiliadoRegiao->delete();
                }

                CartaoCnpj::where("afiliado_id", $afiliado->id)->delete();
                ContratoSocial::where("afiliado_id", $afiliado->id)->delete();
                AfiliadoCategoria::where("afiliado_id", $afiliado->id)->delete();


                $user = UsuarioApp::where('id', $afiliado->usuario_app_id)->first();
                if($user){
                    if (isset($this->user_franqueado->id)) {
                        $user->removido_por = $this->url . " - Removido por Franquia #" . $this->user_franqueado->id . " - ". $this->user_franqueado->nome;
                    } else {
                        $user->removido_por = $this->url . " - Removido por Super Admin";
                    }
                    $user->token_notification = null;
                    $user->tokens()->delete();
                    $user->update();
                    $user->delete();
                }
                $afiliado->delete();
            }
            

            DB::commit();
            return redirect()->route($this->url . '.afiliados.index')
                ->with('success_message', 'Afiliado foi deletado com sucesso.');
        } catch (Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->withErrors('Erro ao deletar afiliado, tente mais tarde.');
        }
    }


    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $afiliado = Afiliado::withTrashed()->where("id", $id)->first();
            $user = UsuarioApp::withTrashed()->where('id', $afiliado->usuario_app_id)->first();
            $userAux = UsuarioApp::where('email', $user->email)->first();
            if($userAux){
                dd("O sistema não pode restaura esse usuário pois já existe um usuário ativo com este mesmo e-mail. E-mail: $user->email");
            }


            
            if($afiliado){
                $afiliado->deleted_at = null;
                $afiliado->save();

                $afiliadoRegioes = AfiliadoRegiao::withTrashed()->where("afiliado_id", $afiliado->id)->get();
                foreach($afiliadoRegioes as $afiliadoRegiao){
                    $planoAssinaturaAfiliadoRegiao = PlanoAssinaturaAfiliadoRegiao::withTrashed()->where("id", $afiliadoRegiao->plano_assinatura_afiliado_regiao_id)->first();
                    if($planoAssinaturaAfiliadoRegiao){
                        $planoAssinaturaAfiliadoRegiao->deleted_at = null;
                        $planoAssinaturaAfiliadoRegiao->save();
                    }
                    $afiliadoRegiao->deleted_at = null;
                    $afiliadoRegiao->save();
                }

                $docsCartaoCnpj = CartaoCnpj::withTrashed()->where("afiliado_id", $afiliado->id)->get();
                foreach($docsCartaoCnpj as $d){
                    $d->deleted_at = null;
                    $d->save();
                }

                $docsContratoSocial = ContratoSocial::withTrashed()->where("afiliado_id", $afiliado->id)->get();
                foreach($docsContratoSocial as $d){
                    $d->deleted_at = null;
                    $d->save();
                }
                
                $docsAfiliadoCategoria = AfiliadoCategoria::withTrashed()->where("afiliado_id", $afiliado->id)->get();
                foreach($docsAfiliadoCategoria as $d){
                    $d->deleted_at = null;
                    $d->save();
                }


                $user = UsuarioApp::withTrashed()->where('id', $afiliado->usuario_app_id)->first();
                if($user){
                    $user->deleted_at = null;
                    
                    if (isset($this->user_franqueado->id)) {
                        $user->removido_por = $this->url . " - Removido por Franquia #" . $this->user_franqueado->id . " - ". $this->user_franqueado->nome;
                    } else {
                        $user->removido_por = $this->url . " - Removido por Super Admin";
                    }
                    $user->token_notification = null;
                    $user->save();
                }
            }
            

            DB::commit();
            dd($afiliado);
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
        }
    }



    /**
     * Get the request's data from the request.
     *
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
            'razao_social' => 'required|string|min:1|max:255',
            'nome_fantasia' => 'required|string|min:1|max:255',
            'cpf' => 'nullable|cpf|string|min:1|max:255',
            'telefone' => 'nullable|string',
            'nome_responsavel' => 'required|string|min:1|max:255',
            'cnpj' => 'nullable|cnpj|string|min:11|max:60',
            'senha' => 'required',
            'cartao_cnpj' => "nullable",
            'email' => 'required|unique:usuario_app,email,null,id,deleted_at,NULL,tipo,"afiliado"',
        ];

        $data = $request->validate($rules);

        return $data;
    }


    protected function getDataUpdate(Request $request, $id)
    {
        $rules = [
            'razao_social'     => 'required|string|min:1|max:255',
            'nome_fantasia'    => 'required|string|min:1|max:255',
            'cpf'              => 'nullable|string|max:255',
            'telefone'         => 'nullable|string',
            'nome_responsavel' => 'nullable|string|min:1|max:255',
            'cnpj'             => 'nullable|cnpj|string|min:11|max:60',
            'email'            => 'required|email|unique:usuario_app,email,' . $request['usuario_app_id'] . ',id,deleted_at,NULL,tipo,"afiliado"',
        ];

        $data = $request->validate($rules);

        return $data;
    }
    public function orcamentos($afiliado_id)
    {
        $orcamentos = Orcamento::where('afiliado_id', $afiliado_id)->get();
        return view('admin.afiliados.orcamentos.index', compact('orcamentos'));
    }


    public function alterar_status_documento($documento_id, Request $request)
    {
        $tipo_documento = $request['tipo'];
        $newStatus = $request['status'];
        $motivo = $request['motivo'];

        if ($tipo_documento == "cartao-cnpj") {
            $documento = CartaoCnpj::where("id", $documento_id)->first();
            if ($documento) {
                $documento->status = $newStatus;
                $documento->motivo_reprovado = $motivo;
                $documento->update();
                $usuarioApp = $documento->afiliado->usuarioApp;
                if ($usuarioApp && $usuarioApp->token_notification)
                    $res = FCM::send(
                        $usuarioApp->token_notification,
                        "Casa do Síndico",
                        "Seu Cartão CNPJ foi $newStatus.",
                        ["tipo" => "navegacao", "texto_botao" => "Ver", "url_botao" => "afiliado/perfil/responsavel"]
                    );
                    Notificacao::painelNotificarAfiliadoDocumentoAprovadaReprovada("Cartão CNPJ", $documento->afiliado, $newStatus, $motivo);
            }
        } else if ($tipo_documento == "contrato-social") {
            $documento = ContratoSocial::where("id", $documento_id)->first();
            if ($documento) {
                $documento->status = $newStatus;
                $documento->motivo_reprovado = $motivo;
                $documento->update();
                $usuarioApp = $documento->afiliado->usuarioApp;
                if ($usuarioApp && $usuarioApp->token_notification)
                    $res = FCM::send(
                        $usuarioApp->token_notification,
                        "Casa do Síndico",
                        "Seu Contrato Social foi $newStatus.",
                        ["tipo" => "navegacao", "texto_botao" => "Ver", "url_botao" => "afiliado/perfil/responsavel"]
                    );
                Notificacao::painelNotificarAfiliadoDocumentoAprovadaReprovada("Contrato Social", $documento->afiliado, $newStatus, $motivo);
            }
        }
        return ["res" => true];
    }
}
