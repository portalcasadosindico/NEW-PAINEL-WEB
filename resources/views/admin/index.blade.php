<?php
    use App\Models\AfiliadoFranqueadoAsaas;
    use App\Models\ResponsavelAfiliado;
    use App\Uteis\StatusOrcamento; 
    use App\Uteis\StatusVistoria; 
    use App\Uteis\StatusPlano;
    use App\Uteis\Formatacao; 
?>

@php
    // Garantir que todas as variáveis existam com valores padrão
    $config = $config ?? (object)['modus_operandi' => 'producao'];
    $afiliados_ativos = $afiliados_ativos ?? 0;
    $afiliadosSemRegiao = $afiliadosSemRegiao ?? 0;
    $solicitacoesEmAndamento = $solicitacoesEmAndamento ?? 0;
    $solicitacoesCanceladas = $solicitacoesCanceladas ?? 0;
    $solicitacoesDisputa = $solicitacoesDisputa ?? 0;
    $franqueados = $franqueados ?? [];
    $solicitacoes = $solicitacoes ?? [];
    $categorias = $categorias ?? [];
    $franqueadoRegioes = $franqueadoRegioes ?? [];
    $chartQA = $chartQA ?? ['data' => [], 'labels' => [], 'colors' => [], 'min' => 0, 'max' => 10];
    $chartQS = $chartQS ?? ['data' => [], 'labels' => [], 'colors' => [], 'min' => 0, 'max' => 10];
    $chartQSo = $chartQSo ?? ['data' => [], 'labels' => [], 'colors' => [], 'min' => 0, 'max' => 10];
@endphp

@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Bem vindo ao painel administrativo</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <div class="input-group date datepicker dashboard-date mr-2 mb-2 mb-md-0 d-md-none d-xl-flex" id="dashboardDate">
            <span class="input-group-addon bg-transparent"><i data-feather="calendar" class=" text-primary"></i></span>
            <input type="text" class="form-control">
        </div>
     </div>
</div>

