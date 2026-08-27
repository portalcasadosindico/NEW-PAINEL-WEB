<?php

namespace App\Http\Controllers;

use App\Models\Afiliado;
use App\Models\AfiliadoCategoria;
use App\Models\AfiliadoFranqueadoAsaas;
use App\Models\AfiliadoOrcamentoInteresse;
use App\Models\AfiliadoRegiao;
use App\Models\Bairro;
use App\Models\Categoria;
use App\Models\Cidade;
use App\Models\Condominio;
use App\Models\Estado;
use App\Models\Franqueado;
use App\Models\FranqueadoRegiao;
use App\Models\Notificacao;
use App\Models\Orcamento;
use App\Models\OrcamentoAssinatura;
use App\Models\PlanoAssinaturaAfiliadoRegiao;
use App\Models\Regiao;
use App\Models\RegiaoFaixaCep;
use App\Models\Sindico;
use App\Models\UsuarioApp;
use App\Models\Vistoria;
use App\Uteis\Asaas;
use App\Uteis\autentique\DocumentosAutentique;
use App\Uteis\autentique\DocumentosAutentique2;

use App\Uteis\Formatacao;
use App\Uteis\StatusAsass;
use App\Uteis\StatusOrcamento;
use App\Uteis\StatusPlano;
use App\Uteis\Util as UteisUtil;
use Dflydev\DotAccessData\Util;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrcamentoController extends Controller
{
    public $url;
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
    public function index($franqueado_id = null)
    {

        $regiaos = Regiao::all();
        $franqueados = Franqueado::all();
        $busca = request('q');
        if ($this->url == 'admin') {
            if ($franqueado_id == null) {
                // Paginado (era ->get() sem limite - 7900+ registros, cada um rodando a
                // sincronização de região abaixo, causava timeout real no servidor com o
                // volume de produção, ver incidente 2026-08-27).
                // Busca (campo "q") roda direto na query SQL, não só na página carregada -
                // client-side search do DataTables só enxerga os 50 registros da página atual.
                $orcamentosQuery = Orcamento::orderBy("id", "desc");
                if ($busca) {
                    $orcamentosQuery->where(function ($query) use ($busca) {
                        $query->where("nome", "like", "%{$busca}%")
                            ->orWhere("id", $busca)
                            ->orWhereHas("condominio.sindico", function ($q) use ($busca) {
                                $q->where("nome", "like", "%{$busca}%");
                            });
                    });
                }
                $orcamentos = $orcamentosQuery->paginate(50)->appends(['q' => $busca]);
            } else {
                $orcamentos = Orcamento::join('franqueado_regiao', 'franqueado_regiao.regiao_id', 'orcamento.regiao_id')->where('franqueado_regiao.franqueado_id', $franqueado_id)->where("franqueado_regiao.status", "ativo")->select('orcamento.*')->orderBy("orcamento.id", "desc")->get();
            }
        } elseif ($this->url == 'admin_franqueado') {
            $orcamentos = [];
            $franqueado_id = $this->user_franqueado->id;
            $franqueadoRegiaos = FranqueadoRegiao::where("franqueado_id", $franqueado_id)->where("status", "ativo")->get();
            foreach ($franqueadoRegiaos as $franqueadoRegiao) {
                $orcamentoRegiaos = Orcamento::where("regiao_id", $franqueadoRegiao->regiao_id)->orderBy("id", "desc")->get();
                foreach ($orcamentoRegiaos as $orcamento) {
                    array_push($orcamentos, $orcamento);
                }
            }
        }

        // [HUBBOX FIX] Sincroniza região dos orçamentos com a região atual do condomínio para evitar "SEM REGIÃO" indevido
        foreach ($orcamentos as $orcamento) {
            $this->sincronizarRegiaoOrcamentoComCondominio($orcamento);
        }


        return view($this->url . '.orcamentos.index', compact('franqueado_id', 'orcamentos', 'franqueados'));
    }

    public function indexByDate(Request $request)
    {
        $franqueados = Franqueado::all();
        $assinaturas = [];
        $mes = $request->get('filtro_mes');
        $ano = $request->get('filtro_ano');
        $franqueado_id = $request->get('franqueado_id');
        if ($this->url == 'admin') {
            if ($franqueado_id == null || $franqueado_id == '0') {
                if ($ano == '-1') {
                    $ano = date("Y");
                    $orcamentos = Orcamento::join(
                        'franqueado_regiao',
                        'franqueado_regiao.regiao_id',
                        'orcamento.regiao_id'
                    )->whereYEAR("orcamento.data_cadastro", $ano)->where("franqueado_regiao.status", "ativo")
                        ->select('orcamento.*')->orderBy("orcamento.id", "desc")->get();
                    $ano = -1;
                } else {
                    $orcamentos = Orcamento::join(
                        'franqueado_regiao',
                        'franqueado_regiao.regiao_id',
                        'orcamento.regiao_id'
                    )->whereYEAR("orcamento.data_cadastro", $ano)->whereMONTH("orcamento.data_cadastro", $mes)
                        ->where("franqueado_regiao.status", "ativo")->select('orcamento.*')->orderBy("orcamento.id", "desc")->get();
                }
            } else {
                $assinaturas = $this->getAssinaturasIndex($franqueado_id);
                if ($ano == '-1') {
                    $ano = date("Y");
                    $orcamentos = Orcamento::join('franqueado_regiao', 'franqueado_regiao.regiao_id', 'orcamento.regiao_id')
                        ->whereYEAR("orcamento.data_cadastro", $ano)->where('franqueado_regiao.franqueado_id', $franqueado_id)
                        ->where("franqueado_regiao.status", "ativo")
                        ->select('orcamento.*')->orderBy("orcamento.id", "desc")->get();
                    $ano = -1;
                } else {
                    $orcamentos = Orcamento::join('franqueado_regiao', 'franqueado_regiao.regiao_id', 'orcamento.regiao_id')
                        ->whereYEAR("orcamento.data_cadastro", $ano)->whereMONTH("orcamento.data_cadastro", $mes)
                        ->where('franqueado_regiao.franqueado_id', $franqueado_id)->where("franqueado_regiao.status", "ativo")
                        ->select('orcamento.*')->orderBy("orcamento.id", "desc")->get();
                }
            }
        } elseif ($this->url == 'admin_franqueado') {
            $orcamentos = [];
            $franqueado_id = $this->user_franqueado->id;
            $franqueadoRegiaos = FranqueadoRegiao::where("franqueado_id", $franqueado_id)->where("status", "ativo")->get();
            foreach ($franqueadoRegiaos as $franqueadoRegiao) {
                if ($ano == '-1') {
                    $ano = date("Y");
                    $orcamentoRegiaos = Orcamento::where("regiao_id", $franqueadoRegiao->regiao_id)->whereYEAR("orcamento.data_cadastro", $ano)->orderBy("id", "desc")->get();
                    $ano = -1;
                } else {
                    $orcamentoRegiaos = Orcamento::where("regiao_id", $franqueadoRegiao->regiao_id)->whereYEAR("orcamento.data_cadastro", $ano)->whereMONTH("orcamento.data_cadastro", $mes)->orderBy("id", "desc")->get();
                }
                foreach ($orcamentoRegiaos as $orcamento) {
                    array_push($orcamentos, $orcamento);
                }
            }
        }

        // [HUBBOX FIX] Sincroniza região dos orçamentos com a região atual do condomínio para evitar divergência na listagem
        foreach ($orcamentos as $orcamento) {
            $this->sincronizarRegiaoOrcamentoComCondominio($orcamento);
        }

        return view($this->url . '.orcamentos.index', compact('franqueado_id', 'orcamentos', 'franqueados', 'assinaturas', 'ano', 'mes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($sindico_param_id = null)
    {

        $sindicop = null;
        if ($sindico_param_id) {
            $sindicop = Sindico::where("id", $sindico_param_id)->first();
        }
        $franqueados = Franqueado::all();
        $sindicos = Sindico::all();
        $afiliados = Afiliado::all();

        $condominios = Condominio::with("sindico")->get();
        $categorias = Categoria::where("categoria_pai_id", "<>", null)->orderBy('nome', 'asc')->get();

        if ($this->user_franqueado)
            $email_franqueado = $this->user_franqueado->email;
        else
            $email_franqueado = null;

        return view($this->url . '.orcamentos.create', compact('email_franqueado', 'sindico_param_id', 'sindicop', 'sindicos', 'afiliados', 'condominios', 'categorias', 'franqueados'));
    }

    public function createComCategoria($categoria_param_id = null)
    {

        $categoria_id = $categoria_param_id;
        $sindico_param_id = null;
        $categoria = Categoria::where("id", $categoria_id)->first();

        $sindicop = null;
        $franqueados = Franqueado::all();
        $sindicos = [];
        $afiliados = [];
        $franqueado_id = 0;
        $possuiTokenAutentique = false;
        $franqueadoRegioes = collect();


        if ($this->url == 'admin_franqueado') {
            $franqueadoLogado = Auth::guard('franqueados')->user();
            // [HUBBOX FIX] Protege o fluxo de criação por categoria quando o guard franqueado não estiver autenticado
            if (!$franqueadoLogado) {
                return redirect()->route('admin_franqueado.login');
            }

            $sindicosAll = Sindico::orWhere("franqueado_id", null)->orWhere("franqueado_id", $this->user_franqueado->id)->get();
            foreach ($sindicosAll as $sindico) {
                $condominios = Condominio::where("sindico_id", $sindico->id)->get();
                $addSindico = false;
                foreach ($condominios  as $c) {
                    $bairro = $c->bairro()->first();
                    $regiao_id = -1;
                    if ($bairro && $bairro->regiao_id) {
                        $regiao_id = $bairro->regiao_id;
                    } elseif ($bairro) {
                        $faixaCep = RegiaoFaixaCep::where("cidade_id", $bairro->cidade_id)->first();
                        if ($faixaCep)
                            $regiao_id = $faixaCep->regiao_id;
                    }

                    $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $regiao_id)->where("franqueado_id", $this->user_franqueado->id)->where("status", "ativo")->orderBy("id", "desc")->first();
                    if ($franqueadoRegiao) {
                        $addSindico = true;
                    }
                }

                if ($addSindico || $sindico->franqueado_id == $this->user_franqueado->id) {
                    $sindicos[] = $sindico;
                }
            }
        } else {
            $sindicos = Sindico::all();
        }


        $afiliadosCategoria = AfiliadoCategoria::where("categoria_id", $categoria_id)->get();
        $afiliadosIdsCategoria = $afiliadosCategoria->pluck('afiliado_id')->unique()->values();

        if ($this->url == 'admin_franqueado') {
            $franqueado_id = Auth::guard('franqueados')->user()->id;
            $afiliados = Afiliado::join('afiliado_regiao', 'afiliado_regiao.afiliado_id', 'afiliado.id')
                ->whereIn('afiliado.id', $afiliadosIdsCategoria)
                ->whereIn('afiliado_regiao.regiao_id', function ($query) use ($franqueado_id) {
                    $query->select('regiao_id')
                        ->from('franqueado_regiao')
                        ->where('franqueado_regiao.status', 'ativo')
                        ->where('franqueado_regiao.franqueado_id', $franqueado_id);
                })
                ->distinct()
                ->select('afiliado.*')
                ->get();

            $possuiTokenAutentique = Auth::guard('franqueados')->user()->token_autentique ? true : false;
            $franqueadoRegioes = FranqueadoRegiao::where("franqueado_id", $this->user_franqueado->id)->where("status", "ativo")->orderBy("id", "desc")->get();
        } else {
            // [HUBBOX FIX] Evita dependência do guard franqueado no fluxo admin para carregar criação por categoria
            $afiliados = Afiliado::whereIn('id', $afiliadosIdsCategoria)->get();
        }

        $condominios = Condominio::with("sindico")->get();
        $categorias = Categoria::where("categoria_pai_id", "<>", null)->orderBy('nome', 'asc')->get();

        if ($this->user_franqueado)
            $email_franqueado = $this->user_franqueado->email;
        else
            $email_franqueado = null;
        return view($this->url . '.orcamentos.create', compact('email_franqueado', 'possuiTokenAutentique', 'franqueadoRegioes', 'franqueado_id', 'categoria', 'sindico_param_id', 'sindicop', 'sindicos', 'afiliados', 'condominios', 'categorias', 'franqueados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->getData($request);
            
            Log::channel('orcamento')->info('request', ['request' => $request]);

            $condominio = Condominio::where("id", $request['condominio_id'])->first();
            $this->updateCondminioBairro($condominio);

            $orcamento = new Orcamento();
            $orcamento->nome = $request['nome'];
            $orcamento->descricao = $request['descricao'];
            $orcamento->status = $request['status'];
            $orcamento->urgente = $request['urgente'];
            $orcamento->status_sindico = $orcamento->status;
            $orcamento->status_afiliado = $orcamento->status;
            $orcamento->status_testemunha1 = $orcamento->status;
            $orcamento->status_testemunha2 = $orcamento->status;

            $orcamento->modo = UteisUtil::getModusOperandi();


            $orcamento->condominio_id = $request['condominio_id'];
            $orcamento->afiliado_id = $request['afiliado_id'] > 0 ? $request['afiliado_id'] : null;
            $orcamento->categoria_id = $request['categoria_id'];
            $condominio = Condominio::where("id", $orcamento->condominio_id)->first();

            $orcamento->regiao_id = $this->getRegiaoByCondominio($condominio);
            if ($orcamento->regiao_id == null) {
                $orcamento->regiao_id = 12;
            }
            $orcamento->save();



            $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $orcamento->regiao_id)->orderBy("id", "desc")->first();
            $orcamento->formato_contrato_atual = $request["formato_contrato_atual"];

            if ($request['contrato'] && $orcamento->afiliado_id > 0) {
                
                Log::channel('orcamento')->info('Verificando se contrato e afiliado existe');
                
                $orcamento->contrato = $request['contrato']->store('contratos');
                $orcamento->titulo_contrato = 'Contrato Ref.: #' . $orcamento->id . ' - ' . $orcamento->categoria()->withTrashed()->first()->nome;
                
                Log::channel('orcamento')->info('Verificando formato contrato', ['formato_contrato_atual' => $request['formato_contrato_atual'] ]);
                
                if ($request["formato_contrato_atual"] == "1") {
                    Log::channel('orcamento')->info('Iniciando envio de contrato', [
                        'orcamento' => $orcamento,
                        'email_testemunha1' => $request['email_testemunha1'],
                        'email_testemunha2' => $request['email_testemunha2']
                    ]);

                    DocumentosAutentique::enviarContratoServico($orcamento, $request['email_testemunha1'], $request['email_testemunha2']);
                } elseif ($request["formato_contrato_atual"] == "2") {
                    // $orcamento->contrato_original = $orcamento->contrato;
                    // $orcamento->contrato_assinado = $orcamento->contrato;
                }
            }

            $orcamento->update();

            DB::commit();

            $orcamento['condominio'] = $orcamento->condominio()->first();
            $orcamento['status_label'] = StatusOrcamento::getLabel($orcamento->status);
            $orcamento['status_cor'] = StatusOrcamento::getCor($orcamento->status);

            $orcamento['afiliado'] = $orcamento->afiliado()->withTrashed()->first();
            $orcamento['categoria'] = Categoria::where("id", $orcamento->categoria_id)->first();
            $orcamento['status_sindico_label'] = StatusOrcamento::getLabel($orcamento->status_sindico);
            $orcamento['status_sindico_cor'] = StatusOrcamento::getCor($orcamento->status_sindico);

            $orcamento['status_afiliado_label'] = StatusOrcamento::getLabel($orcamento->status_afiliado);
            $orcamento['status_afiliado_cor'] = StatusOrcamento::getCor($orcamento->status_afiliado);




            if ($orcamento->afiliado_id) {
                $afiliado = Afiliado::where("id", $orcamento->afiliado_id)->first();
                if ($afiliado)
                    $usuarioAppAfiliado = UsuarioApp::where("id", $afiliado->usuario_app_id)->first();
                else
                    $usuarioAppAfiliado = null;
            } else
                $usuarioAppAfiliado = null;


            if (isset($orcamento->condominio->sindico->usuarioApp->id))
                $usuarioAppSindico = UsuarioApp::where("id", $orcamento->condominio->sindico->usuarioApp->id)->first();
            else
                $usuarioAppSindico = null;

            if ($usuarioAppAfiliado) {
                $enderecoCondomino = $condominio->bairro . ", " . $condominio->cidade . "/" . $condominio->estado;
                $msg = "Você possui um novo serviço para " . $orcamento->categoria->nome . ". Condomínio " . $condominio->nome . ". $enderecoCondomino. Solicitação #$orcamento->id. Status: " . StatusOrcamento::getLabel($orcamento->status);

                SenderEmails::emailNotification("Solicitação #$orcamento->id criada.", $msg, $usuarioAppAfiliado->email, $usuarioAppAfiliado->email);
                if ($usuarioAppAfiliado->token_notification)
                    SenderNotificacao::send($usuarioAppAfiliado->token_notification, $msg, $orcamento);

                Notificacao::painelNotificarAfiliadoEscolhido($orcamento, $afiliado);
            } else {
                $enderecoCondomino = $condominio->bairro . ", " . $condominio->cidade . "/" . $condominio->estado;
                $this->senderEnviarEmailAfiliados($orcamento);
            }

            if ($usuarioAppSindico) {
                $enderecoCondomino = $condominio->bairro . ", " . $condominio->cidade . "/" . $condominio->estado;
                if ($usuarioAppSindico) {
                    SenderEmails::emailNotification("Solicitação #$orcamento->id", "Você possui um novo serviço para o seu condomínio " . $condominio->nome . ". $enderecoCondomino. Solicitação #$orcamento->id. Status: " . StatusOrcamento::getLabel($orcamento->status), $usuarioAppSindico->email, $usuarioAppSindico->email);

                    if ($usuarioAppSindico->token_notification)
                        SenderNotificacao::send($usuarioAppSindico->token_notification, "Você possui um novo serviço para o seu condomínio " . $condominio->nome . ". $enderecoCondomino. Solicitação #$orcamento->id. Status: " . StatusOrcamento::getLabel($orcamento->status), $orcamento);
                } else {
                    SenderEmails::emailNotification("Solicitação #$orcamento->id", "Você possui uma nova solicitação para o seu condomínio " . $condominio->nome . ". $enderecoCondomino. Solicitação #$orcamento->id. Status: " . StatusOrcamento::getLabel($orcamento->status), $usuarioAppSindico->email, $usuarioAppSindico->email);
                }
            }

            if ($this->url == 'admin') {
                return redirect("admin/orcamentos/" . $orcamento->id . "/edit")->with('success_message', 'Solicitação foi adicionada com sucesso.');
            } else {
                return redirect("admin_franqueado/orcamentos/" . $orcamento->id . "/edit")->with('success_message', 'Solicitação foi adicionada com sucesso.');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors($this->getData($request));
        }
    }

    public function updateCondminioBairro($condominio)
    {
        $est = Estado::where("uf", "like", $condominio->estado, "or")->where("nome", "like", $condominio->estado, "or")->first();
        if (!$est) {
            return $this->errorResponse([array("error_code" => "invalid-uf", "error_message" => "Estado não encontrado")], 403);
        }

        $bairros = Bairro::where("chave", "LIKE", "%" . Formatacao::chave($condominio->bairro) . "%")->orderBy("id", "asc")->get();

        $encontrouBairro = false;
        foreach ($bairros as $bairroLinha) {
            $cid = Cidade::where("id", $bairroLinha->cidade_id)->first();
            $estado = Estado::where("id", $cid->estado_id)->first();
            if ((strtoupper($estado->uf) == strtoupper($condominio->estado) || Formatacao::chave($estado->nome) == Formatacao::chave($condominio->estado)) && Formatacao::chave($cid->nome) == Formatacao::chave($condominio->cidade)) {
                $condominio->estado = $estado->uf;
                $condominio->cidade = $cid->nome;
                $condominio->bairro = $bairroLinha->nome;
                $encontrouBairro = true;
                break;
            }
        }

        if ($encontrouBairro == false) {
            $cidadeReq = Cidade::where("chave", "LIKE", "%" . Formatacao::chave($condominio->cidade) . "%")->where("uf", "LIKE", $condominio->estado)->first();

            if (!$cidadeReq) {
                //return $this->errorResponse([array("error_code" => "invalid-cidade", "error_message" => "Não encontramos sua cidade. Fale com a administração.")], 403);

                $est = Estado::where("uf", "like", $condominio->estado, "or")->where("nome", "like", $condominio->estado, "or")->first();
                if (!$est) {
                    return $this->errorResponse([array("error_code" => "invalid-uf", "error_message" => "Estado não encontrado")], 403);
                }
                $cidadeReq = new Cidade();
                $cidadeReq->nome = $condominio->cidade;
                $cidadeReq->uf = $condominio->estado;
                $cidadeReq->estado_id = $est->id;
                $cidadeReq->save();
            }

            $bairro = new Bairro();
            $bairro->nome = $condominio->bairro;
            $bairro->cidade_id = $cidadeReq->id;
            $bairro->chave = Formatacao::chave($bairro->nome);
            $bairro->save();
            $condominio->bairro_id = $bairro->id;
        } else {
            $condominio->bairro_id = $bairroLinha->id;
        }

        $condominio->update();
    }


    public static function senderEnviarEmailAfiliados($orcamento, $tipo = "sendingblue")
    {
        $afiliadosEnviar = [];
        $afiliadosCategorias = AfiliadoCategoria::where("categoria_id", $orcamento->categoria_id)->where("status", "aprovado")->get();
        $inadimplenciaFranquia = [];
        foreach ($afiliadosCategorias as $afiliadoCat) {

            $afiliado = Afiliado::where("id", $afiliadoCat->afiliado_id)->first();

            if ($afiliado) {
                $afiliadosRegiaoLista = AfiliadoRegiao::where("regiao_id", $orcamento->regiao_id)->where("afiliado_id", $afiliadoCat->afiliado_id)->get();
                foreach ($afiliadosRegiaoLista as $afiliadosRegiao) {
                    if ($afiliadosRegiao) {
                        $planoRegiao = PlanoAssinaturaAfiliadoRegiao::where("id", $afiliadosRegiao->plano_assinatura_afiliado_regiao_id)->where("statusPlano", 1)->orderBy("id", "desc")->first();
                        if ($planoRegiao) {
                            if ($planoRegiao->gerenciado_plano_assas_franquia === null && $planoRegiao->asaas_assinatura_id == null) {
                                $planoRegiao->gerenciado_plano_assas_franquia = 1;
                                $planoRegiao->save();
                            } else if ($planoRegiao->gerenciado_plano_assas_franquia === null && $planoRegiao->asaas_assinatura_id != null) {
                                $planoRegiao->gerenciado_plano_assas_franquia = 0;
                                $planoRegiao->save();
                            }

                            if ($planoRegiao) {
                                $autorizeAsaas = false;
                                $autorizeAutentique = false;
                                //Plano subscription asaas geerenciado pela franquia
                                if ($planoRegiao->gerenciado_plano_assas_franquia == 1 && $planoRegiao->statusPlano == StatusPlano::$ATIVO) {
                                    $autorizeAsaas = true;
                                } else if ($planoRegiao->gerenciado_plano_assas_franquia == 0 && ($planoRegiao->statusPlano == StatusPlano::$ATIVO || $planoRegiao->statusPlano == StatusPlano::$INADIMPLENTE || $planoRegiao->statusPlano == StatusPlano::$EM_PROCESSO_CANCELAMENTO) && $planoRegiao->asaas_assinatura_id != null && $planoRegiao->data_expiracao != null) {
                                    $diasAtrasado = Formatacao::diasPeriodo(date("Y-m-d"), $planoRegiao->data_expiracao);
                                    if ($diasAtrasado >= -10) {
                                        $autorizeAsaas = true;
                                    }
                                }

                                // if ($planoRegiao->statusPlano != StatusPlano::$CANCELADO) {
                                //     $autorizeAsaas = true;
                                // }



                                if ($planoRegiao->tipo_assinatura == 1 && $planoRegiao->status_afiliado == 1) {
                                    //Altenticado pelo autentique
                                    $autorizeAutentique = true;
                                } else if ($planoRegiao->tipo_assinatura == 2) {
                                    //Autenticado pela franquia
                                    $autorizeAutentique = true;
                                }



                                //Veriifcar indimplencia
                                $isInadimplente = true;
                                $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $orcamento->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
                                if ($franqueadoRegiao) {
                                    $franqueado = Franqueado::where("id", $franqueadoRegiao->franqueado_id)->first();
                                    if ($franqueado) {
                                        $afiliadoFranqueadoAsaas = AfiliadoFranqueadoAsaas::where("afiliado_id", $afiliadosRegiao->afiliado_id)->where("franqueado_id", $franqueado->id)->orderBy("id", "desc")->first();
                                        if ($afiliadoFranqueadoAsaas) {
                                            $vencidas =  $afiliadoFranqueadoAsaas->asaas_cobrancas_vencidas ? json_decode($afiliadoFranqueadoAsaas->asaas_cobrancas_vencidas) : [];
                                            $isInadimplente = Asaas::isPossuiCobrancaVencida($vencidas);
                                        } elseif ($planoRegiao->gerenciado_plano_assas_franquia == 1 && $planoRegiao->statusPlano == StatusPlano::$ATIVO) {
                                            $isInadimplente = false;
                                        }
                                    }
                                }



                                if ($afiliadosRegiao && $afiliadosRegiao->afiliado_id) {
                                    $afiliado = Afiliado::where("id", $afiliadosRegiao->afiliado_id)->first();
                                    if ($afiliado && $afiliado->usuario_app_id) {
                                        $usuarioApp = UsuarioApp::where("id", $afiliado->usuario_app_id)->first();
                                        //Verifica se está autorizado a ver este orçamento
                                        if ($autorizeAsaas && $autorizeAutentique && $usuarioApp && $usuarioApp->data_confirmacao && $isInadimplente === false) {
                                            $afiliadosEnviar[$afiliado->id] = $afiliado;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach ($afiliadosEnviar as $afil) {
            Notificacao::painelNotificarAfiliadoNovaSolicitacao($orcamento, $afil);

            if ($afil->email) {
                SenderEmails::enviarEmailAfiliadosNovaSolicitacao($afil->email, $afil->razao_social, $orcamento->id, $tipo);
            }

            $usuarioApp = UsuarioApp::where("id", $afil->usuario_app_id)->first();
            if ($usuarioApp && $usuarioApp->token_notification) {
                $condominio = Condominio::withTrashed()->where("id", $orcamento->condominio_id)->first();
                SenderNotificacao::enviarNotificacaoNovaSolicitacao($orcamento->id, $usuarioApp->token_notification, $condominio->nome);
            }

            if ($usuarioApp && $afil->email != $usuarioApp->email) {
                SenderEmails::enviarEmailAfiliadosNovaSolicitacao($usuarioApp->email, $afil->razao_social, $orcamento->id, $tipo);
            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orcamento = Orcamento::findOrFail($id);
        $orcamento->load('condominio', 'afiliado', 'categoria');
        return view($this->url . '.orcamentos.show', compact('orcamento'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $categoria_id = null)
    {

        $sindico_param_id = $id;

        $orcamento = Orcamento::findOrFail($id);
        $orcamento->data_inicio_operacao = Formatacao::data($orcamento->data_inicio_operacao, false, false);
        $orcamento->data_fim_operacao = Formatacao::data($orcamento->data_fim_operacao, false, false);

        $sindicop = Sindico::withTrashed()->where("id", $orcamento->condominio()->withTrashed()->first()->sindico()->withTrashed()->first()->id)->first();
        $franqueados = Franqueado::all();
        $sindicos = Sindico::all();

        if ($categoria_id != null) {
            $orcamento->categoria_id = $categoria_id;
        }

        // [HUBBOX FIX] Inicialização de segurança para evitar Undefined Variable
                $franqueado_id = 0;

                if (isset($this->user_franqueado->id) && $this->user_franqueado->id > 0) {
                    $franqueado_id = $this->user_franqueado->id;
                } else {
                    $franqReg = FranqueadoRegiao::where("regiao_id", $orcamento->regiao_id)
                        ->where("status", "ativo")
                        ->orderBy("id", "desc")
                        ->first();

                    if ($franqReg) {
                        $franqueado_id = $franqReg->franqueado_id;
                    }
                }


        $categoria = Categoria::withTrashed()->where("id", $orcamento->categoria_id)->first();

        $afiliadosCategoria = AfiliadoCategoria::where("categoria_id", $orcamento->categoria_id)->get();
        $afiliadosLista = Afiliado::join('afiliado_regiao', 'afiliado_regiao.afiliado_id', 'afiliado.id')
            ->whereIn('afiliado_regiao.regiao_id', function ($query) use ($franqueado_id) {
                $query->select('regiao_id')
                    ->from('franqueado_regiao')
                    ->where('franqueado_regiao.franqueado_id', $franqueado_id);
            })->distinct()->select('afiliado.*')->get();

        $afiliados = [];
        foreach ($afiliadosLista as $afiliado) {
            $addAfiliado = false;
            foreach ($afiliadosCategoria as $afiliadoCategoria) {
                if ($afiliadoCategoria->afiliado_id == $afiliado->id) {
                    $addAfiliado = true;
                }
            }
            if ($addAfiliado == true) {
                $afiliados[] = $afiliado;
            }
        }

        $condominios = Condominio::with("sindico")->get();
        $categorias = Categoria::where("categoria_pai_id", "<>", null)->orderBy('nome', 'asc')->get();
        $afiliadosInteressados = [];
        $afiliadosInteressadosFK = AfiliadoOrcamentoInteresse::where("orcamento_id", $id)->where("descartado_afiliado", 0)->get();
        foreach ($afiliadosInteressadosFK as $afiliadoInteresse) {
            $afiliado = Afiliado::where("id", $afiliadoInteresse->afiliado_id)->first();
            if ($afiliado) {
                $afiliado->parecer =  $afiliadoInteresse;
                $afiliadosInteressados[] = $afiliado;
            }
        }

        if ($orcamento->condominio()->withTrashed()->first()->bairro()->withTrashed()->first()->regiao) {
            $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $orcamento->condominio()->withTrashed()->first()->bairro()->withTrashed()->first()->regiao->id)->orderBy("id", "desc")->first();
            $franqueado_id = $franqueadoRegiao->franqueado_id;
        } else {
            $regiaoFaixaCep = RegiaoFaixaCep::where("cidade_id", $orcamento->condominio()->withTrashed()->first()->bairro()->withTrashed()->first()->cidade->id)->orderBy("id", "desc")->first();

            if ($regiaoFaixaCep) {
                $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $regiaoFaixaCep->regiao_id)->orderBy("id", "desc")->first();
                $franqueado_id = $franqueadoRegiao->franqueado_id;
                $baux = $orcamento->condominio->bairro()->first();
                $baux->regiao_id = $regiaoFaixaCep->regiao_id;
                $baux->update();
            } else {
                $cep = $orcamento->condominio->cep;
                $cont = 0;
                do {
                    $cont++;
                    $regiaoFaixaCep = RegiaoFaixaCep::where("cep", "LIKE", "%" . $cep . "%")->orderBy("id", "desc")->first();
                    $cep = substr($cep, 0, strlen($orcamento->condominio->cep) - $cont);
                    if ($cont == 4) {
                        break;
                    }
                } while (!$regiaoFaixaCep);

                if (isset($regiaoFaixaCep->regiao_id)) {
                    $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $regiaoFaixaCep->regiao_id)->orderBy("id", "desc")->first();
                    $franqueado_id = $franqueadoRegiao->franqueado_id;
                    $baux = $orcamento->condominio()->withTrashed()->first()->bairro()->withTrashed()->first();
                    $baux->regiao_id = $regiaoFaixaCep->regiao_id;
                    $baux->update();
                }
            }
        }


        $isEditable = $orcamento->status == StatusOrcamento::$ANALISANDO_CANDIDATOS || $orcamento->status == StatusOrcamento::$ANALISANDO_ORCAMENTOS || $orcamento->status == StatusOrcamento::$AGUARDANDO_CONTRATO;


        $assinaturas['sindico']['state'] = false;
        $assinaturas['franqueado']['state'] = false;
        $assinaturas['afiliado']['state'] = false;

        $formato_contrato_atual = $orcamento->formato_contrato_atual;
        if ($formato_contrato_atual == 2) {
            $assinaturas['sindico']['state'] = true;
            $assinaturas['franqueado']['state'] = true;
            $assinaturas['afiliado']['state'] = true;
        } elseif ($formato_contrato_atual == 1) {
            $assinaturasLocal = OrcamentoAssinatura::where("orcamento_id", $orcamento->id)->get();
            foreach ($assinaturasLocal as $assLocal) {
                if ($assLocal->tipo_usuario == "franqueado" && $assLocal->signed) {
                    $assinaturas['franqueado']['state'] = true;
                } else if ($assLocal->tipo_usuario == "sindico" && $assLocal->signed) {
                    $assinaturas['sindico']['state'] = true;
                } else if ($assLocal->tipo_usuario == "afiliado" && $assLocal->signed) {
                    $assinaturas['afiliado']['state'] = true;
                }

                if ($assLocal->tipo_usuario == "franqueado") {
                    $assinaturas["franqueado"]["assinatura"] = $assLocal;
                } else if ($assLocal->tipo_usuario == "sindico") {
                    $assinaturas["sindico"]["assinatura"] = $assLocal;
                } else if ($assLocal->tipo_usuario == "afiliado") {
                    $assinaturas["afiliado"]["assinatura"] = $assLocal;
                }
            }
        }

        $afiliadoRegiaoAfiliado = null;
        if ($orcamento->afiliado_id > 0) {
            $afiliadoRegiaoAfiliadoLista = AfiliadoRegiao::where("afiliado_id", $orcamento->afiliado_id)->where("regiao_id", $orcamento->regiao_id)->orderBy("id", "DESC")->get();
            if ($afiliadoRegiaoAfiliadoLista && count($afiliadoRegiaoAfiliadoLista) == 1) {
                $afiliadoRegiaoAfiliado = $afiliadoRegiaoAfiliadoLista[0];
            } elseif ($afiliadoRegiaoAfiliadoLista && count($afiliadoRegiaoAfiliadoLista) > 1) {
                foreach ($afiliadoRegiaoAfiliadoLista as $afiliadoRegiaoAux) {
                    if ($afiliadoRegiaoAux->planoAssinaturaAfiliadoRegiao->statusPlano == 1) {
                        $afiliadoRegiaoAfiliado = $afiliadoRegiaoAux;
                        break;
                    }
                }
            }
        } else {
            $afiliadoRegiaoAfiliado = null;
        }

        $franqueado_id = 0;
        if (isset($this->user_franqueado->id) && $this->user_franqueado->id > 0) {
            $franqueado_id = $this->user_franqueado->id;
        } else {
            $franqReg = FranqueadoRegiao::where("regiao_id", $orcamento->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
            if ($franqReg) {
                $franqueado_id = $franqReg->franqueado_id;
            }
        }

        if ($franqueado_id > 0) {
            $franq = Franqueado::where("id", $franqueado_id)->first();
            $possuiTokenAutentique = $franq->token_autentique ? true : false;
            $email_franqueado = $franq->email;
        } else {
            $possuiTokenAutentique = false;
            $email_franqueado = "";
        }


        $franqueadoRegioes = FranqueadoRegiao::where("franqueado_id", $franqueado_id)->where("status", "ativo")->orderBy("id", "desc")->get();

        return view($this->url . '.orcamentos.edit', compact('email_franqueado', 'possuiTokenAutentique', 'franqueadoRegioes', 'categoria',  'afiliadoRegiaoAfiliado', 'afiliadosInteressados', 'franqueado_id', 'assinaturas', 'formato_contrato_atual', 'isEditable', 'orcamento', 'sindico_param_id', 'sindicop', 'sindicos', 'afiliados', 'condominios', 'categorias', 'franqueados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            //$this->getData($request);
            
            Log::channel('orcamento')->info('Update do orcamento', [
                'request' => $request->all(),
                'id' => $id,
            ]);

            $orcamento = Orcamento::findOrFail($id);
            $alterouFormaContrato = $request["formato_contrato_atual"] != $orcamento->formato_contrato_atual;
            $alterouCategoria = $request['categoria_id'] != $orcamento->categoria_id;

            $isEditable = true;
            $orcamento->nome = $request['nome'];
            $orcamento->descricao = $request['descricao'];
            $orcamento->urgente = $request['urgente'];
            $orcamento->motivo_cancelamento = $request['motivo_cancelamento'];
            if ($request['data_inicio_operacao']) {
                $orcamento->data_inicio_operacao = Formatacao::data($request['data_inicio_operacao'], false, false);
            }

            if ($request['data_fim_operacao']) {
                $orcamento->data_fim_operacao = Formatacao::data($request['data_fim_operacao'], false, false);
            }


            $trocouAfiliado = false;
            if ($request['afiliado_id'] != $orcamento->afiliado_id) {
                $trocouAfiliado = true;
            }

            $orcamento->status = $request['status'];
            if ($orcamento->status_sindico != 5 && $orcamento->status_sindico != 8)
                $orcamento->status_sindico = $orcamento->status;

            if ($orcamento->status_afiliado != 5 && $orcamento->status_afiliado != 9)
                $orcamento->status_afiliado = $orcamento->status;


            if ($isEditable) {
                Log::channel('orcamento')->info('É editavel');
                $orcamento->afiliado_id = $request['afiliado_id'] > 0 ? $request['afiliado_id'] : null;
                $orcamento->categoria_id = $request['categoria_id'];

                $condominio = Condominio::where("id", $orcamento->condominio_id)->first();

                // [HUBBOX FIX] Garante que a solicitação mantenha a mesma região calculada pelo endereço do condomínio
                $this->sincronizarRegiaoOrcamentoComCondominio($orcamento);

                $orcamento->save();
                DB::commit();
                
                Log::channel('orcamento')->info('Emails de testemunhas', [
                    'email_testemunha1' => $request['email_testemunha1'],
                    'email_testemunha2' => $request['email_testemunha2'],
                ]);
                
                // DocumentosAutentique2::enviarContratoServico($orcamento, $request['email_testemunha1'], $request['email_testemunha2']);
                
                Log::channel('orcamento')->info('Verificando contrato e afiliado', [
                    'contrato' => $request['contrato'],
                    'afiliado_id' => $orcamento->afiliado_id,
                ]);
                $orcamento->formato_contrato_atual = $request["formato_contrato_atual"];
                if ($request['contrato'] && $orcamento->afiliado_id > 0) {
                    Log::channel('orcamento')->info('Verificado contrato e afiliado');
                    $orcamento->contrato = $request['contrato']->store('contratos');
                    $orcamento->titulo_contrato = 'Contrato Ref.: #' . $orcamento->id . ' - ' . $orcamento->categoria()->withTrashed()->first()->nome;
                    $orcamento->save();
                    
                    Log::channel('orcamento')->info('Verificando formato_contrato_atual', [
                        'formato_contrato_atual' => $request['formato_contrato_atual']
                    ]);

                    if ($request["formato_contrato_atual"] == "1") {
                        Log::channel('orcamento')->info('Iniciando envio de contrato update', [
                            'orcamento' => $orcamento,
                            'email_testemunha1' => $request['email_testemunha1'],
                            'email_testemunha2' => $request['email_testemunha2']
                        ]);
                        DocumentosAutentique::enviarContratoServico($orcamento, $request['email_testemunha1'], $request['email_testemunha2']);
                    } elseif ($request["formato_contrato_atual"] == "2") {
                        // $orcamento->contrato_original = $orcamento->contrato;
                        // $orcamento->contrato_assinado = $orcamento->contrato;
                        $orcamento->status_sindico = $orcamento->status;
                        $orcamento->status_afiliado = $orcamento->status;
                        $orcamento->save();
                    }
                } else {

                    //Sem contrato
                    $orcamento->status = $request['status'];
                    $orcamento->status_sindico = $orcamento->status;
                    $orcamento->status_afiliado = $orcamento->status;
                    $orcamento->status_testemunha1 = $orcamento->status;
                    $orcamento->status_testemunha2 = $orcamento->status;
                    $orcamento->save();
                    Log::channel('orcamento')->info('caiu no else');
                    // DocumentosAutentique::enviarContratoServico($orcamento, $request['email_testemunha1'], $request['email_testemunha2']);
                }
            }





            $orcamento['condominio'] = $orcamento->condominio()->first();
            $orcamento['status_label'] = StatusOrcamento::getLabel($orcamento->status);
            $orcamento['status_cor'] = StatusOrcamento::getCor($orcamento->status);

            $orcamento['afiliado'] = $orcamento->afiliado()->withTrashed()->first();
            $orcamento['categoria'] = Categoria::where("id", $orcamento->categoria_id)->first();
            $orcamento['status_sindico_label'] = StatusOrcamento::getLabel($orcamento->status_sindico);
            $orcamento['status_sindico_cor'] = StatusOrcamento::getCor($orcamento->status_sindico);

            $orcamento['status_afiliado_label'] = StatusOrcamento::getLabel($orcamento->status_afiliado);
            $orcamento['status_afiliado_cor'] = StatusOrcamento::getCor($orcamento->status_afiliado);




            if ($orcamento->afiliado_id) {
                $afiliado = Afiliado::where("id", $orcamento->afiliado_id)->first();
                if ($afiliado)
                    $usuarioAppAfiliado = UsuarioApp::where("id", $afiliado->usuario_app_id)->first();
                else
                    $usuarioAppAfiliado = null;
            } else
                $usuarioAppAfiliado = null;


            if (isset($orcamento->condominio()->withTrashed()->first()->sindico()->withTrashed()->first()->usuarioApp()->withTrashed()->first()->id))
                $usuarioAppSindico = UsuarioApp::where("id", $orcamento->condominio()->withTrashed()->first()->sindico()->withTrashed()->first()->usuarioApp()->withTrashed()->first()->id)->first();
            else
                $usuarioAppSindico = null;

            if ($usuarioAppAfiliado) {

                if ($trocouAfiliado) {
                    $enderecoCondomino = $condominio->bairro . ", " . $condominio->cidade . "/" . $condominio->estado;
                    $msg = "Você possui um novo serviço para " . $orcamento->categoria->nome . ". Condomínio " . $condominio->nome . ". $enderecoCondomino. Solicitação #$orcamento->id. Status: " . StatusOrcamento::getLabel($orcamento->status);
                    SenderEmails::emailNotification("Solicitação #$orcamento->id criada.", $msg, $usuarioAppAfiliado->email, $usuarioAppAfiliado->email);
                    SenderNotificacao::send($usuarioAppAfiliado->token_notification, $msg, $orcamento);
                }

                if ($alterouFormaContrato && $request["formato_contrato_atual"] != 4) {
                    SenderEmails::emailNotification("Solicitação #$orcamento->id, contrato disponível.", "O Contrato já está disponível", $usuarioAppAfiliado->email, $usuarioAppAfiliado->email);
                    SenderNotificacao::enviarNotificacaoAlteracaoSolicitacaoContrato($usuarioAppAfiliado->token_notification, $orcamento->id, StatusOrcamento::getLabel($orcamento->status), $orcamento);
                } else if (!$trocouAfiliado) {
                    SenderEmails::emailNotification("Solicitação #$orcamento->id alterada", "Confira no App", $usuarioAppAfiliado->email, $usuarioAppAfiliado->email);
                    SenderNotificacao::enviarNotificacaoAlteracaoSolicitacao($usuarioAppAfiliado->token_notification, $orcamento->id, StatusOrcamento::getLabel($orcamento->status), $orcamento);
                }
            } else if ($alterouCategoria) {
                $this->senderEnviarEmailAfiliados($orcamento);
            }

            if ($usuarioAppSindico) {
                if ($alterouFormaContrato && $request["formato_contrato_atual"] != 4) {
                    SenderEmails::emailNotification("Solicitação #$orcamento->id, contrato disponível", "Contrato já está disponível", $usuarioAppSindico->email, $usuarioAppSindico->email);
                    SenderNotificacao::enviarNotificacaoAlteracaoSolicitacaoContrato($usuarioAppSindico->token_notification, $orcamento->id, StatusOrcamento::getLabel($orcamento->status), $orcamento);
                } else {
                    SenderEmails::emailNotification("Solicitação #$orcamento->id alterada", "Confira no App", $usuarioAppSindico->email, $usuarioAppSindico->email);
                    SenderNotificacao::enviarNotificacaoAlteracaoSolicitacao($usuarioAppSindico->token_notification, $orcamento->id, StatusOrcamento::getLabel($orcamento->status), $orcamento);
                }
            }


            if ($this->url == 'admin') {
                return redirect("admin/orcamentos/" . $orcamento->id . "/edit")->with('success_message', 'Solicitação foi adicionada com sucesso.');
            } else {
                return redirect("admin_franqueado/orcamentos/" . $orcamento->id . "/edit")->with('success_message', 'Solicitação foi adicionada com sucesso.');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors($this->getData($request));
        }
    }

    public function getRegiaoByCondominio($condominio)
    {
        $bairro_condominio = Bairro::where("id", $condominio->bairro_id)->first();
        if (!$bairro_condominio) {
            return null;
        }
        $regiao_id_bairro_condominio = $bairro_condominio->regiao_id;

        if (!($regiao_id_bairro_condominio > 0)) {
            $cidadesRegiao = RegiaoFaixaCep::where("cidade_id", $bairro_condominio->cidade_id)->first();
            if ($cidadesRegiao) {
                $regiao_id_bairro_condominio = $cidadesRegiao->regiao_id;
            }
        }

        if (!($regiao_id_bairro_condominio > 0)) {
            $condominio->cep = str_replace("-", "", $condominio->cep);
            $cep = $condominio->cep;
            $cont = 0;
            do {
                $cont++;
                $regiaoFaixaCep = RegiaoFaixaCep::where("cep", "LIKE", $cep)->orderBy("id", "desc")->first();
                $cep = substr($cep, 0, strlen($condominio->cep) - $cont);
                if ($cep == "" || strlen($cep) <= 5 || $cont == 6) {
                    break;
                }
            } while (!$regiaoFaixaCep);

            if ($regiaoFaixaCep) {
                $regiao_id_bairro_condominio = $regiaoFaixaCep->regiao_id;
            }
        }

        return $regiao_id_bairro_condominio;
    }

    // [HUBBOX FIX] Sincroniza regiao_id da solicitação com o endereço atual do condomínio (bairro/cidade/CEP)
    private function sincronizarRegiaoOrcamentoComCondominio($orcamento)
    {
        $condominio = $orcamento->condominio()->withTrashed()->first();
        if (!$condominio) {
            return;
        }

        $regiaoCalculada = $this->getRegiaoByCondominio($condominio);
        $regiaoAtual = Regiao::where("id", $orcamento->regiao_id)->first();

        // [HUBBOX FIX] Atualiza quando a solicitação está sem região, com região inválida ou divergente do condomínio
        if (
            $regiaoCalculada > 0 &&
            (
                !($orcamento->regiao_id > 0) ||
                $orcamento->regiao_id == 12 ||
                !$regiaoAtual ||
                (int) $orcamento->regiao_id !== (int) $regiaoCalculada
            )
        ) {
            $orcamento->regiao_id = $regiaoCalculada;
            $orcamento->save();
            return;
        }

        // [HUBBOX FIX] Mantém fallback histórico de "sem região" quando não é possível calcular uma região válida
        if (!($orcamento->regiao_id > 0)) {
            $orcamento->regiao_id = 12;
            $orcamento->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $orcamento = Orcamento::findOrFail($id);
            if (isset($this->user_franqueado->id)) {
                $orcamento->removido_por = $this->url . " - Removido por Franquia #" . $this->user_franqueado->id . " - " . $this->user_franqueado->nome;
            } else {
                $orcamento->removido_por = $this->url . " - Removido por Super Admin";
            }
            $orcamento->update();
            $orcamento->delete();
            return redirect()->route($this->url . '.orcamentos.index')
                ->with('success_message', 'Orcamento foi deletado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Erro ao deletar orcamento, tente mais tarde.');
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
            'nome' => 'nullable|string',
            'descricao' => 'required|string',
            'status' => 'required|int',
            'condominio_id' => 'required',
            'categoria_id' => 'required',
        ];

        $data = $request->validate($rules);

        return $data;
    }

    public function vistoria($id)
    {
        $vistoria = Vistoria::where('orcamento_id', $id)->first();
        return view('admin_franqueado.orcamentos.vistorias.show', compact('vistoria'));
    }

    public function fetchOrcamentosByCondominio($condominio_id = null)
    {
        try {
            $orcamentos = Orcamento::where("condominio_id", $condominio_id)->orderBy("id", "desc")->get();
            $orcamentos_html = [];
            $html = '<div class="col-md-6"><div class="card">
                        <div class="card-header card-header-primary">
                            <h5 class="card-title">[TITULO]</h5>
                            <a style="position: absolute; right: 4px; top: 5px" class="btn btn-warning" href="../orcamentos/[ORCAMENTO_ID]/edit">Visualizar</a>
                        </div>
                        <div class="card-body">
                        <div class="item-solicitacao">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label style="display: block; text-align: left; margin-bottom: -4px; margin-left: -5px;" class="badge badge-default">Status</label>
                                        <label class="badge badge-[COLOR_THEME]">[STATUS]</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="display: block; text-align: left; margin-bottom: 4px;" class="badge badge-default">Afiliado</label>
                                        <label style="white-space: pre-wrap; font-size: 14px;" class="badge badge-info">[AFILIADO_NOME]</label>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                    <h5>
                                        [TITULO]
                                    </h5>
                                    <label style="display: block; text-align: left; margin-bottom: -4px; margin-left: -5px;" class="badge badge-default">Descrição</label>
                                    <p>
                                        [DESCRICAO]
                                    </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label style="display: block; text-align: left; margin-bottom: -4px; " class="badge badge-default">Local</label>
                                        <label style="white-space: pre-wrap;text-align: left; font-size: 14px;" class="badge badge-default">[LOCAL]</label>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label style="display: block; text-align: left; margin-bottom: -4px; margin-left: -5px;" class="badge badge-default">Avaliação</label>
                                        [AVALIACAO]
                                    </div>
                                </div>
                        </div>
                        </div>
                    </div></div>';
            foreach ($orcamentos as $orcamento) {
                $orcamento->afiliado = Afiliado::where("id", $orcamento->afiliado_id)->first();
                $orcamento->bairroFK = Bairro::where("id", $orcamento->condominio->bairro_id)->first();
                $html_aux = str_replace("[TITULO]", $orcamento->nome ? 'Solicitação #' . $orcamento->id . " - " . $orcamento->nome : 'Solicitação #' . $orcamento->id, $html);
                $html_aux = str_replace("[AFILIADO_NOME]", (isset($orcamento->afiliado()->withTrashed()->first()->razao_social) ? $orcamento->afiliado()->withTrashed()->first()->razao_social : "Sem afiliado"), $html_aux);
                $html_aux = str_replace("[DESCRICAO]", (isset($orcamento->descricao) ? $orcamento->descricao : "--"), $html_aux);
                $html_aux = str_replace("[STATUS]", (isset($orcamento->status) ? StatusOrcamento::getLabel($orcamento->status) : "--"), $html_aux);
                $html_aux = str_replace("[COLOR_THEME]", (isset($orcamento->status) ? StatusOrcamento::getColorTheme($orcamento->status) : "--"), $html_aux);
                $html_aux = str_replace("[ORCAMENTO_ID]", (isset($orcamento->id) ? $orcamento->id : "--"), $html_aux);
                $html_aux = str_replace("[LOCAL]", $orcamento->condominio->endereco . ". " . $orcamento->bairroFK->nome . ", " . $orcamento->bairroFK->cidade->nome . "/" . $orcamento->bairroFK->cidade->estado->uf, $html_aux);
                $html_aux = str_replace("[AVALIACAO]", ($orcamento->avaliacao == 0 ? '<label class="badge badge-secondary">Não foi avaliado</label>' : '<label class="badge badge-success">Nota: ' . round($orcamento->avaliacao) . '</label>'), $html_aux);

                $orcamentos_html[] = $html_aux;
            }
            return $orcamentos_html;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function fetchOrcamentosByRegiao($regiao_id = null, $afiliado_id = null)
    {
        try {

            $orcamentos = Orcamento::where("regiao_id", $regiao_id)->where("afiliado_id", $afiliado_id)->get();
            $orcamentos_html = [];
            $html = '<div class="col-md-6"><div class="card">
                        <div class="card-header card-header-primary">
                            <h5 class="card-title">[TITULO]</h5>
                            <a style="position: absolute; right: 4px; top: 5px" class="btn btn-warning" href="../orcamentos/[ORCAMENTO_ID]/edit">Editar</a>
                        </div>
                        <div class="card-body">
                        <div class="item-solicitacao">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label style="display: block; text-align: left; margin-bottom: -4px; margin-left: -5px;" class="badge badge-default">Status</label>
                                        <label class="badge badge-[COLOR_THEME]">[STATUS]</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="display: block; text-align: left; margin-bottom: 4px;" class="badge badge-default">Afiliado</label>
                                        <label style="white-space: pre-wrap; font-size: 14px;" class="badge badge-info">[AFILIADO_NOME]</label>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                    <h5>
                                        [TITULO]
                                    </h5>
                                    <label style="display: block; text-align: left; margin-bottom: -4px; margin-left: -5px;" class="badge badge-default">Descrição</label>
                                    <p>
                                        [DESCRICAO]
                                    </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label style="display: block; text-align: left; margin-bottom: -4px; " class="badge badge-default">Local</label>
                                        <label style="white-space: pre-wrap;text-align: left; font-size: 14px;" class="badge badge-default">[LOCAL]</label>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label style="display: block; text-align: left; margin-bottom: -4px; margin-left: -5px;" class="badge badge-default">Vistorias</label>
                                        <label class="badge badge-secondary">Vistorias</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="display: block; text-align: left; margin-bottom: -4px; margin-left: -5px;" class="badge badge-default">Avaliação</label>
                                        <label class="badge badge-secondary">Não foi avaliado</label>
                                    </div>
                                </div>
                        </div>
                        </div>
                    </div></div>';

            foreach ($orcamentos as $orcamento) {
                $orcamento->afiliado = Afiliado::where("id", $orcamento->afiliado_id)->first();
                $orcamento->bairroFK = Bairro::where("id", $orcamento->condominio->bairro_id)->first();
                if ($orcamento->condominio->bairro_id) {
                    $html_aux = str_replace("[TITULO]", $orcamento->nome ? 'Solicitação #' . $orcamento->id . " - " . $orcamento->nome : 'Solicitação #' . $orcamento->id, $html);
                    $html_aux = str_replace("[AFILIADO_NOME]", (isset($orcamento->afiliado()->withTrashed()->first()->razao_social) ? $orcamento->afiliado()->withTrashed()->first()->razao_social : "Sem afiliado"), $html_aux);
                    $html_aux = str_replace("[DESCRICAO]", (isset($orcamento->descricao) ? $orcamento->descricao : "--"), $html_aux);
                    $html_aux = str_replace("[STATUS]", (isset($orcamento->status) ? StatusOrcamento::getLabel($orcamento->status) : "--"), $html_aux);
                    $html_aux = str_replace("[COLOR_THEME]", (isset($orcamento->status) ? StatusOrcamento::getColorTheme($orcamento->status) : "--"), $html_aux);
                    $html_aux = str_replace("[ORCAMENTO_ID]", (isset($orcamento->id) ? $orcamento->id : "--"), $html_aux);
                    $html_aux = str_replace("[LOCAL]", $orcamento->condominio->endereco . ". " . $orcamento->bairroFK->nome . ", " . $orcamento->bairroFK->cidade->nome . "/" . $orcamento->bairroFK->cidade->estado->uf, $html_aux);
                } else {
                    $html_aux = str_replace("[TITULO]", $orcamento->nome ? 'Solicitação #' . $orcamento->id . " - " . $orcamento->nome : 'Solicitação #' . $orcamento->id, $html);
                    $html_aux = str_replace("[AFILIADO_NOME]", (isset($orcamento->afiliado()->withTrashed()->first()->razao_social) ? $orcamento->afiliado()->withTrashed()->first()->razao_social : "Sem afiliado"), $html_aux);
                    $html_aux = str_replace("[DESCRICAO]", (isset($orcamento->descricao) ? $orcamento->descricao : "--"), $html_aux);
                    $html_aux = str_replace("[STATUS]", (isset($orcamento->status) ? StatusOrcamento::getLabel($orcamento->status) : "--"), $html_aux);
                    $html_aux = str_replace("[COLOR_THEME]", (isset($orcamento->status) ? StatusOrcamento::getColorTheme($orcamento->status) : "--"), $html_aux);
                    $html_aux = str_replace("[ORCAMENTO_ID]", (isset($orcamento->id) ? $orcamento->id : "--"), $html_aux);
                    $html_aux = str_replace("[LOCAL]", $orcamento->endereco . ". " . $orcamento->bairro . ", " . $orcamento->cidade . "/" . $orcamento->estado . ". Não vinculado.", $html_aux);
                }

                $orcamentos_html[] = $html_aux;
            }
            return $orcamentos_html;
        } catch (Exception $e) {
            return $e;
        }
    }

    private function getAssinaturasIndex($franqueado_id)
    {
        $assinaturas = [];
        $franqueado = Franqueado::where("id", $franqueado_id)->first();
        if ($franqueado->token_autentique) {
            $dados = json_decode(DocumentosAutentique::listAll($franqueado->token_autentique));
            if ($dados || isset($dados->data)) {
                $documentos = [];
                if (isset($dados->data)) {
                    if (isset($dados->data->documents)) {
                        if (isset($dados->data->documents->data)) {
                            $documentos = $dados->data->documents->data;
                        }
                    }
                }

                foreach ($documentos as $i => $doc) {
                    $document_id_autentique = $doc->id;
                    $orcamento = Orcamento::where("documento_id_autentique", $document_id_autentique)->first();

                    if ($orcamento) {
                        if ($orcamento->contrato_original == null) {
                            $orcamento->contrato_original = isset($doc->files->original) ? $doc->files->original : null;
                        }

                        if ($orcamento->contrato_assinado == null) {
                            $orcamento->contrato_assinado = isset($doc->files->signed) ? $doc->files->signed : null;
                        }

                        foreach ($doc->signatures as $j => $assinatura) {
                            $assinaturaLocal = OrcamentoAssinatura::where("public_id", $assinatura->public_id)->first();
                            if ($assinaturaLocal) {
                                $assinaturaLocal->signed = isset($assinatura->signed->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->signed->created_at)) : null;
                                $assinaturaLocal->viewed = isset($assinatura->viewed->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->viewed->created_at)) : null;
                                $assinaturaLocal->rejected = isset($assinatura->rejected->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->rejected->created_at)) : null;
                                $assinaturaLocal->update();
                            }
                        }

                        $assinaturaLocals = OrcamentoAssinatura::where("orcamento_id", $orcamento->id)->get();
                        $assinadoFranqueado = false;
                        $assinadoSindico = false;
                        $assinadoAfiliado = false;

                        foreach ($assinaturaLocals as $assinaturaLocal) {
                            if ($assinaturaLocal->signed) {
                                switch ($assinaturaLocal->tipo_usuario) {
                                    case "franqueado":
                                        $assinadoFranqueado = true;
                                        $assinaturas[$orcamento->id]["franqueado"] = $assinaturaLocal;
                                        break;
                                    case "sindico":
                                        $assinadoSindico = true;
                                        $assinaturas[$orcamento->id]["sindico"] = $assinaturaLocal;
                                        break;
                                    case "afiliado":
                                        $assinadoAfiliado = true;
                                        $assinaturas[$orcamento->id]["afiliado"] = $assinaturaLocal;
                                        break;
                                    case "testemunha1":
                                        $assinadoTestemunha1 = true;
                                        $assinaturas[$orcamento->id]["testemunha1"] = $assinaturaLocal;
                                        break;
                                    case "testemunha2":
                                        $assinadoTestemunha2 = true;
                                        $assinaturas[$orcamento->id]["testemunha2"] = $assinaturaLocal;
                                        break;
                                }
                            }
                        }
                        try {
                            $orcamento->update();
                        } catch (Exception $e) {
                        }
                    }
                }
            }
        }
        return $assinaturas;
    }
}
