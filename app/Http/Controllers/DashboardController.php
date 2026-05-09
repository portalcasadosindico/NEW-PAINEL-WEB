<?php

namespace App\Http\Controllers;

use App\Exports\AfiliadosExport;
use App\Models\Afiliado;
use App\Models\AfiliadoCategoria;
use App\Models\AfiliadoRegiao;
use App\Models\Bairro;
use App\Models\CartaoCnpj;
use App\Models\Categoria;
use App\Models\Condominio;
use App\Models\Configuracao;
use App\Models\ContratoSocial;
use App\Models\Franqueado;
use App\Models\FranqueadoRegiao;
use App\Models\FranqueadoRegiaoPlanoDisponibilizado;
use App\Models\Orcamento;
use App\Models\Parceiro;
use App\Models\PlanoAssinaturaAfiliadoRegiao;
use App\Models\Regiao;
use App\Models\Sindico;
use App\Models\UsuarioApp;
use App\Models\Vistoria;
use App\Uteis\Formatacao;
use App\Uteis\ModusOperandiStatus;
use App\Uteis\StatusOrcamento;
// [HUBBOX FIX] Imports
use App\Uteis\StatusPlano;
use App\Uteis\StatusVistoria;
use App\Uteis\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

use Spatie\Async\Pool;

use DataTables;

class DashboardController extends Controller
{
  // [HUBBOX FIX] Detecta corretamente o contexto admin/admin_franqueado para carregar a dashboard esperada
  public function __construct(Request $request)
  {
    // [HUBBOX FIX] Preserva a inicialização original do controller pai antes de ajustar o contexto desta dashboard
    if (method_exists(get_parent_class($this), '__construct')) {
      parent::__construct($request);
    }

    if (
      $request->is('admin_franqueado') ||
      $request->is('admin_franqueado/*') ||
      $request->is('dashboard_franqueado') ||
      $request->is('dashboard_franqueado/*')
    ) {
      $this->url = 'admin_franqueado';
      // [HUBBOX FIX] Define o guard correto da franquia para evitar fallback inválido para "web"
      $this->guard = 'franqueados';
    } else {
      $this->url = 'admin';
      // [HUBBOX FIX] Define o guard correto do admin para evitar fallback inválido para "web"
      $this->guard = 'admins';
    }
  }

  /**
   * Verifica a url e redireciona para index correta.
   * Caso o usuario não estiver logado redireciona para a página de login.
   */
  public function index()
  {

    if (Auth::guard($this->guard)->check()) {
      switch ($this->url) {
        case 'admin_franqueado':
          return $this->populateFranqueadoIndex();
          break;
        default:
          return $this->populateAdminIndex();
          break;
      }
    } else {
      return redirect()->route($this->url . '.login');
    }
  }
  /**
   * Verifica a url e redireciona para a página de pendências correta.
   * Caso o usuario não estiver logado redireciona para a página de login.
   **/
  public function pendencias()
  {
    if (Auth::guard($this->guard)->check()) {
      switch ($this->url) {
        case 'admin_franqueado':
          return $this->getPendenciasFranqueado();
          break;
        default:
          return $this->getPendenciasAdmin();
          break;
      }
      return $this->getPendencias();
    } else {
      return redirect()->route($this->url . '.login');
    }
  }



  private function getAfiliados($franqueadoRegioes)
  {
    $afiliadosLnha = Afiliado::get();
    $afiliados = [];
    foreach ($afiliadosLnha as $afiliado) {
      $afiliadoRegioes = AfiliadoRegiao::where("afiliado_id", $afiliado->id)->get();
      foreach ($afiliadoRegioes as $afiliadoRegiao) {
        foreach ($franqueadoRegioes as $franqueadoRegiaoLinha) {
          if ($franqueadoRegiaoLinha->regiao_id == $franqueadoRegiaoLinha->regiao_id) {
            foreach ($franqueadoRegioes as $franqueadoREgiao) {
              if ($afiliadoRegiao->regiao_id == $franqueadoREgiao->regiao_id) {
                $afiliados[$afiliado->id] = $afiliado;
              }
            }
          }
        }
      }
    }

    $afiliadosLnha = Afiliado::where("franqueado_id", $this->user_franqueado->id)->get();
    foreach ($afiliadosLnha as $afiliado) {
      $afiliados[$afiliado->id] = $afiliado;
    }

    if ($afiliados)
      krsort($afiliados);

    $afiliadosAux = $afiliados;
    $afiliados = [];
    foreach ($afiliadosAux as $afiliado) {
      if (count($afiliados) == 10) {
        break;
      }
      $afiliados[] = $afiliado;
    }

    return $afiliados;
  }