<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow">
            <div class="col-md-12 grid-margin stretch-card">
                @if($config->modus_operandi=="producao")
                    <div class="card border-success">
                        <div class="card-body">
                            <div class="text-center">
                                <h6 class="card-title mb-0 text-center">Modus operandi</h6>
                                <a href="javascript:void(0)" onclick="alterarModus()">Alterar Modus Operandi do Sistema</a>
                            </div>
                            <div class="row text-center">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mt-2">O sistema está em modo PRODUÇÃO</h3>
                                    <button type="button"  class="btn-question mb-4" data-trigger="focus" data-toggle="popover" title="Modo Produção" data-content="Isso significa que o sistema está realizando assinaturas de planos com ASAAS e assinaturas de contratos via Autentique reais."><i class="fa fa-question"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($config->modus_operandi=="debug")
                    <div class="card border-warning">
                        <div class="card-body">
                            <div class="text-center">
                                <h6 class="card-title mb-0 text-center">Modus operandi</h6>
                                <a href="javascript:void(0)" onclick="alterarModus()">Alterar Modus Operandi do Sistema</a>
                            </div>
                            <div class="row text-center">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mt-2">O sistema está em modo TESTE</h3>
                                    <button type="button"  class="btn-question mb-4" data-trigger="focus" data-toggle="popover" title="Modo Teste" data-content="Isso significa que o sistema está realizando assinaturas de planos com ASAAS e assinaturas de contratos via Autentique fictícios."><i class="fa fa-question"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($config->modus_operandi=="manutencao")
                    <div class="card border-danger">
                        <div class="card-body">
                            <div class="text-center">
                                <h6 class="card-title mb-0 text-center">Modus operandi</h6>
                                <a href="javascript:void(0)" onclick="alterarModus()">Alterar Modus Operandi do Sistema</a>
                            </div>
                            <div class="row text-center">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mt-2">O sistema está em modo MANUTENÇÃO</h3>
                                    <button type="button"  class="btn-question mb-4" data-trigger="focus" data-toggle="popover" title="Modo Teste" data-content="Isso significa que o franqueado e usuários ao entrar no aplicativo ou sistema, irão encontrar a mensagem 'Nosso sistema está em manutenção. Não se preocupe, nosso sistema voltará em breve'."><i class="fa fa-question"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="text-center">
                            <h6 class="card-title mb-0 text-center">Afiliados Ativos</h6>
                        </div>
                        <div class="row text-center">
                            <div class="col-12 col-md-12 col-xl-12">
                                <h3 class="mt-2">{{ $afiliados_ativos ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="text-center">
                            <h6 class="card-title mb-0 text-center">Afiliados sem região</h6>
                        </div>
                        <div class="row text-center">
                            <div class="col-12 col-md-12 col-xl-12">
                                <h3 class="mt-2">{{ $afiliadosSemRegiao ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 grid-margin stretch-card">
                

                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card border-success">
                        <div class="card-body">
                            <div class="text-center">
                                <h6 class="card-title mb-0">Solicitações em andamento</h6>
                                <button type="button"  class="btn-question mb-4" data-trigger="focus" data-toggle="popover" title="Solicitações em andamento" data-content="São solicitações que estão em processo de análise de candidatos, orçamento e em execução."><i class="fa fa-question"></i></button>
                            </div>
                            <div class="row text-center">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mb-2">{{ $solicitacoesEmAndamento ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card border-danger">
                        <div class="card-body">
                            <div class="text-center">
                                <h6 class="card-title mb-0">Solicitações canceladas</h6>
                                <button type="button"  class="btn-question mb-4" data-trigger="focus" data-toggle="popover" title="Solicitações canceladas" data-content="São solicitações que foram cancelados pelo super admin, franqueado, afiliado ou síndico."><i class="fa fa-question"></i></button>
                            </div>
                            <div class="row text-center">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mb-2">{{ $solicitacoesCanceladas ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card border-success">
                        <div class="card-body">
                            <div class="text-center">
                                <h6 class="card-title mb-0">Solicitações e vistoria</h6>
                                <button type="button"  class="btn-question mb-4" data-trigger="focus" data-toggle="popover" title="Solicitações de vistoria" data-content="Vistorias em aberto."><i class="fa fa-question"></i></button>
                            </div>
                            <div class="row text-center">
                                <div class="col-12 col-md-12 col-xl-12">
                                    <h3 class="mb-2">{{ $solicitacoesDisputa ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div> <!-- row -->

<div class="row">
    <div class="col-lg-12 col-xl-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0">Franqueados</h6>
                </div>

                @if(count($franqueados) == 0)
                <div class="panel-body text-center">
                    <h4>Nenhum franqueado listado</h4>
                </div>
                @else
                <div class="panel-body panel-body-with-table">
                    <div class="table-responsive">
                        <table class="table table-striped ">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Contato</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($franqueados as $franqueado)
                                <td>
                                    {{ $franqueado->nome }}
                                    <div>
                                        <form method="POST" action="{!! route('admin_franqueado.autoLogin',['franqueado_id' => $franqueado->id] ) !!}" accept-charset="UTF-8">
                                            <input name="_method" value="POST" type="hidden">
                                            {{ csrf_field() }}
                                            <button class="btn btn-primary">Logar</button>
                                        </form>
                                    </div>
                                </td>
                                <td>
                                    <label class="badge badge-default mail-link" style="font-size: 14px;">{{ $franqueado->email }}</label>
                                    <br>
                                    <label class="badge badge-default whats-link" style="font-size: 14px;" target="_blank">{{ $franqueado->telefone_responsavel }}</label>
                                </td>
                                <td align="right">

                                    <form method="POST" action="{!! route('franqueados.destroy', $franqueado->id) !!}" accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}

                                        <div class="btn-group btn-group-xs pull-right" role="group">
                                            <a href="{{ route('franqueados.show', $franqueado->id ) }}" class="btn btn-info" title="Ver franqueado">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('franqueados.edit', $franqueado->id ) }}" class="btn btn-primary" title="Editar franqueado">
                                                <i class="fa fa-pencil"></i>
                                            </a>

                                            <button type="submit" class="btn btn-danger" title="Remover franqueado" onclick="return confirm('Deseja realmete excluir o franqueado {{$franqueado->nome}}?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>

                                    </form>

                                </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div> <!-- row -->

<div class="row">
    <div class="col-lg-12 col-xl-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0">Solicitações em andamento</h6>
                </div>
                <button class="btn btn-info mb-3" onclick="filtroSolicitacoes('aguardando contrato')">Ver apenas Aguardando Contrato</button>

                @if(!isset($solicitacoes) || count($solicitacoes) == 0)
                <div class="panel-body text-center">
                    <i class="fa fa-tint fa-5x"></i>
                    <h4>Nenhuma solicitação em andamento</h4>
                </div>
                @else
                <div class="panel-body panel-body-with-table" style="max-height: 600px; overflow: auto;">
                    <div class="table-responsive">
                        <table class="table table-striped dataTableNoOrder">
                            <thead>
                                <tr>
                                    <th width="200">#</th>
                                    <th>Local</th>
                                    <th>Status</th>
                                    <th>Contrato</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($solicitacoes as $solicitacao)
                                <td>
                                    <h5>#{{ $solicitacao->id }}</h5>
                                    Data: <b><?php echo Formatacao::data($solicitacao->data_cadastro) ?></b><br><br>
                                    Síndico: <b>{{optional(optional($solicitacao->Condominio)->Sindico)->nome}}</b><br>
                                    Afiliado: <b>{{$solicitacao->afiliado ? $solicitacao->afiliado->nome_fantasia : 'Nenhum afiliado'}}</b>
                                </td>
                                <td>
                                    @if($solicitacao->regiao_id)
                                        <label class="badge badge-success">Região: {{ optional($solicitacao->Regiao)->nome }}</label>
                                    @else
                                        <label class="badge badge-danger">Sem região</label>
                                        <p>
                                            Não foi encontrado uma região para a solicitação.<br>
                                            Isso ocorreu porque o endereço do condomínio não foi encontrado em nossas regiões, no momento do cadastro da solicitação.<br>
                                            <br><h6>Solução</h6>
                                            1) Peça para o síndico revisar o endereço do condomínio, ou vá até o Perfil do síndico pelo admin e realize a verificação do endereço.<br><br>
                                            2) Você precisa Editar a solicitação que está sem região e simplesmente clicar em Salvar. O sistema irá revisar a região desta soicitação com base no endereço atual do condomínio.
                                        </p>
                                    @endif
                                    <br><br>
                                    Categoria: <b>{{ optional($solicitacao->Categoria)->nome }}</b><br><br>
                                    Condominio: <b>{{ optional($solicitacao->Condominio)->nome }}</b><br>
                                    <br>
                                    {{ optional($solicitacao->Condominio)->endereco.". ".optional($solicitacao->bairroFK)->nome.", ".optional(optional($solicitacao->bairroFK)->cidade)->nome."/".optional(optional(optional($solicitacao->bairroFK)->cidade)->estado)->uf }}
                                </td>
                                <td>
                                    {{ $solicitacao->status ? StatusOrcamento::getLabel( $solicitacao->status) : 'Sem status' }}
                                </td>

                                <td>
                                    <label clas="badge badge-default"><b>{{$solicitacao->titulo_contrato}}</b></label><br>
                                    @if($solicitacao->formato_contrato_atual==2)
                                        <a href="{{$solicitacao->contrato_assinado ? $solicitacao->contrato_assinado : "https://admin2.casadosindico.srv.br/storage/".$solicitacao->contrato}}"
                                        class="btn btn-success" target="_blank">Ver contrato</a>
                                    @elseif($solicitacao->formato_contrato_atual==1)
                                        <a href="{{$solicitacao->contrato_original ? $solicitacao->contrato_original : "https://admin2.casadosindico.srv.br/storage/".$solicitacao->contrato}}"
                                            class="btn btn-secondary" target="_blank">Ver contrato</a>
                                        <a href="{{$solicitacao->contrato_assinado ? $solicitacao->contrato_assinado : "https://admin2.casadosindico.srv.br/storage/".$solicitacao->contrato}}" class="btn btn-success"
                                            target="_blank">Ver contrato assinado</a>
                                    @else
                                        <a class="btn btn-danger"
                                            href="{{ route('admin_franqueado.orcamentos.edit', $solicitacao->id ) }}#upload">Upload
                                            do
                                            contrato</a>
                                    @endif

                                </td>

                                <td align="right">

                                    <form method="POST" action="{!! route('admin.orcamentos.destroy', $solicitacao->id) !!}" accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}

                                        <div class="btn-group btn-group-xs pull-right" role="group">
                                            <a href="{{ route('admin.orcamentos.edit', $solicitacao->id ) }}" class="btn btn-primary" title="Editar solicitacao">
                                                <i class="fa fa-pencil"></i>
                                            </a>

                                            <button type="submit" class="btn btn-danger" title="Remover solicitacao" onclick="return confirm('Deseja realmete excluir o solicitacao {{$solicitacao->id}}?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>

                                    </form>

                                </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

</div> <!-- row -->

<div class="row">
    <!-- categorias table-->
    <div class="col-lg-6 col-xl-6 stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0">Categorias</h6>
                </div>

                @if(!isset($categorias) || count($categorias) == 0)
                <div class="panel-body text-center">
                    <i class="fa fa-tint fa-5x"></i>
                    <h4>Nenhuma categoria</h4>
                </div>
                @else
                <div class="panel-body panel-body-with-table" style="max-height: 600px; overflow: auto;">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="200">Categoria</th>
                                    <th>Solicitações</th>
                                    <th>Afiliados</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categorias as $categoria)
                                <td>
                                    <h5>{{ $categoria->nome }}</h5>
                                </td>
                                <td>
                                    <label class="badge badge-success"> Concluidas: {{$categoria->concluidas}}</label>
                                    <label class="badge badge-danger"> Canceladas: {{$categoria->canceladas}}</label>
                                    <label class="badge badge-info"> Total: {{sizeof($categoria->orcamentos)}}</label>
                                </td>
                                <td>
                                    {{$categoria->afiliados}}                                     
                                </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div> <!-- categorias table-->
    <!-- regioes table-->
        <div class="col-lg-6 col-xl-6 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Regiões franquias</h6>
                    </div>
    
                    @if(!isset($franqueadoRegioes) || count($franqueadoRegioes) == 0)
                    <div class="panel-body text-center">
                        <i class="fa fa-tint fa-5x"></i>
                        <h4>Nenhuma região</h4>
                    </div>
                    @else
                    <div class="panel-body panel-body-with-table" style="max-height: 600px; overflow: auto;">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="200">Região/Franqueado</th>
                                        <th>Solicitações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($franqueadoRegioes as $franqueadoRegiao)
                                    <td>
                                        Região: <b>{{ $franqueadoRegiao->regiao ? $franqueadoRegiao->regiao->nome : "SEM REGIÃO" }}</b><br>
                                        Franqueado: <b>{{ $franqueadoRegiao->franqueado ? $franqueadoRegiao->franqueado->nome : 'N/A' }}</b>
                                    </td>
                                    <td>
                                        <td>
                                            <label class="badge badge-success"> Concluidas: {{$franqueadoRegiao->concluidas}}</label>
                                            <label class="badge badge-danger"> Canceladas: {{$franqueadoRegiao->canceladas}}</label>
                                            <label class="badge badge-info"> Total: {{sizeof($franqueadoRegiao->orcamentos)}}</label>
                                        </td>
                                    </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div><!-- regioes table-->