  /**
   * Pega as informações necessárias para preencher a index do franqueado
   **/
  private function populateFranqueadoIndex()
  {

    $afiliados_inativos = Afiliado::where('status', 'inativo')->select('id')->count();

    $franqueadoRegioes = FranqueadoRegiao::where("franqueado_id", $this->user_franqueado->id)
      ->where("status", "ativo")
      ->orderBy("id", "desc")
      ->select('regiao_id')
      ->get();
    $afiliados = $this->getAfiliados($franqueadoRegioes);


    $planos_ativos = 0;
    $valor_planos_afiliados_ativos = 0;
    $valor_planos_afiliados_ativos_com_dessconto = 0;
    $t = [];
    foreach ($franqueadoRegioes as $franqueado_regiao) {
      $afiliadosRegioes = AfiliadoRegiao::where("regiao_id", $franqueado_regiao->regiao_id)
        ->orderBy("id", "desc")
        ->select('afiliado_id', 'plano_assinatura_afiliado_regiao_id', 'regiao_id')
        ->get();
      foreach ($afiliadosRegioes as $afiliadoRegiao) {
        if ($franqueado_regiao->regiao_id == $afiliadoRegiao->regiao_id) {
          $planoAfiliado = PlanoAssinaturaAfiliadoRegiao::where("id", $afiliadoRegiao->plano_assinatura_afiliado_regiao_id)->whereIn("statusPlano", [StatusPlano::$ATIVO, StatusPlano::$INADIMPLENTE])->first();
          if ($planoAfiliado) {
            $afiliado = Afiliado::where("id", $afiliadoRegiao->afiliado_id)->select('usuario_app_id', 'id', 'razao_social')->first();
            if ($afiliado) {
              $usuarioApp = UsuarioApp::where("id", $afiliado->usuario_app_id)->first();
              if ($usuarioApp) {
                if ($planoAfiliado->statusPlano == StatusPlano::$ATIVO) {
                  $planos_ativos++;
                  $valor_planos_afiliados_ativos += $planoAfiliado->valor;
                  $valor_planos_afiliados_ativos_com_dessconto += $planoAfiliado->valor - ($planoAfiliado->valor * ($planoAfiliado->desconto / 100));
                }
                $t[] = [
                  "razao_social" => $afiliado->razao_social . " (" . $planoAfiliado->asaas_customer_id . ")",
                  "nome_plano" => $planoAfiliado->nome . ". Criado em \n" . Formatacao::data($planoAfiliado->data_cadastro),
                  "valor" => "R$ " . $planoAfiliado->valor,
                  "valor_desconto" => "R$ " . ($planoAfiliado->valor - ($planoAfiliado->valor * ($planoAfiliado->desconto / 100))),
                  "afiliado_id" => $afiliado->id,
                  "desconto" => $planoAfiliado->desconto,
                  "data_expiracao" => $planoAfiliado->data_expiracao ? Formatacao::data($planoAfiliado->data_expiracao) : "Gerenciado pela franquia",
                  "cor" => $planoAfiliado->statusPlano == StatusPlano::$ATIVO ? "--success" : "--warning"
                ];
              }
            }
          }
        }
      }
    }
    $valor_planos_afiliados_ativos = Formatacao::prefixoSufixo($valor_planos_afiliados_ativos);
    $valor_planos_afiliados_ativos_com_dessconto = Formatacao::prefixoSufixo($valor_planos_afiliados_ativos_com_dessconto);

    $sindicos = [];
    $sindicosAll = Sindico::where("franqueado_id", null)->orWhere("franqueado_id", $this->user_franqueado->id)
      ->orderBy("id", "desc")
      ->limit(11)
      ->get();
    foreach ($sindicosAll as $sindico) {
      $condominios = Condominio::where("sindico_id", $sindico->id)->get();
      $addSindico = false;

      foreach ($condominios as $c) {
        if (isset($c->bairro()->first()->regiao)) {
          $franqueado_condominio_id = FranqueadoRegiao::where("regiao_id", $c->bairro()->first()->regiao->id)->orderBy("id", "desc")->first()->franqueado_id;
          if ($franqueado_condominio_id == $this->user_franqueado->id) {
            $addSindico = true;
          }
        } else if ($this->user_franqueado->id == 1) {
          $addSindico = true;
        }
      }
      if ($addSindico) {
        $sindicos[$sindico->id] = $sindico;
      }
    }

    if ($sindicos)
      krsort($sindicos);

    $sindicosAux = $sindicos;
    $sindicos = [];
    foreach ($sindicosAux as $s) {
      if (count($sindicos) == 10) {
        break;
      }
      $sindicos[] = $s;
    }

    $fid = Auth::guard('franqueados')->user()->id;
    $franqueadoRegioes = FranqueadoRegiao::where("franqueado_id", $this->user_franqueado->id)->where("status", "ativo")->orderBy("id", "desc")->get();

    $afiliadosAll = Afiliado::join('afiliado_regiao', 'afiliado_regiao.afiliado_id', 'afiliado.id')
      ->whereIn('afiliado_regiao.regiao_id', function ($query) {
        $query->select('regiao_id')
          ->from('franqueado_regiao')
          ->where("franqueado_regiao.status", "ativo")
          ->where('franqueado_regiao.franqueado_id', Auth::guard('franqueados')->user()->id);
      })->distinct()->select('afiliado.*')->orderBy("afiliado.id", "desc")->get();

    $afiliados_sem_contrato = 0;
    $documentosPendentesShow = [];

    $afiliados_sem_contrato_result = [];
    foreach ($afiliadosAll as $afiliadoLinha) {
      $afiliadoRegiaoAuxiliarLista = AfiliadoRegiao::where("afiliado_id", $afiliadoLinha->id)->where("modo", Util::getModusOperandi())->get();

      foreach ($afiliadoRegiaoAuxiliarLista as $afiliadoRegiaoAuxiliar) {
        $regiao = Regiao::where("id", $afiliadoRegiaoAuxiliar->regiao_id)->first();

        foreach ($franqueadoRegioes as $franqueadoRegiaoLinha) {
          if ($regiao && $franqueadoRegiaoLinha->regiao_id == $regiao->id) {
            $afiliado = Afiliado::where("id", $afiliadoRegiaoAuxiliar->afiliado_id)->first();
            if ($regiao && $afiliado) {
              $contrato = PlanoAssinaturaAfiliadoRegiao::where("id", $afiliadoRegiaoAuxiliar->plano_assinatura_afiliado_regiao_id)->whereNull("arquivo_original")->first();
              if ($contrato) {
                $franqueado_regiao_plano_disponibilizado = FranqueadoRegiaoPlanoDisponibilizado::where("id", $contrato->franqueado_regiao_plano_disponibilizado_id)->first();
                if ($franqueado_regiao_plano_disponibilizado) {
                  $franqueado_regiao = FranqueadoRegiao::where("id", $franqueado_regiao_plano_disponibilizado->franqueado_regiao_id)->first();
                  if ($franqueado_regiao) {
                    if ($contrato && $franqueado_regiao->status == "ativo" && ($contrato->statusPlano == StatusPlano::$ATIVO || $contrato->statusPlano == StatusPlano::$PENDENTE)) {
                      //$afiliados_sem_contrato++;
                      $afiliados_sem_contrato_result[$afiliado->id] = $afiliado;
                    } else if ($contrato && $franqueado_regiao->status == "inativo" && ($contrato->statusPlano == StatusPlano::$ATIVO || $contrato->statusPlano == StatusPlano::$PENDENTE)) {
                      $afiliados_sem_contrato_result[$afiliado->id] = $afiliado;
                    }
                  }
                }
              }
            }
          }
        }
      }
    }

    $afiliados_sem_contrato = count($afiliados_sem_contrato_result);


    // END Solicitações
    return view($this->url . '.index', compact(
      'afiliados_sem_contrato',
      'franqueadoRegioes',
      'fid',
      'afiliados_sem_contrato_result',
      'planos_ativos',
      'afiliados_inativos',
      'valor_planos_afiliados_ativos',
      'valor_planos_afiliados_ativos_com_dessconto',
      'sindicos',
      'afiliados',
      't'
    ));
  }