</div> <!-- row -->



<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0">Quantidade de afiliados por franqueado.</h6>
                    <div class="dropdown mb-2">
                        <button class="btn p-0" type="button" id="dropdownMenuButton4" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="feather feather-more-horizontal icon-lg text-muted pb-3px">
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="19" cy="12" r="1"></circle>
                                <circle cx="5" cy="12" r="1"></circle>
                            </svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton4">
                            <a class="dropdown-item d-flex align-items-center" href="#"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-eye icon-sm mr-2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg> <span class="">View</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-edit-2 icon-sm mr-2">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg> <span class="">Edit</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-trash icon-sm mr-2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                </svg> <span class="">Delete</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-printer icon-sm mr-2">
                                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                    <path
                                        d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2">
                                    </path>
                                    <rect x="6" y="14" width="12" height="8"></rect>
                                </svg> <span class="">Print</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-download icon-sm mr-2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg> <span class="">Download</span></a>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-4">Quantidade de afiliados por franqueado.</p>
                <div class="monthly-sales-chart-wrapper">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="quantity-afiliados-chart" style="display: block; width: 648px; height: 270px;"
                        width="648" height="270" class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0">Quantidade de sindicos por franqueado.</h6>
                    <div class="dropdown mb-2">
                        <button class="btn p-0" type="button" id="dropdownMenuButton4" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="feather feather-more-horizontal icon-lg text-muted pb-3px">
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="19" cy="12" r="1"></circle>
                                <circle cx="5" cy="12" r="1"></circle>
                            </svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton4">
                            <a class="dropdown-item d-flex align-items-center" href="#"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-eye icon-sm mr-2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg> <span class="">View</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-edit-2 icon-sm mr-2">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg> <span class="">Edit</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-trash icon-sm mr-2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                </svg> <span class="">Delete</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-printer icon-sm mr-2">
                                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                    <path
                                        d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2">
                                    </path>
                                    <rect x="6" y="14" width="12" height="8"></rect>
                                </svg> <span class="">Print</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-download icon-sm mr-2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg> <span class="">Download</span></a>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-4">Quantidade de sindicos por franqueado.</p>
                <div class="monthly-sales-chart-wrapper">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="quantity-sindicos-chart" style="display: block; width: 648px; height: 270px;"
                        width="648" height="270" class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0">Quantidade de solicitações concluidas.</h6>
                    <div class="dropdown mb-2">
                        <button class="btn p-0" type="button" id="dropdownMenuButton4" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="feather feather-more-horizontal icon-lg text-muted pb-3px">
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="19" cy="12" r="1"></circle>
                                <circle cx="5" cy="12" r="1"></circle>
                            </svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton4">
                            <a class="dropdown-item d-flex align-items-center" href="#"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-eye icon-sm mr-2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg> <span class="">View</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-edit-2 icon-sm mr-2">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg> <span class="">Edit</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-trash icon-sm mr-2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                </svg> <span class="">Delete</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-printer icon-sm mr-2">
                                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                    <path
                                        d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2">
                                    </path>
                                    <rect x="6" y="14" width="12" height="8"></rect>
                                </svg> <span class="">Print</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="#"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-download icon-sm mr-2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg> <span class="">Download</span></a>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-4">Quantidade de solicitações concluidas.</p>
                <div class="monthly-sales-chart-wrapper">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="quantity-solicitacoes-chart" style="display: block; width: 648px; height: 270px;"
                        width="648" height="270" class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<form method="POST">{{ csrf_field() }}</form>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/progressbar-js/progressbar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/datepicker.js') }}"></script>

<script>
    function abrirMotivo(index){
        if($("#content-motivo-"+index).css("display")=="none"){
            $("#content-motivo-"+index).show();
        } else {
            $("#content-motivo-"+index).hide();
        }
    }

    function abrirMotivoDocPendente(index, tipo){
        if($("#content-motivo-doc-"+index+"-"+tipo).css("display")=="none"){
            $("#content-motivo-doc-"+index+"-"+tipo).show();
        } else {
            $("#content-motivo-doc-"+index+"-"+tipo).hide();
        }
    }

    function aprovarCategoria(afiliado_categoria_id, newStatus){

        if(newStatus=="aprovado"){
            var textoConfirm = "Você confirma a aprovação desta categoria?";
        } else if(newStatus=="reprovado"){
            var textoConfirm = "Você confirma a reprovação desta categoria?";
        }

        if(confirm(textoConfirm)){
            $(".btn-aprovar-"+afiliado_categoria_id).html("Aguarde...")
            $(".btn-aprovar-"+afiliado_categoria_id).addClass("disabled")
            $(".btn-reprovar-"+afiliado_categoria_id).html("Aguarde...")
            $(".btn-reprovar-"+afiliado_categoria_id).addClass("disabled")
            var motivo = $("#motivo-reprovado-"+afiliado_categoria_id).val()
            var _token = $('input[name="_token"]').val();
            $.getJSON({
                url: '<?php echo getenv("APP_URL"); ?>/admin/alterar_status_categoria_afiliado/'+afiliado_categoria_id,
                method: "POST",
                data: {
                    _token: _token,
                    status: newStatus,
                    motivo: motivo
                },
                success: function(data) {
                    if(data.res==true){
                        $("#linha-categoria-pendente-"+afiliado_categoria_id).remove();
                        contarQuantidaeCategoriasPendentes();
                    } else {
                        $(".btn-aprovar-"+afiliado_categoria_id).html("Aprovar")
                        $(".btn-aprovar-"+afiliado_categoria_id).removeClass("disabled")
                        $(".btn-reprovar-"+afiliado_categoria_id).html("Reprovar")
                        $(".btn-reprovar-"+afiliado_categoria_id).removeClass("disabled")
                        alert("Tente novamente.")
                    }
                },
                error: function() {
                    $(".btn-aprovar-"+afiliado_categoria_id).html("Aprovar")
                    $(".btn-aprovar-"+afiliado_categoria_id).removeClass("disabled")
                    $(".btn-reprovar-"+afiliado_categoria_id).html("Reprovar")
                    $(".btn-reprovar-"+afiliado_categoria_id).removeClass("disabled")
                    alert("Tente novamente.")
                }
            });  
        }

        
    }

    function aprovarDocPendente(documento_id, newStatus, tipo){

        if(newStatus=="aprovado"){
            var textoConfirm = "Você confirma a aprovação deste documento?";
        } else if(newStatus=="reprovado"){
            var textoConfirm = "Você confirma a reprovação deste documento?";
        }

        if(confirm(textoConfirm)){
            $(".btn-aprovar-doc-"+documento_id+"-"+tipo).html("Aguarde...")
            $(".btn-aprovar-doc-"+documento_id+"-"+tipo).addClass("disabled")
            $(".btn-reprovar-doc-"+documento_id+"-"+tipo).html("Aguarde...")
            $(".btn-reprovar-doc-"+documento_id+"-"+tipo).addClass("disabled")
            var motivo = $("#motivo-reprovado-doc-"+documento_id+"-"+tipo).val()
            var _token = $('input[name="_token"]').val();
            $.getJSON({
                url: '<?php echo getenv("APP_URL"); ?>/admin/alterar_status_documento_pendente/'+documento_id,
                method: "POST",
                data: {
                    _token: _token,
                    status: newStatus,
                    motivo: motivo,
                    tipo: tipo
                },
                success: function(data) {
                    if(data.res==true){
                        $("#linha-doc-pendente-"+documento_id+"-"+tipo).remove();
                        contarQuantidaeDocPendentes();
                    } else {
                        $(".btn-aprovar-doc-"+documento_id+"-"+tipo).html("Aprovar")
                        $(".btn-aprovar-doc-"+documento_id+"-"+tipo).removeClass("disabled")
                        $(".btn-reprovar-doc-"+documento_id+"-"+tipo).html("Reprovar")
                        $(".btn-reprovar-doc-"+documento_id+"-"+tipo).removeClass("disabled")
                        alert("Tente novamente.")
                    }
                },
                error: function() {
                    $(".btn-aprovar-doc-"+documento_id+"-"+tipo).html("Aprovar")
                    $(".btn-aprovar-doc-"+documento_id+"-"+tipo).removeClass("disabled")
                    $(".btn-reprovar-doc-"+documento_id+"-"+tipo).html("Reprovar")
                    $(".btn-reprovar-doc-"+documento_id+"-"+tipo).removeClass("disabled")
                    alert("Tente novamente.")
                }
            });  
        }


    }

    function contarQuantidaeCategoriasPendentes(){
        var qtd = $(".linha-categoria-pendente").length;
        $("#qtd-categorias-pendentes").html(qtd);
        if(qtd==0){
            $("#container-categorias-pendentes").html('<tr><td colspan="3"><div class="panel-body text-center"><br><i class="fa fa-thumbs-o-up fa-5x"></i><h4>Nenhuma solicitação pendente</h4></div></td></tr>')
        }
        return true;
    }

    function contarQuantidaeDocPendentes(){
        var qtd = $(".linha-doc-pendente").length;
        $("#qtd-doc-pendentes").html(qtd);
        if(qtd==0){
            $("#container-doc-pendentes").html('<tr><td colspan="3"><div class="panel-body text-center"><br><i class="fa fa-thumbs-o-up fa-5x"></i><h4>Nenhuma solicitação pendente</h4></div></td></tr>')
        }
        return true;
    }

    function alterarModus(){
        var _token = $('input[name="_token"]').val();
        var w = prompt('Você deve digitar o valor do novo status.\nDigite producao para deixar modo real\nDigite debug para testes\nDigite manutencao para remover o sistema do ar')
        if(w && (w=="producao" || w=="debug" || w=="manutencao")){
            var q = prompt("Informe o PIN secreto")
            $.getJSON({
                url: '<?php echo getenv("APP_URL"); ?>/admin/alterar_modus_operandi',
                method: "POST",
                data: {
                    _token: _token,
                    pin: q,
                    modo: w
                },
                success: function(data) {
                    if(data.status==true){
                        alert("Modus operandi alterado para " + w + " com sucesso!")
                        window.location.reload()
                    } else {
                        alert("PIN Incorreto.")
                    }
                },
                error: function() {
                    alert("Tente novamente.")
                }
            })
        } else {
            alert("Ação cancelada.")
        }
    }