  public function getSolicitacoesAndamentoFranqueado()
  {
    $solicitacoesLinha = Orcamento::join('franqueado_regiao', 'franqueado_regiao.regiao_id', 'orcamento.regiao_id')->where('franqueado_regiao.franqueado_id', Auth::guard('franqueados')->id())->distinct()->select('orcamento.*')->orderBy('orcamento.id', 'desc')->get();
    $solicitacoes = [];

    foreach ($solicitacoesLinha as $key => $value) {

      if ($value->status <= 4 || $value->status == 10) {
        $value->bairroFK = Bairro::where("id", $value->condominio->bairro_id)->first();
        $solicitacoes[] = $value;
      }
    }
  }

  public function getAfiliadosSemRegiao()
  {
    $afiliadosSemRegiao = 0;
    $afiliadosAux = Afiliado::orderBy("id", "desc")->get(['id']);
    foreach ($afiliadosAux as $afiliado) {
      $afiliadoRegiao = AfiliadoRegiao::where("afiliado_id", $afiliado->id)->first();
      if (!$afiliadoRegiao) {
        $afiliadosSemRegiao++;
      }
    }
    return response()->json(
      $afiliadosSemRegiao
    );
  }

  public function getSolicitacoesStatus($status)
    {
      // [HUBBOX FIX] Converte a string "1,2,3" recebida via AJAX em um array real para o Laravel
      $statusArray = explode(',', $status);
      
      return response()->json(
          Orcamento::whereIn("status", $statusArray)->count()
      );
    }

    public function getSolicitacoesFranqueadosStatus($status)
    {
      // [HUBBOX FIX] Converte a string "1,2,3" recebida via AJAX em um array real para o Laravel
      $statusArray = explode(',', $status);
      
      $solicitacoesAux = Orcamento::whereIn("status", $statusArray)->where("modo", Util::getModusOperandi())->get();
      $solicitacoes = 0;
      foreach ($solicitacoesAux as $solicitacao) {
        $franqueadoRegiao = FranqueadoRegiao::where("franqueado_id", $this->user_franqueado->id)
            ->where("regiao_id", $solicitacao->regiao_id)
            ->where("status", "ativo")
            ->orderBy("id", "desc")
            ->first();
            
        if ($franqueadoRegiao) {
            $solicitacoes++;
        }
      }
      return response()->json($solicitacoes);
    }