</script>

<script type='text/javascript'>
    // Monthly afiliados chart start
        if ($('#quantity-afiliados-chart').length) {
            const data = @php echo json_encode($chartQA['data'] ?? ''); @endphp;
            const labels =  @php echo json_encode($chartQA['labels'] ?? ''); @endphp;
            const colors = @php echo json_encode($chartQA['colors'] ?? ''); @endphp;
            const min = @php echo json_encode($chartQA['min'] ?? ''); @endphp;
            const max = @php echo json_encode($chartQA['max'] ?? ''); @endphp;
            var quantityAfiliadosChart = document.getElementById('quantity-afiliados-chart').getContext('2d');
            new Chart(quantityAfiliadosChart, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Afiliados',
                        data: data,
                        backgroundColor: colors,
                        barPercentage: .3,
                        categoryPercentage: .6,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    "animation": {
                        "duration": 1,
						"onComplete": function () {
							var chartInstance = this.chart,
								ctx = chartInstance.ctx;
							
							ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
							ctx.textAlign = 'center';
							ctx.textBaseline = 'bottom';

							this.data.datasets.forEach(function (dataset, i) {
								var meta = chartInstance.controller.getDatasetMeta(i);
								meta.data.forEach(function (bar, index) {
									var data = dataset.data[index];                            
									ctx.fillText(data, bar._model.x, bar._model.y - 5);
								});
							});
						}
                    },
                    legend: {
                        display: false,
                        position:'top',
                        align:'center',
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                fontColor: '#8392a5',
                                fontSize: 10
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                color: 'rgba(77, 138, 240, .1)'
                            },
                            ticks: {
                                fontColor: '#8392a5',
                                fontSize: 10,
                                min: min,
                                max: max+10
                            }
                        }]
                    }
                }
            });
        }
    // Monthly afiliados chart end

    // Monthly sindicos chart start
        if ($('#quantity-sindicos-chart').length) {
            const data = @php echo json_encode($chartQS['data'] ?? ''); @endphp;
            const labels =  @php echo json_encode($chartQS['labels'] ?? ''); @endphp;
            const colors = @php echo json_encode($chartQS['colors'] ?? ''); @endphp;
            const min = @php echo json_encode($chartQS['min'] ?? ''); @endphp;
            const max = @php echo json_encode($chartQS['max'] ?? ''); @endphp;
            var quantitySindicosChart = document.getElementById('quantity-sindicos-chart').getContext('2d');
            new Chart(quantitySindicosChart, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sindicos',
                        data: data,
                        backgroundColor: colors,
                        barPercentage: .3,
                        categoryPercentage: .6,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    "animation": {
                        "duration": 1,
						"onComplete": function () {
							var chartInstance = this.chart,
								ctx = chartInstance.ctx;
							
							ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
							ctx.textAlign = 'center';
							ctx.textBaseline = 'bottom';

							this.data.datasets.forEach(function (dataset, i) {
								var meta = chartInstance.controller.getDatasetMeta(i);
								meta.data.forEach(function (bar, index) {
									var data = dataset.data[index];                            
									ctx.fillText(data, bar._model.x, bar._model.y - 5);
								});
							});
						}
                    },
                    legend: {
                        display: false,
                        position:'top',
                        align:'center',
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                fontColor: '#8392a5',
                                fontSize: 10
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                color: 'rgba(77, 138, 240, .1)'
                            },
                            ticks: {
                                fontColor: '#8392a5',
                                fontSize: 10,
                                min: min,
                                max: max+10
                            }
                        }]
                    }
                }
            });
        }
    // Monthly sindicos chart end

    // Monthly solicitacoes chart start
        if ($('#quantity-solicitacoes-chart').length) {
            const data = @php echo json_encode($chartQSo['data'] ?? ''); @endphp;
            const labels =  @php echo json_encode($chartQSo['labels'] ?? ''); @endphp;
            const colors = @php echo json_encode($chartQSo['colors'] ?? ''); @endphp;
            const min = @php echo json_encode($chartQSo['min'] ?? ''); @endphp;
            const max = @php echo json_encode($chartQSo['max'] ?? ''); @endphp;
            var quantitySolicitacoesChart = document.getElementById('quantity-solicitacoes-chart').getContext('2d');
            new Chart(quantitySolicitacoesChart, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Solicitações',
                        data: data,
                        backgroundColor: colors,
                        barPercentage: .3,
                        categoryPercentage: .6,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    "animation": {
                        "duration": 1,
						"onComplete": function () {
							var chartInstance = this.chart,
								ctx = chartInstance.ctx;
							
							ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
							ctx.textAlign = 'center';
							ctx.textBaseline = 'bottom';

							this.data.datasets.forEach(function (dataset, i) {
								var meta = chartInstance.controller.getDatasetMeta(i);
								meta.data.forEach(function (bar, index) {
									var data = dataset.data[index];                            
									ctx.fillText(data, bar._model.x, bar._model.y - 5);
								});
							});
						}
                    },
                    legend: {
                        display: false,
                        position:'top',
                        align:'center',
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                fontColor: '#8392a5',
                                fontSize: 10
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                color: 'rgba(77, 138, 240, .1)'
                            },
                            ticks: {
                                fontColor: '#8392a5',
                                fontSize: 10,
                                min: min,
                                max: max+10
                            }
                        }]
                    }
                }
            });
        }
    // Monthly solicitacoes chart end

    function filtroSolicitacoes(texto){ 
        dataTableNoOrder.search(texto).draw();
    }
</script>

@endpush