  public function getSolicitacoesVistorias()
  {
    $solicitacoesDisputaAux = Vistoria::where("status", StatusVistoria::$PENDENTE)->get();
    $solicitacoesDisputa = 0;
    foreach ($solicitacoesDisputaAux as $vistoria) {
      $solicitacao = Orcamento::where("id", $vistoria->orcamento_id)->first();
      if ($solicitacao) {
        $franqueadoRegiao = FranqueadoRegiao::where("franqueado_id", $this->user_franqueado->id)->where("regiao_id", $solicitacao->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
        if ($franqueadoRegiao) {
          $solicitacoesDisputa++;
        }
      }
    }
    return response()->json($solicitacoesDisputa);
  }

  public function getSolicitacoesAndamento()
    {
      $solicitacoes = [];
      $solicitacoesLinha = Orcamento::where('orcamento.status', '<>', null)->select('orcamento.*')->orderBy('orcamento.id', 'desc')->paginate(25);

      foreach ($solicitacoesLinha as $key => $value) {
          if ($value->status <= 4 || $value->status == 10) {
              // [HUBBOX FIX] Prevenção de quebra na requisição AJAX
              $bairro_id = optional($value->condominio)->bairro_id;
              $value->bairroFK = $bairro_id ? Bairro::where("id", $bairro_id)->first() : null;
              $solicitacoes[] = $value;
          }
      }

      $list = view('admin.dashboard.solicitacoes-andamento')->with('solicitacoes', $solicitacoes)->render();
      return response()->json($list);
    }

  public function getSolicitacoesFranqueadoAndamento()
    {
      $solicitacoesLinha = Orcamento::join('franqueado_regiao', 'franqueado_regiao.regiao_id', 'orcamento.regiao_id')->where('franqueado_regiao.franqueado_id', Auth::guard('franqueados')->id())->distinct()->select('orcamento.*')->orderBy('orcamento.id', 'desc')->get();
      $solicitacoes = [];
      
      foreach ($solicitacoesLinha as $key => $value) {
          if ($value->status <= 4 || $value->status == 10) {
              // [HUBBOX FIX] Prevenção de quebra na requisição AJAX
              $bairro_id = optional($value->condominio)->bairro_id;
              $value->bairroFK = $bairro_id ? Bairro::where("id", $bairro_id)->first() : null;
              $solicitacoes[] = $value;
          }
      }

      $list = view('admin_franqueado.dashboard.solicitacoes-andamento')->with('solicitacoes', $solicitacoes)->render();
      return response()->json($list);
    }


  public function getAfiliadosAtivos()
  {
    $planos_ativos_afiliado = PlanoAssinaturaAfiliadoRegiao::where("statusPlano", 1)->get();

    $planos_ativos = 0;
    $valor_planos_afiliados_ativos = 0;

    foreach ($planos_ativos_afiliado as $plano_ativo) {
      $afiliado_regiao = AfiliadoRegiao::where("plano_assinatura_afiliado_regiao_id", $plano_ativo->id)->first();
      if ($afiliado_regiao) {
        $afiliado = Afiliado::where("id", $afiliado_regiao->afiliado_id)->first();
        if ($afiliado) {
          $usuarioApp = UsuarioApp::where("id", $afiliado->usuario_app_id)->first();
          if ($usuarioApp) {
            $planos_ativos++;
            $valor_planos_afiliados_ativos += $plano_ativo->valor;
          }
        }
      }
    }

    return response()->json($planos_ativos);
  }


  public function getFranqueados()
  {
    $categorias = [];
    $categorias = Categoria::all();
    foreach ($categorias as $categoria) {
      $categoria->afiliados = 0;
      $categoria->concluidas = 0;
      $categoria->canceladas = 0;
      foreach ($categoria->afiliadoCategorias as $afiliadoCategoria) {
        if (optional($afiliadoCategoria->afiliado)->status == 'ativo') {
          $categoria->afiliados++;
        };
      }
      foreach ($categoria->orcamentos as $orcamento) {
        if ($orcamento->status == StatusOrcamento::$FINALIZADO) {
          $categoria->concluidas++;
        }
        if (
          $orcamento->status == StatusOrcamento::$CANCELADO_PELO_ADMIN ||
          $orcamento->status == StatusOrcamento::$CANCELADO_PELO_FRANQUEADO ||
          $orcamento->status == StatusOrcamento::$CANCELADO_PELO_SINDICO ||
          $orcamento->status == StatusOrcamento::$CANCELADO_PELO_AFILIADO
        ) {
          $categoria->canceladas++;
        }
      }
    }
    $list = view('admin.dashboard.categorias')->with('categorias', $categorias)->render();
    return response()->json($list);
  }

  // public function getCategorias()
  // {
  //   $categorias = [];
  //   $categorias = Categoria::all();
  //   foreach ($categorias as $categoria) {
  //     $categoria->afiliados = 0;
  //     $categoria->concluidas = 0;
  //     $categoria->canceladas = 0;
  //     foreach ($categoria->afiliadoCategorias as $afiliadoCategoria) {
  //       if (optional($afiliadoCategoria->afiliado)->status == 'ativo') {
  //         $categoria->afiliados++;
  //       };
  //     }
  //     foreach ($categoria->orcamentos as $orcamento) {
  //       if ($orcamento->status == StatusOrcamento::$FINALIZADO) {
  //         $categoria->concluidas++;
  //       }
  //       if (
  //         $orcamento->status == StatusOrcamento::$CANCELADO_PELO_ADMIN ||
  //         $orcamento->status == StatusOrcamento::$CANCELADO_PELO_FRANQUEADO ||
  //         $orcamento->status == StatusOrcamento::$CANCELADO_PELO_SINDICO ||
  //         $orcamento->status == StatusOrcamento::$CANCELADO_PELO_AFILIADO
  //       ) {
  //         $categoria->canceladas++;
  //       }
  //     }
  //   }
  //   $list = view('admin.dashboard.categorias')->with('categorias', $categorias)->render();
  //   return response()->json($list);
  // }

  public function getCategorias()
  {
    $categorias = Categoria::with(['afiliadoCategorias.afiliado', 'orcamentos'])
      ->get();

    foreach ($categorias as $categoria) {
      $categoria->afiliados = $categoria->afiliadoCategorias
        ->filter(function ($afiliadoCategoria) {
          return optional($afiliadoCategoria->afiliado)->status == 'ativo';
        })
        ->count();

      $categoria->concluidas = $categoria->orcamentos
        ->filter(function ($orcamento) {
          return $orcamento->status == StatusOrcamento::$FINALIZADO;
        })
        ->count();

      $categoria->canceladas = $categoria->orcamentos
        ->whereIn('status', [
          StatusOrcamento::$CANCELADO_PELO_ADMIN,
          StatusOrcamento::$CANCELADO_PELO_FRANQUEADO,
          StatusOrcamento::$CANCELADO_PELO_SINDICO,
          StatusOrcamento::$CANCELADO_PELO_AFILIADO
        ])
        ->count();
    }

    $list = view('admin.dashboard.categorias')
      ->with('categorias', $categorias)
      ->render();

    return response()->json($list);
  }



  /**
   * Pega as informações necessárias para preencher a index do admin
   **/
  private function populateAdminIndex()
  {

    $dbg = function($msg) { file_put_contents(storage_path('logs/dash_debug.txt'), date('H:i:s.') . substr(microtime(), 2, 3) . " $msg\n", FILE_APPEND); };
    $dbg('populateAdminIndex start');
    $afiliados_inativos = "--";
    // [HUBBOX FIX] Calcula afiliados ativos para alimentar o card "Afiliados Ativos" no admin/inicio
    $afiliados_ativos = AfiliadoRegiao::join(
      'plano_assinatura_afiliado_regiao',
      'plano_assinatura_afiliado_regiao.id',
      '=',
      'afiliado_regiao.plano_assinatura_afiliado_regiao_id'
    )
      ->join('afiliado', 'afiliado.id', '=', 'afiliado_regiao.afiliado_id')
      ->join('usuario_app', 'usuario_app.id', '=', 'afiliado.usuario_app_id')
      ->where('plano_assinatura_afiliado_regiao.statusPlano', StatusPlano::$ATIVO)
      ->distinct('afiliado.id')
      ->count('afiliado.id');

    // [HUBBOX FIX] Calcula afiliados sem região para alimentar o card "Afiliados Sem Região" no admin/inicio
    $afiliadosSemRegiao = Afiliado::leftJoin('afiliado_regiao', 'afiliado_regiao.afiliado_id', '=', 'afiliado.id')
      ->whereNull('afiliado_regiao.id')
      ->distinct('afiliado.id')
      ->count('afiliado.id');

    $afiliados_pendentes = Afiliado::where('status', 'pendente')->count();

    $valor_planos_afiliados_ativos = AfiliadoRegiao::join('plano_assinatura_afiliado_regiao', 'plano_assinatura_afiliado_regiao.id', 'plano_assinatura_afiliado_regiao_id')
      ->join('afiliado', 'afiliado.id', 'afiliado_regiao.afiliado_id')
      ->distinct()->where('afiliado.status', 'ativo')->sum('plano_assinatura_afiliado_regiao.valor');

    $config = Configuracao::orderBy("id", "desc")->first();
    // [HUBBOX FIX] Garante objeto de configuração para evitar fallback silencioso na view de Modus Operandi
    if (!$config) {
      $config = new Configuracao();
      // [HUBBOX FIX] Assume modo produção quando ainda não existe registro em configuracao
      $config->modus_operandi = ModusOperandiStatus::$PRODUCAO;
    }


    $documentosPendentesShow = [];
    $totalDocPendentes = 0;
    $cartoesCnpjPendente = CartaoCnpj::where("status", "pendente")->orderBy("id", "desc")->get();
    foreach ($cartoesCnpjPendente as $docPendente) {
      $docPendente['tipo'] = "cartao-cnpj";
      $documentosPendentesShow[$docPendente->afiliado_id][] = $docPendente;
      $totalDocPendentes++;
    }



    $contratoSocialPendente = ContratoSocial::where("status", "pendente")->orderBy("id", "desc")->get();
    foreach ($contratoSocialPendente as $docPendente) {
      $docPendente['tipo'] = "contrato-social";
      $documentosPendentesShow[$docPendente->afiliado_id][] = $docPendente;
      $totalDocPendentes++;
    }


    $parceiros_ativos = Parceiro::where('status', 'ativo')->count();
    $parceiros_inativos = Parceiro::where('status', 'inativos')->count();
    $valor_planos_parceiros_ativos = Parceiro::join('plano_disponivel_franqueado', 'plano_disponivel_franqueado.id', 'parceiros.plano_id')->where('parceiros.status', 'ativo')->sum('plano_disponivel_franqueado.valor');
    // $valor_planos_parceiros_ativos = Formatacao::prefixoSufixo($valor_planos_afiliados_ativos);


    $afiliados = Afiliado::orderBy("id", "desc")->limit(10)->get();
    // [HUBBOX FIX] Injetar síndicos ausentes no dashboard
    $sindicos = Sindico::orderBy("id", "desc")->limit(10)->get();

    // [HUBBOX FIX] Calcula as métricas de solicitações para evitar fallback "0" na view admin/index
    $solicitacoesEmAndamento = 0;
    // [HUBBOX FIX] Calcula as métricas de solicitações canceladas para alimentar os cards do dashboard
    $solicitacoesCanceladas = 0;
    // [HUBBOX FIX] Popula a tabela "Solicitações em andamento" diretamente na carga inicial do dashboard
    $solicitacoes = [];
    // [HUBBOX FIX] Carrega as solicitações com status para classificar em andamento e canceladas
    $solicitacoesLinha = Orcamento::whereNotNull('status')
      ->with([
        'Condominio'              => fn($q) => $q->withTrashed(),
        'Condominio.Sindico'      => fn($q) => $q->withTrashed(),
        'Condominio.Bairro.cidade.estado',
        'Regiao'                  => fn($q) => $q->withTrashed(),
        'Categoria'               => fn($q) => $q->withTrashed(),
        'afiliado',
      ])
      ->select('orcamento.*')
      ->orderBy('orcamento.id', 'desc')
      ->limit(200)
      ->get();

    foreach ($solicitacoesLinha as $solicitacaoLinha) {
      if (in_array($solicitacaoLinha->status, [
        StatusOrcamento::$ANALISANDO_CANDIDATOS,
        StatusOrcamento::$ANALISANDO_ORCAMENTOS,
        StatusOrcamento::$AGUARDANDO_CONTRATO,
        StatusOrcamento::$EM_EXECUCAO,
        StatusOrcamento::$CONTRATO_ASSINADO
      ])) {
        $solicitacaoLinha->bairroFK = optional($solicitacaoLinha->Condominio)->Bairro ?? null;
        $solicitacoes[] = $solicitacaoLinha;
        $solicitacoesEmAndamento++;
      }

      if (in_array($solicitacaoLinha->status, [
        StatusOrcamento::$CANCELADO_PELO_ADMIN,
        StatusOrcamento::$CANCELADO_PELO_FRANQUEADO,
        StatusOrcamento::$CANCELADO_PELO_SINDICO,
        StatusOrcamento::$CANCELADO_PELO_AFILIADO
      ])) {
        $solicitacoesCanceladas++;
      }
    }

    $solicitacoesDisputa = Vistoria::where("status", StatusVistoria::$PENDENTE)->count();
    $dbg('after solicitacoes+vistoria');
    // END Solicitações

    $categoriasPendentesShow = AfiliadoCategoria::where("status", "pendente")
      ->with(['Afiliado', 'Categoria'])
      ->get()
      ->filter(fn($c) => $c->Afiliado && $c->Categoria)
      ->values()
      ->all();

    $valor_planos_afiliados_ativos = Formatacao::prefixoSufixo($valor_planos_afiliados_ativos);
    $dbg('after categorias pendentes');
    // Popular graficos
    $franqueados = Franqueado::all();
    $dbg('after franqueados::all');
    $chartQA = $this->populateChartQA($franqueados);
    $dbg('after chartQA');
    $condominios = Condominio::all();
    $dbg('after condominios::all');
    $chartQS = $this->populateChartQS($condominios);
    $dbg('after chartQS');
    $chartQSo = $this->populateChartQSo($franqueados);
    $dbg('after chartQSo');

    // END Popular graficos

    // Popular table regioes
    $dbg('starting franqueadoRegioes');
    $franqueadoRegioes = FranqueadoRegiao::where('status', 'ativo')->get();
    $regiaoIds = $franqueadoRegioes->pluck('regiao_id')->unique()->values();
    $allOrcamentos = Orcamento::whereIn('regiao_id', $regiaoIds)
      ->select('id', 'regiao_id', 'status')
      ->get()
      ->groupBy('regiao_id');
    foreach ($franqueadoRegioes as $franqueadoRegiao) {
      $franqueadoRegiao->afiliados = 0;
      $franqueadoRegiao->concluidas = 0;
      $franqueadoRegiao->canceladas = 0;
      $orcamentos = $allOrcamentos->get($franqueadoRegiao->regiao_id, collect());
      $franqueadoRegiao->orcamentos = $orcamentos;
      foreach ($orcamentos as $orcamento) {
        if ($orcamento->status == StatusOrcamento::$FINALIZADO) $franqueadoRegiao->concluidas++;
        if (in_array($orcamento->status, [
          StatusOrcamento::$CANCELADO_PELO_ADMIN,
          StatusOrcamento::$CANCELADO_PELO_FRANQUEADO,
          StatusOrcamento::$CANCELADO_PELO_SINDICO,
          StatusOrcamento::$CANCELADO_PELO_AFILIADO
        ])) $franqueadoRegiao->canceladas++;
      }
    }
    $dbg('after franqueadoRegioes orcamentos loop');
    // END Popular table regioes

    $dbg('starting view render');
    // Popular table vistorias
    /*$vistoriasResource = Vistoria::get();


    $vistorias = [];
    foreach ($vistoriasResource as $v) {
      $o = Orcamento::where("id", $v->orcamento_id)->first(['regiao_id']);
      if ($o && ($v->status == StatusVistoria::$EM_ANDAMENTO || $v->status == StatusVistoria::$PENDENTE)) {
        if ($this->url == "admin_franqueado") {
          $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $o->regiao_id)->where("franqueado_id", $this->user_franqueado->id)->where("status", "ativo")->orderBy("id", "desc")->first();
          if ($franqueadoRegiao) {
            $vistorias[] = $v;
          }
        } else {
          $vistorias[] = $v;
        }
      }
    }
    // END Popular table vistorias
    print_r('<br />');
    print_r(date("Y-m-d H:i:s"));
    dd($vistorias);*/
    $dbg('calling return view');
    return view(
      $this->url . '.index',
      compact(
        'config',
        'solicitacoesEmAndamento',
        'solicitacoesCanceladas',
        'solicitacoesDisputa',
        'totalDocPendentes',
        'documentosPendentesShow',
        'categoriasPendentesShow',
        'franqueados',
        'afiliados_inativos',
        'afiliados_ativos',
        'afiliadosSemRegiao',
        'afiliados_pendentes',
        'parceiros_ativos',
        'parceiros_inativos',
        'valor_planos_afiliados_ativos',
        'valor_planos_parceiros_ativos',
        'solicitacoes',
        'afiliados',
        'sindicos', // [HUBBOX FIX] Variável injetada na view para popular a tabela de Últimos Síndicos
        'chartQA',
        'chartQS',
        'chartQSo',
        'franqueadoRegioes',
        // 'vistorias'
      )
    );
  }


  /**
   * Pega as informações necessárias para preencher o gráfico de
   *  quantidade de afiliados por franqueado da index
   * @param /App/Models/Franqueado
   * @return Object
   **/
  private function populateChartQA($franqueados)
  {
    $chartQA = ["data" => [], "labels" => [], "colors" => '#727cf5', "min" => 0, "max" => 0];

    $franqueados->load('franqueadoRegiao');
    $regioes = [];
    foreach ($franqueados as $franqueado) {
      foreach ($franqueado->franqueadoRegiao as $franqueadoRegiao) {
        if ($franqueadoRegiao->status == 'ativo') {
          if (!in_array($franqueado->nome, $chartQA['labels'])) {
            $chartQA['labels'][] = $franqueado->nome;
          }
          if (!in_array($franqueadoRegiao->regiao_id, array_column($regioes, 0))) {
            $regioes[] = [$franqueadoRegiao->regiao_id, $franqueado->nome];
          }
        }
      }
    }

    if (empty($regioes)) return $chartQA;

    $regiaoIds = array_column($regioes, 0);
    $counts = AfiliadoRegiao::join('plano_assinatura_afiliado_regiao', 'plano_assinatura_afiliado_regiao.id', '=', 'afiliado_regiao.plano_assinatura_afiliado_regiao_id')
      ->whereIn('afiliado_regiao.regiao_id', $regiaoIds)
      ->where('plano_assinatura_afiliado_regiao.statusPlano', 1)
      ->selectRaw('afiliado_regiao.regiao_id, count(*) as total')
      ->groupBy('afiliado_regiao.regiao_id')
      ->pluck('total', 'regiao_id');

    $data = [];
    foreach ($regioes as $regiaoDados) {
      $num = $counts->get($regiaoDados[0], 0);
      $data[$regiaoDados[1]] = ($data[$regiaoDados[1]] ?? 0) + $num;
    }
    foreach ($data as $d) {
      if ($d > $chartQA['max']) $chartQA['max'] = $d;
      $chartQA['data'][] = $d;
    }
    return $chartQA;
  }
  /**
   * Pega as informações necessárias para preencher o gráfico de
   *  quantidade de sindicos por franqueado da index
   * @param /App/Models/Condominio
   * @return Object
   **/
  private function populateChartQS($condominios)
  {
    // Replaced N+1 (getRegiao per condominio) with a bairro-based join approach
    $chartQS = ["data" => [], "labels" => [], "colors" => '#727cf5', "min" => 0, "max" => 0];

    $franqueadoRegioes = FranqueadoRegiao::where('status', 'ativo')->with('Franqueado')->get()->keyBy('regiao_id');
    $condominios->load('Bairro', 'Sindico');

    $sindicosFranqueado = [];
    foreach ($condominios as $condominio) {
      $regiao_id = optional($condominio->Bairro)->regiao_id;
      if (!$regiao_id) continue;
      $franqueadoRegiao = $franqueadoRegioes->get($regiao_id);
      if (!$franqueadoRegiao || !$franqueadoRegiao->Franqueado) continue;
      $nome = $franqueadoRegiao->Franqueado->nome;
      if (!isset($sindicosFranqueado[$nome])) $sindicosFranqueado[$nome] = [];
      $sid = optional($condominio->Sindico)->id;
      if ($sid && !in_array($sid, $sindicosFranqueado[$nome])) {
        $sindicosFranqueado[$nome][] = $sid;
      }
      if (!in_array($nome, $chartQS['labels'])) $chartQS['labels'][] = $nome;
    }
    foreach ($sindicosFranqueado as $sindicos) {
      $n = count($sindicos);
      $chartQS['data'][] = $n;
      if ($n > $chartQS['max']) $chartQS['max'] = $n;
    }
    return $chartQS;
  }
  /**
   * Pega as informações necessárias para preencher o gráfico de
   *  quantidade de solicitações/orcamentos por franqueado da index
   * @param /App/Models/Franqueado
   * @return Object
   **/
  private function populateChartQSo($franqueados)
  {
    $chartQSo = ["data" => [], "labels" => [], "colors" => '#727cf5', "min" => 0, "max" => 0];

    $franqueados->load('franqueadoRegiao');
    $regioes = [];
    foreach ($franqueados as $franqueado) {
      foreach ($franqueado->franqueadoRegiao as $franqueadoRegiao) {
        if ($franqueadoRegiao->status == 'ativo') {
          if (!in_array($franqueado->nome, $chartQSo['labels'])) {
            $chartQSo['labels'][] = $franqueado->nome;
          }
          if (!in_array($franqueadoRegiao->regiao_id, array_column($regioes, 0))) {
            $regioes[] = [$franqueadoRegiao->regiao_id, $franqueado->nome];
          }
        }
      }
    }

    if (empty($regioes)) return $chartQSo;

    $regiaoIds = array_column($regioes, 0);
    $counts = Orcamento::whereIn('regiao_id', $regiaoIds)
      ->where('status', StatusOrcamento::$FINALIZADO)
      ->selectRaw('regiao_id, count(*) as total')
      ->groupBy('regiao_id')
      ->pluck('total', 'regiao_id');

    $data = [];
    foreach ($regioes as $regiaoDados) {
      $num = $counts->get($regiaoDados[0], 0);
      $data[$regiaoDados[1]] = ($data[$regiaoDados[1]] ?? 0) + $num;
    }
    foreach ($data as $d) {
      if ($d > $chartQSo['max']) $chartQSo['max'] = $d;
      $chartQSo['data'][] = $d;
    }
    return $chartQSo;
  }
  /**
   * Pega as informações necessárias para preencher a pagina de pendências do franqueado
   **/
  private function getPendenciasFranqueado()
  {
    $categoriasPendentesShow = [];
    $categoriasPendentes = AfiliadoCategoria::where("status", "pendente")->orderBy("id", "desc")->get();
    foreach ($categoriasPendentes as $categoriaPendente) {
      $regioesAfiliado = AfiliadoRegiao::where("afiliado_id", $categoriaPendente->afiliado_id)->get();
      foreach ($regioesAfiliado as $regiaoAfiliado) {
        $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $regiaoAfiliado->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
        if ($franqueadoRegiao->franqueado_id == $this->user_franqueado->id) {
          $categoriasPendentesShow[$categoriaPendente->id] = $categoriaPendente;
        }
      }
    }

    $documentosPendentesShow = [];
    $totalDocPendentes = 0;
    $cartoesCnpjPendente = CartaoCnpj::where("status", "pendente")->orderBy("id", "desc")->get();
    foreach ($cartoesCnpjPendente as $docPendente) {
      $docPendente['tipo'] = "cartao-cnpj";
      $documentosPendentesShow[$docPendente->afiliado_id][] = $docPendente;
      $totalDocPendentes++;
    }

    $contratoSocialPendente = ContratoSocial::where("status", "pendente")->orderBy("id", "desc")->get();
    foreach ($contratoSocialPendente as $docPendente) {
      $docPendente['tipo'] = "contrato-social";
      $documentosPendentesShow[$docPendente->afiliado_id][] = $docPendente;
      $totalDocPendentes++;
    }



    $afiliadosAll = Afiliado::join('afiliado_regiao', 'afiliado_regiao.afiliado_id', 'afiliado.id')
      ->whereIn('afiliado_regiao.regiao_id', function ($query) {
        $query->select('regiao_id')
          ->from('franqueado_regiao')
          ->where('franqueado_regiao.franqueado_id', Auth::guard('franqueados')->user()->id);
      })->distinct()->select('afiliado.*')->orderBy("afiliado.id", "desc")->get();

    $afiliados_sem_contrato = 0;
    $documentosPendentesShow = [];
    $totalDocPendentes = 0;
    $afiliados_inativos = 0;
    $afiliados_inativos_cont = [];

    foreach ($afiliadosAll as $afiliadoLinha) {
      $afiliadoRegiaoAuxiliarLista = AfiliadoRegiao::where("afiliado_id", $afiliadoLinha->id)->get();

      foreach ($afiliadoRegiaoAuxiliarLista as $afiliadoRegiaoAuxiliar) {
        if ($afiliadoRegiaoAuxiliar) {
          $contrato = PlanoAssinaturaAfiliadoRegiao::where("id", $afiliadoRegiaoAuxiliar->plano_assinatura_afiliado_regiao_id)->where("statusPlano", 1)->where("arquivo_original", null)->first();
          if ($contrato) {
            $afiliados_sem_contrato++;
          }

          $contrato2 = PlanoAssinaturaAfiliadoRegiao::where("id", $afiliadoRegiaoAuxiliar->plano_assinatura_afiliado_regiao_id)->where("statusPlano", "in", [StatusPlano::$PENDENTE])->first();
          if ($contrato2) {
            $afiliados_inativos_cont[$afiliadoLinha->id] = $contrato2;
          }
        }
      }

      $afiliados_inativos = count($afiliados_inativos_cont);



      $cartoesCnpjPendente = CartaoCnpj::where("afiliado_id", $afiliadoLinha->id)->where("status", "pendente")->orderBy("id", "desc")->get();
      foreach ($cartoesCnpjPendente as $docPendente) {
        $docPendente['tipo'] = "cartao-cnpj";
        $documentosPendentesShow[$docPendente->afiliado_id][] = $docPendente;
        $totalDocPendentes++;
      }

      $contratoSocialPendente = ContratoSocial::where("afiliado_id", $afiliadoLinha->id)->where("status", "pendente")->orderBy("id", "desc")->get();
      foreach ($contratoSocialPendente as $docPendente) {
        $docPendente['tipo'] = "contrato-social";
        $documentosPendentesShow[$docPendente->afiliado_id][] = $docPendente;
        $totalDocPendentes++;
      }
    }

    $vistorias = Vistoria::whereIn('status', [StatusVistoria::$EM_ANDAMENTO, StatusVistoria::$PENDENTE])->get();
    $vistoriasResource = Vistoria::get();
    $vistorias = [];
    foreach ($vistoriasResource as $v) {
      $o = Orcamento::where("id", $v->orcamento_id)->first();
      if ($o && ($v->status == StatusVistoria::$EM_ANDAMENTO || $v->status == StatusVistoria::$PENDENTE)) {
        if ($this->url == "admin_franqueado") {
          $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $o->regiao_id)->where("franqueado_id", $this->user_franqueado->id)->where("status", "ativo")->orderBy("id", "desc")->first();
          if ($franqueadoRegiao) {
            $vistorias[] = $v;
          }
        } else {
          $vistorias[] = $v;
        }
      }
    }


    return view($this->url . '.pendencias', compact('vistorias', 'categoriasPendentesShow', 'documentosPendentesShow', 'totalDocPendentes'));
  }
  /**
   * Pega as informações necessárias para preencher a pagina de pendências do admin
   **/
  private function getPendenciasAdmin()
  {
    $afiliados_inativos = Afiliado::where('status', 'inativo')->count();
    $afiliados_pendentes = Afiliado::where('status', 'pendente')->count();
    $valor_planos_afiliados_ativos = AfiliadoRegiao::join('plano_assinatura_afiliado_regiao', 'plano_assinatura_afiliado_regiao.id', 'plano_assinatura_afiliado_regiao_id')
      ->join('afiliado', 'afiliado.id', 'afiliado_regiao.afiliado_id')
      ->distinct()->where('afiliado.status', 'ativo')->sum('plano_assinatura_afiliado_regiao.valor');

    $documentosPendentesShow = [];
    $totalDocPendentes = 0;
    $cartoesCnpjPendente = CartaoCnpj::where("status", "pendente")->orderBy("id", "desc")->get();
    foreach ($cartoesCnpjPendente as $docPendente) {
      $docPendente['tipo'] = "cartao-cnpj";
      $afiliado = Afiliado::where("id", $docPendente->afiliado_id)->first();
      if ($afiliado) {
        $documentosPendentesShow[$docPendente->afiliado_id][] = $docPendente;
        $totalDocPendentes++;
      }
    }

    $contratoSocialPendente = ContratoSocial::where("status", "pendente")->orderBy("id", "desc")->get();
    foreach ($contratoSocialPendente as $docPendente) {
      $docPendente['tipo'] = "contrato-social";
      $afiliado = Afiliado::where("id", $docPendente->afiliado_id)->first();
      if ($afiliado) {
        $documentosPendentesShow[$docPendente->afiliado_id][] = $docPendente;
        $totalDocPendentes++;
      }
    }
    $categoriasPendentesShowAux = AfiliadoCategoria::where("status", "pendente")->get();
    $categoriasPendentesShow = [];
    foreach ($categoriasPendentesShowAux as $categoriaPendente) {
      $afiliado = Afiliado::where("id", $categoriaPendente->afiliado_id)->first();
      $categoria = Categoria::where("id", $categoriaPendente->categoria_id)->first();
      if ($afiliado && $categoria) {
        $categoriasPendentesShow[] = $categoriaPendente;
      }
    }

    // Popular table vistorias
    $vistorias = Vistoria::whereIn('status', [StatusVistoria::$EM_ANDAMENTO, StatusVistoria::$PENDENTE])->get();
    $vistoriasResource = Vistoria::get();

    $vistorias = [];
    foreach ($vistoriasResource as $v) {
      $o = Orcamento::where("id", $v->orcamento_id)->first();
      if ($o && ($v->status == StatusVistoria::$EM_ANDAMENTO || $v->status == StatusVistoria::$PENDENTE)) {
        if ($this->url == "admin_franqueado") {
          $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $o->regiao_id)->where("franqueado_id", $this->user_franqueado->id)->where("status", "ativo")->orderBy("id", "desc")->first();
          if ($franqueadoRegiao) {
            $vistorias[] = $v;
          }
        } else {
          $vistorias[] = $v;
        }
      }
    }

    return view($this->url . '.pendencias', compact('vistorias', 'categoriasPendentesShow', 'documentosPendentesShow', 'totalDocPendentes'));
  }

  public function gerarExcel()
  {
    return Excel::download(new AfiliadosExport(), 'afiliados.xlsx');
  }
}
