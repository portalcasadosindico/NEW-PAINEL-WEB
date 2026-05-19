<?php

use App\Models\Categoria;
use App\Models\ContratoAssinatura;
use App\Models\PlanoAssinaturaAfiliadoRegiao;
use App\Uteis\Asaas;
use App\Uteis\autentique\DocumentosAutentique;
use App\Uteis\Formatacao;
use App\Uteis\StatusAssinaturaPlano;
use App\Uteis\StatusPlano;

use App\Models\Orcamento;
use App\Models\AfiliadoOrcamentoInteresse;
use App\Uteis\StatusOrcamento;
?>
@extends('admin_franqueado.layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
<style>
    .card-body dt {
        font-weight: normal;
        font-size: 13px;
        margin-top: 16px;
        margin-bottom: 0px;
    }

    .card-body dd {
        font-size: 1.4rem;
        font-weight: 600;
    }

    .select-status {
        margin-top: 8px;
        display: none;
    }

    .mais-info {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Visualizando dados de afiliados</h4>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Integração com ASAAS</h5>
                        <div class="row">
                            <div class="col-md-12" style="padding: 8px;">
                                @if($possuiTokenAsaas)
                                @if($afiliado->asaas_customer_id)
                                <label>ID do Cliente no ASAAS</label><br>
                                <h5 class="badge badge-success" style="font-size: 16px;">
                                    {{$afiliado->asaas_customer_id}}
                                </h5>
                                @if($afiliado->asaas_customer_id_modo=="debug")
                                <label class="badge badge-danger">MODO TESTE</label>
                                @endif
                                <br>
                                <a href="javascript:void(0)" onclick="removerIntegracao()">Desvincular do ASAAS</a>
                                @else

                                @if(!$afiliado->cnpj)
                                <label style="font-size: 14px;">
                                    Não é possível vincular um usuário sem um CNPJ
                                </label><br>
                                @else
                                @if($customer_asaas_encontrados)
                                <label>ID do Cliente no ASAAS</label><br>
                                <h5 class="badge badge-warning" style="font-size: 16px;">Não vinculado</h5>
                                <br>
                                <a href="javascript:void(0)" onclick="vincularCustomerAsaas()">Vincular ao ASAAS
                                    cadastrando-o novamente</a>
                                @else
                                <label>ID do Cliente no ASAAS</label><br>
                                <h5 class="badge badge-warning" style="font-size: 16px;">Não vinculado</h5>
                                <br>
                                <a href="javascript:void(0)" onclick="vincularCustomerAsaas()">Vincular ao ASAAS</a>
                                @endif
                                @endif

                                @if($customer_asaas_encontrados)
                                <br><br>
                                <h6 style="width: 100%;">Encontramos estes afiliados na sua conta ASAAS com este CNPJ ou
                                    E-mail</h6>
                                @foreach($customer_asaas_encontrados as $customer)
                                <hr>
                                <b>Nome: {{$customer['name']}}</b><br>
                                CNPJ: {{$customer['cpfCnpj']}}<br>
                                E-mail: {{$customer['email']}}<br>
                                ID asaas: {{$customer['id']}}<br>
                                <a href="javascript:void(0)" onclick="vincularCustomerExistenteAsaas('<?php echo $customer['id']; ?>')">Vincular
                                    a este usuário do ASAAS</a>
                                <hr>
                                @endforeach
                                @endif

                                @endif
                                @else
                                <label class="badge badge-warning">Você não possui o token do ASAAS</label>
                                <br>
                                <a href="../perfil/edit#tokens" target="_blank">Inserir Token e depois atualize esta
                                    página</a>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>




                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Cobranças vencidas
                            <?php if ($possuiCobrancaVencida) { ?> <label class="badge badge-danger">Usuário
                                    bloqueado</label>
                            <?php } ?>
                        </h5>
                        @if(count($cobrancasVencidas)>0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                    <th>Vencimento</th>
                                    <th>Dias Venc.</th>
                                    <th>PDF</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cobrancasVencidas as $cobranca)
                                <tr>
                                    <td>{{$cobranca['description']}}</td>
                                    <td>
                                        @if(Formatacao::diasPeriodo($cobranca['dueDate'], date("Y-m-d"))>10)
                                        <label class="badge badge-danger" style="font-size: 16px;">R${{number_format($cobranca['value'], 2,
                                            ",",".")}}</label>
                                        @else
                                        <label class="badge badge-warning" style="font-size: 16px;">R${{number_format($cobranca['value'], 2,
                                            ",",".")}}</label>
                                        @endif

                                    </td>
                                    <td>{{Formatacao::data($cobranca['dueDate'])}}</td>
                                    <td>{{Formatacao::diasPeriodo($cobranca['dueDate'], date("Y-m-d"))}}</td>
                                    <td><a href="{{$cobranca['bankSlipUrl']}}" target="_blank">Boleto</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p style="text-align: center;">
                            <i class="fa fa-thumbs-o-up" style="font-size: 62px; color: #007bff;"></i>
                        <p style="text-align: center;">Top, nenhuma cobrança vencida.</p>
                        </p>
                        @endif
                    </div>
                </div>



                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Dados da empresa</h5>
                        <div class="row">
                            <div class="col-md-6" style="padding: 8px;">
                                <h5>Contrato Social</h5>
                                @if(isset($contratoSocial))
                                @if($contratoSocial->status=="pendente")
                                <label class="badge badge-warning">Pendente</label>
                                @elseif($contratoSocial->status=="aprovado")
                                <label class="badge badge-success">Aprovado</label>
                                @elseif($contratoSocial->status=="reprovado")
                                <label class="badge badge-danger">Reprovado</label>
                                @if($contratoSocial->motivo_reprovado)
                                <p>{{$contratoSocial->motivo_reprovado}}</p>
                                @endif
                                @endif
                                @endif

                                <div style="overflow: hidden;">
                                    <img style="width: 100%; opacity: 0.30;" src="{{ asset('assets/images/contrato-social.png') }}">
                                    @if(isset($contratoSocial->arquivo))
                                    <a style="position: absolute; left: 63px; top: 69px;" href="{{Storage::url($contratoSocial->arquivo)}}" class="btn btn-primary" target="_blank">Ver
                                        arquivo</a>
                                    @else
                                    <a style="position: absolute; left: 63px; top: 69px;" href="{{ route('admin_franqueado.afiliados.edit', $afiliado->id) }}" class="btn btn-danger">Upload</a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Cartão CNPJ</h5>
                                @if($cartaoCNPJ)
                                @if($cartaoCNPJ->status=="pendente")
                                <label class="badge badge-warning">Pendente</label>
                                @elseif($cartaoCNPJ->status=="aprovado")
                                <label class="badge badge-success">Aprovado</label>
                                @elseif($cartaoCNPJ->status=="reprovado")
                                <label class="badge badge-danger">Reprovado</label>
                                @endif
                                @endif

                                <div style="overflow: hidden;">
                                    <img style="width: 100%; opacity: 0.30; position: relative; top: -34px;" src="{{ asset('assets/images/cartao-cnpj.png') }}">
                                    @if(isset($cartaoCNPJ->arquivo))
                                    <a style="position: absolute; left: 63px; top: 69px;" href="{{Storage::url($cartaoCNPJ->arquivo)}}" class="btn btn-primary" target="_blank">Ver arquivo</a>

                                    @else
                                    <a style="position: absolute; left: 63px; top: 69px;" href="{{ route('admin_franqueado.afiliados.edit', $afiliado->id) }}" class="btn btn-danger">Upload</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <dl class="dl-horizontal">
                            <dt>Nome fantasia</dt>
                            <dd>{{ $afiliado->nome_fantasia }}</dd>
                            <dt>Razao social</dt>
                            <dd>{{ $afiliado->razao_social }}</dd>
                            <dt>Telefone</dt>
                            <dd class="whats-link">{{ $afiliado->telefone }}</dd>
                            <dt>E-mail
                            </dt>
                            <dd class="mail-link">{{ $afiliado->email }}</dd>
                        </dl>
                    </div>
                </div>



            </div>
            <div class="col-md-6">



                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Dados do responsável</h5>
                        <dl class="dl-horizontal">
                            <dt>CNPJ</dt>
                            <dd>{{ $afiliado->cnpj }}</dd>
                            <dt>Inscricao estadual</dt>
                            <dd>{{ $afiliado->inscricao_estadual }}</dd>
                            <dt>Inscricao municipal</dt>
                            <dd>{{ $afiliado->inscricao_municipal }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Categorias</h5>
                        <div style="max-height: 300px; overflow: auto;">
                            <?php foreach ($categorias_afiliado as $cat_afil) {
                                $super = Categoria::where("id", $cat_afil->categoria ? $cat_afil->categoria->categoria_pai_id : null)->first(); ?>
                                <label class="badge badge-secondary">{{$cat_afil->categoria ? $cat_afil->categoria->nome :
                                "Categoria removida"}} ({{isset($super->nome) ? $super->nome : '--'}})</label><br>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Solicitações</h5>
                        <?php
                        $countConcluidas = Orcamento::where("afiliado_id", $afiliado->id)->where("status", StatusOrcamento::$FINALIZADO)->count();
                        $countCanceladas = Orcamento::where("afiliado_id", $afiliado->id)->whereIn("status", [StatusOrcamento::$CANCELADO_PELO_ADMIN, StatusOrcamento::$CANCELADO_PELO_SINDICO, StatusOrcamento::$CANCELADO_PELO_AFILIADO, StatusOrcamento::$CANCELADO_PELO_FRANQUEADO])->count();
                        $countAndamento = Orcamento::where("afiliado_id", $afiliado->id)->whereIn("status", [
                            StatusOrcamento::$ANALISANDO_CANDIDATOS, StatusOrcamento::$ANALISANDO_ORCAMENTOS, StatusOrcamento::$AGUARDANDO_CONTRATO, StatusOrcamento::$CONTRATO_ASSINADO, StatusOrcamento::$EM_EXECUCAO
                        ])->count();
                        $countTotal = Orcamento::where("afiliado_id", $afiliado->id)->count();

                        $interessesCount = AfiliadoOrcamentoInteresse::where("afiliado_id", $afiliado->id)->where("descartado_afiliado", 0)->count();
                        ?>
                        <label class="badge badge-success mb-5" style="font-size: 16px;">Interesses:
                            {{$interessesCount}}</label><br>
                        <label class="badge badge-success mb-2" style="font-size: 16px;">Concluidas:
                            {{$countConcluidas}}</label><br>
                        <label class="badge badge-warning mb-2" style="font-size: 16px;">Em andamento:
                            {{$countAndamento}}</label><br>
                        <label class="badge badge-danger mb-2" style="font-size: 16px;">Canceladas:
                            {{$countCanceladas}}</label><br>
                        <label class="badge badge-info mb-2" style="font-size: 16px;">Total: {{$countTotal}}</label>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body" style="max-height: 400px; overflow: auto;">
                        <h5 class="card-title">Solicitações Interessado</h5>
                        <?php
                        $interessesOrcamentos = AfiliadoOrcamentoInteresse::join('orcamento', 'orcamento.id', 'afiliado_orcamento_interesse.orcamento_id')->where("afiliado_orcamento_interesse.afiliado_id", $afiliado->id)->where("descartado_afiliado", 0)->get();
                        foreach ($interessesOrcamentos as $orcamentoInteresse) {
                            $orcamento = Orcamento::withTrashed()->where("id", $orcamentoInteresse->orcamento_id)->first();
                        ?>
                            <div style="border: 1px solid #dedede; padding: 12px; margin-bottom: 8px;">
                                <h4><a href="../orcamentos/{{$orcamento->id}}/edit" target="_blank">Ref.:
                                        #{{$orcamento->id}} - {{$orcamento->nome}}</a></h4>
                                @if($orcamento->deleted_at!=null)
                                <label class="badge badge-danger" style="font-size: 13px;">Deletado via painel</label>
                                @elseif($orcamento->status==6 || $orcamento->status==7 || $orcamento->status==8 ||
                                $orcamento->status==9)
                                <label class="badge badge-danger" style="font-size: 13px;">Cancelado</label>
                                @elseif($orcamento->afiliado_id==$afiliado->id)
                                <label class="badge badge-success" style="font-size: 13px;">Afiliado Selecionado</label>
                                @elseif($orcamento->afiliado_id!=null)
                                <label class="badge badge-secondary" style="font-size: 13px;" title="{{$orcamento->afiliado()->withTrashed()->first()->razao_social}} foi selecionado">Outro
                                    afiliado foi selecionado</label>
                                @elseif($orcamentoInteresse->descartado_sindico==0 && $orcamento->afiliado_id==null)
                                <label class="badge badge-info" style="font-size: 13px;">O síndico aceita orçamento</label>
                                @elseif($orcamentoInteresse->descartado_sindico==1 && $orcamento->afiliado_id==null)
                                <label class="badge badge-danger" style="font-size: 13px;">O síndico descartou</label>
                                @elseif($orcamentoInteresse->descartado_sindico==-1 && $orcamento->afiliado_id==null &&
                                $orcamento->status==1)
                                <label class="badge badge-dark" style="font-size: 13px;">Nenhuma reação do síndico</label>
                                @elseif($orcamentoInteresse->descartado_sindico==-1 && $orcamento->afiliado_id==null &&
                                $orcamento->status==2)
                                <label class="badge badge-danger" style="font-size: 13px;">O síndico descartou</label>
                                @endif
                            </div>
                        <?php  } ?>
                    </div>
                </div>



            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Dados de endereço</h5>
                        <dl class="dl-horizontal">
                            <dt>Cep</dt>
                            <dd>{{ $afiliado->cep }}</dd>
                            <dt>Estado</dt>
                            <dd>{{ $afiliado->estado }}</dd>
                            <dt>Cidade</dt>
                            <dd>{{ $afiliado->cidade }}</dd>
                            <dt>Bairro</dt>
                            <dd>{{ $afiliado->bairro }}</dd>
                            <dt>Rua</dt>
                            <dd>{{ $afiliado->rua }}</dd>
                            <dt>Número</dt>
                            <dd>{{ $afiliado->numero }}</dd>
                            <dt>E-mail</dt>
                            <dd>{{ $afiliado->email }}</dd>
                            <dt>Complemento</dt>
                            <dd>{{ $afiliado->complemento }}</dd>
                            <dt>Ramo atividade</dt>
                            <dd>{{ $afiliado->rumo_atividade }}</dd>
                            <dt>Número funcionarios</dt>
                            <dd>{{ $afiliado->numero_funcionarios }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Dados da empresa</h5>
                        <dl class="dl-horizontal">
                            <dt>Status</dt>
                            @if($afiliado->status=="ativo")
                            <dd class="badge badge-success">Ativo</dd>
                            @elseif($afiliado->status=="inadimplente")
                            <dd class="badge badge-warning">Inadimplente</dd>
                            @elseif($afiliado->status=="inativo")
                            <dd class="badge badge-danger">Inativo</dd>
                            @elseif($afiliado->status=="pendente")
                            <dd class="badge badge-warning">Pendente</dd>
                            @endif
                            <dt>Usuario app
                                @if($afiliado->usuarioApp && $afiliado->usuarioApp->data_confirmacao==null)
                                <label class="badge badge-warning"> E-mail não confirmado</label>
                                @elseif($afiliado->usuarioApp)
                                <label class="badge badge-success"> E-mail confirmado</label>
                                @endif
                            </dt>
                            <dd class="mail-link">{{ isset($afiliado->usuarioApp->email) ? $afiliado->usuarioApp->email
                                : 'Sem usuário app' }}</dd>
                            <dt>Logo</dt>
                            <dd>
                                @if($afiliado->logo)
                                <img src="{{ Storage::url($afiliado->logo) }}" style="width: 150px" alt="logo">
                                @else
                                <a style="" href="{{ route('admin_franqueado.afiliados.edit', $afiliado->id) }}" class="btn btn-danger">Upload</a>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 style="margin-top:0px; margin-bottom: 24px;">Regiões deste afiliado</h4>


                        <a href="javascript:void(0)" class="btn btn-warning" data-toggle="modal" data-target="#regiao_afiliado_modal">Adicionar região ao afiliado</a>
                        <!-- Modal -->
                        <div class="modal fade" id="regiao_afiliado_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <form id="form_regiao" method="POST" class="forms-sample" action="{{ route('admin_franqueado.afiliados.store_regiao') }}" enctype="multipart/form-data">
                                        <input type="hidden" name="afiliado_id" value="{{$afiliado->id}}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Adicionando região ao
                                                afiliado</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{ csrf_field() }}
                                            <div class="row">
                                                <div class="col col-md-6">
                                                    <div class="form-group">
                                                        <label for="regiao_id">Região</label>
                                                        <select name="regiao_id" id="regiao_id">
                                                            <option value="">Selecione uma região</option>
                                                            @if(count($regioesDisponiveis)==1)
                                                            @foreach($regioesDisponiveis as $regiao)
                                                            <option selected value="{{$regiao->id}}">{{$regiao->nome}}
                                                            </option>
                                                            @endforeach
                                                            @else
                                                            @foreach($regioesDisponiveis as $regiao)
                                                            <option value="{{$regiao->id}}">{{$regiao->nome}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col col-md-6">
                                                    <div class="form-group">
                                                        <label for="plano_id">Plano</label>
                                                        <select name="plano_id" id="plano_id">
                                                            <option value="">Selecione um plano</option>
                                                            @if(count($planosDisponiveis)==1)
                                                            @foreach($planosDisponiveis as $plano)
                                                            <option selected value="{{$plano->id}}">{{$plano->nome}}
                                                            </option>
                                                            @endforeach
                                                            @else
                                                            @foreach($planosDisponiveis as $plano)
                                                            <option value="{{$plano->id}}">{{$plano->nome}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label for="status_plano_regiao">Status</label>
                                                <?php echo StatusPlano::getSelectAllStatus("status_plano_regiao", "status_plano_regiao"); ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="assinatura_asaas">Assinatura do Plano</label>
                                                <select name="assinatura_asaas" id="assinatura_asaas">
                                                    @if($possuiTokenAsaas)
                                                    <option value="1">Criar assinatura no ASAAS</option>
                                                    @else
                                                    <option value="1" disabled>Criar assinatura no ASAAS (Você não
                                                        possui Token do Asaas)</option>
                                                    @endif
                                                    <option value="2">Gerenciado pela franquia</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="tipo_assinatura">Assinaturas do Contrato</label>
                                                <select onchange="showHideTestemunhas(this.value)" name="tipo_assinatura" id="tipo_assinatura">
                                                    @if($possuiTokenAutentique)
                                                    <option value="1">Colher assinaturas pelo Autentique</option>
                                                    @else
                                                    <option value="1" disabled>Colher assinaturas pelo Autentique (Você
                                                        não possui Token do Autentique)</option>
                                                    @endif
                                                    <option value="2">Contrato já está assinado por todos</option>
                                                    <option value="4">Gerar contrato automático</option>
                                                    <option value="4">Vou enviar o contrato depois</option>
                                                </select>
                                            </div>

                                            @if($possuiTokenAutentique)
                                            <div class="row container-testemunhas">
                                                <div class="col col-md-6">
                                                    <div class="form-group">
                                                        <label for="email_testemunha1">E-mail testemunha 1</label>
                                                        <input type="text" class="form-control" placeholder="E-mail testemunha 1" name="email_testemunha1" id="email_testemunha1">
                                                    </div>
                                                </div>
                                                <div class="col col-md-6">
                                                    <div class="form-group">
                                                        <label for="email_testemunha2">E-mail testemunha 2</label>
                                                        <input type="text" class="form-control" placeholder="E-mail testemunha 2" name="email_testemunha2" id="email_testemunha2">
                                                    </div>
                                                </div>
                                            </div>
                                            @endif


                                            <div class="form-group container-contrato">
                                                <label for="arquivo_contrato">Contrato</label>
                                                <input type="file" class="form-control" name="arquivo_contrato" id="arquivo_contrato">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" onclick="validarForm()">Salvar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>




                        <table data-page-length="10" id="dataTableExample" class="table table-striped dataTableDesc no-footer" role="grid" aria-describedby="dataTableExample_info">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Região</th>
                                    <th>Plano</th>
                                    <th>Solicitações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($regioes as $item)
                                <?php
                                $planoAssinatura = PlanoAssinaturaAfiliadoRegiao::where("id", $item->plano_assinatura_afiliado_regiao_id)->first();
                                if ($planoAssinatura) {
                                    $assinaturas = PlanoAssinaturaAfiliadoRegiao::updateAssinaturasGeral($planoAssinatura->id);
                                    $planoAssinatura = PlanoAssinaturaAfiliadoRegiao::where("id", $item->plano_assinatura_afiliado_regiao_id)->first();
                                    // dd($planoAssinatura);

                                ?>
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>
                                            @if($item->modo=='debug')
                                            <label class="badge badge-danger mb-2">MODO TESTE</label>
                                            <br>
                                            @endif
                                            <b>{{$item->regiao ? $item->regiao->nome : "SEM REGIÃO"}}</b>
                                            <br><br>
                                            @if($planoAssinatura->tipo_assinatura==2)
                                            <a href="{{Storage::url($planoAssinatura->arquivo_assinado)}}" target="_blank">Ver contrato assinado
                                                (Autenticado franqueado)</a>
                                            <br><br>
                                            @if($planoAssinatura->status_afiliado == \App\Uteis\StatusAssinaturaPlano::$ASSINADO)
                                                <label class="badge badge-success">Contrato confirmado</label>
                                            @else
                                                <button class="btn btn-sm btn-warning btn-confirmar-contrato"
                                                    data-plano-id="{{ $planoAssinatura->id }}"
                                                    data-url="{{ url($url . '/plano_regiao/' . $planoAssinatura->id . '/confirmar-contrato') }}">
                                                    Confirmar contrato assinado
                                                </button>
                                            @endif
                                            @elseif($planoAssinatura->tipo_assinatura==1)
                                            <a href="{{$planoAssinatura->arquivo_original_autentique}}" target="_blank">Contrato original
                                                autentique</a>
                                            <br><br>
                                            <a href="{{$planoAssinatura->arquivo_assinado}}" target="_blank">Contrato
                                                assinado autentique</a>

                                            @if($planoAssinatura->status==StatusAssinaturaPlano::$ASSINADO)
                                            <br><br>
                                            <label class="badge badge-success">Todos assinaram o contrato</label>
                                            @endif
                                            <br><br>
                                            <a href="javascript:void(0)" onclick="showHideAssinaturasPlano(<?php echo $planoAssinatura->id; ?>)">Ver
                                                assinaturas</a>

                                            <div style="margin-top: 12px; display: none;" class="assinaturas-{{$planoAssinatura->id}}">
                                                @if(!empty($assinaturas[$planoAssinatura->id]["afiliado"]->signed))
                                                <div style="background:#fff; padding: 8px; border-radius: 8px; border: 1px solid #ccc; border-left: 3px solid #009900; ">
                                                    @else
                                                    <div style="background:#fff; padding: 8px; border-radius: 8px; border: 1px solid #ccc; border-left: 3px solid #999900; ">
                                                        @endif

                                                        <h6>Afiliado</h6>
                                                        <label>Nome assinante:
                                                            <?php echo !empty($assinaturas[$planoAssinatura->id]["afiliado"]->nome_assinante) ? $assinaturas[$planoAssinatura->id]["afiliado"]->nome_assinante : "--"; ?>
                                                        </label><br>
                                                        <label>E-mail:
                                                            <?php echo !empty($assinaturas[$planoAssinatura->id]["afiliado"]->email) ? $assinaturas[$planoAssinatura->id]["afiliado"]->email : "--"; ?>
                                                        </label><br>
                                                        <label>Data visualização:
                                                            <?php echo !empty($assinaturas[$planoAssinatura->id]["afiliado"]->viewed) ? Formatacao::data($assinaturas[$planoAssinatura->id]["afiliado"]->viewed) : "--"; ?>
                                                        </label><br>
                                                        <label>Data assinatura:
                                                            <?php echo !empty($assinaturas[$planoAssinatura->id]["afiliado"]->signed) ? Formatacao::data($assinaturas[$planoAssinatura->id]["afiliado"]->signed) : "--"; ?>
                                                        </label><br>
                                                        @if(!empty($assinaturas[$planoAssinatura->id]["afiliado"]->rejected))
                                                        <label>Data rejeitado:
                                                            <?php echo !empty($assinaturas[$planoAssinatura->id]["afiliado"]->rejected) ? Formatacao::data($assinaturas[$planoAssinatura->id]["afiliado"]->rejected) : "--"; ?>
                                                        </label>
                                                        @endif
                                                    </div>
                                                    <hr>
                                                    @if(!empty($assinaturas[$planoAssinatura->id]["franqueado"]->signed))
                                                    <div style="background:#fff; padding: 8px; border-radius: 8px; border: 1px solid #ccc; border-left: 3px solid #009900; ">
                                                        @else
                                                        <div style="background:#fff; padding: 8px; border-radius: 8px; border: 1px solid #ccc; border-left: 3px solid #999900; ">
                                                            @endif

                                                            <h6>Franqueado</h6>
                                                            <label>E-mail:
                                                                <?php echo !empty($assinaturas[$planoAssinatura->id]["franqueado"]->email) ? $assinaturas[$planoAssinatura->id]["franqueado"]->email : "--"; ?>
                                                            </label><br>
                                                            <label>Data visualização:
                                                                <?php echo !empty($assinaturas[$planoAssinatura->id]["franqueado"]->viewed) ? Formatacao::data($assinaturas[$planoAssinatura->id]["franqueado"]->viewed) : "--"; ?>
                                                            </label><br>
                                                            <label>Data assinatura:
                                                                <?php echo !empty($assinaturas[$planoAssinatura->id]["franqueado"]->signed) ? Formatacao::data($assinaturas[$planoAssinatura->id]["franqueado"]->signed) : "--"; ?>
                                                            </label><br>
                                                            @if(!empty($assinaturas[$planoAssinatura->id]["franqueado"]->rejected))
                                                            <label>Data rejeitado:
                                                                <?php echo !empty($assinaturas[$planoAssinatura->id]["franqueado"]->rejected) ? Formatacao::data($assinaturas[$planoAssinatura->id]["franqueado"]->rejected) : "--"; ?>
                                                            </label>
                                                            @endif
                                                        </div>
                                                        <hr>
                                                        @if(!empty($assinaturas[$planoAssinatura->id]["testemunha1"]->signed))
                                                        <div style="background:#fff; padding: 8px; border-radius: 8px; border: 1px solid #ccc; border-left: 3px solid #009900; ">
                                                            @else
                                                            <div style="background:#fff; padding: 8px; border-radius: 8px; border: 1px solid #ccc; border-left: 3px solid #999900; ">
                                                                @endif
                                                                <h6>Testemunha 1</h6>
                                                                <label>E-mail:
                                                                    <?php echo !empty($assinaturas[$planoAssinatura->id]["testemunha1"]->email) ? $assinaturas[$planoAssinatura->id]["testemunha1"]->email : "--"; ?>
                                                                </label><br>
                                                                <label>Data visualização:
                                                                    <?php echo !empty($assinaturas[$planoAssinatura->id]["testemunha1"]->viewed) ? Formatacao::data($assinaturas[$planoAssinatura->id]["testemunha1"]->viewed) : "--"; ?>
                                                                </label><br>
                                                                <label>Data assinatura:
                                                                    <?php echo !empty($assinaturas[$planoAssinatura->id]["testemunha1"]->signed) ? Formatacao::data($assinaturas[$planoAssinatura->id]["testemunha1"]->signed) : "--"; ?>
                                                                </label><br>
                                                                @if(!empty($assinaturas[$planoAssinatura->id]["testemunha1"]->rejected))
                                                                <label>Data rejeitado:
                                                                    <?php echo !empty($assinaturas[$planoAssinatura->id]["testemunha1"]->rejected) ? Formatacao::data($assinaturas[$planoAssinatura->id]["testemunha1"]->rejected) : "--"; ?>
                                                                </label>
                                                                @endif
                                                            </div>
                                                            <hr>
                                                            @if(!empty($assinaturas[$planoAssinatura->id]["testemunha2"]->signed))
                                                            <div style="background:#fff; padding: 8px; border-radius: 8px; border: 1px solid #ccc; border-left: 3px solid #009900; ">
                                                                @else
                                                                <div style="background:#fff; padding: 8px; border-radius: 8px; border: 1px solid #ccc; border-left: 3px solid #999900; ">
                                                                    @endif
                                                                    <h6>Testemunha 2</h6>
                                                                    <label>E-mail:
                                                                        <?php echo !empty($assinaturas[$planoAssinatura->id]["testemunha2"]->email) ? $assinaturas[$planoAssinatura->id]["testemunha2"]->email : "--"; ?>
                                                                    </label><br>
                                                                    <label>Data visualização:
                                                                        <?php echo !empty($assinaturas[$planoAssinatura->id]["testemunha2"]->viewed) ? Formatacao::data($assinaturas[$planoAssinatura->id]["testemunha2"]->viewed) : "--"; ?>
                                                                    </label><br>
                                                                    <label>Data assinatura:
                                                                        <?php echo !empty($assinaturas[$planoAssinatura->id]["testemunha2"]->signed) ? Formatacao::data($assinaturas[$planoAssinatura->id]["testemunha2"]->signed) : "--"; ?>
                                                                    </label><br>
                                                                    @if(!empty($assinaturas[$planoAssinatura->id]["testemunha2"]->rejected))
                                                                    <label>Data rejeitado:
                                                                        <?php echo !empty($assinaturas[$planoAssinatura->id]["testemunha2"]->rejected) ? Formatacao::data($assinaturas[$planoAssinatura->id]["testemunha2"]->rejected) : "--"; ?>
                                                                    </label>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @else
                                                            @if($item->afiliado->contrato_gerado)
                                                            <a href="{{Storage::url($item->afiliado->contrato_gerado)}}" target="_blank">Ver contrato
                                                                assinado (Autenticado
                                                                franqueado)</a>
                                                            <br>
                                                            @if($item->afiliado->data_contrato)
                                                            Data contrato:
                                                            <?php echo Formatacao::data($item->afiliado->data_contrato); ?><br>
                                                            @endif
                                                            @if($item->afiliado->data_ativacao)
                                                            Data Ativiação:
                                                            <?php echo Formatacao::data($item->afiliado->data_ativacao); ?><br>
                                                            @endif
                                                            @else
                                                            <a href="javascript:void(0)" class="btn btn-danger" data-toggle="modal" data-target="#regiao_afiliado_upload_contrato_modal_{{$planoAssinatura->id}}">Upload
                                                                do contrato</a>
                                                            <br>
                                                            <a href="javascript:void(0)" id="btn-gerar-contrato-{{$item->id}}" class="btn btn-warning mt-2" data-toggle="modal" data-target="#regiao_afiliado_gerar_contrato_modal_{{$planoAssinatura->id}}">Gerar
                                                                contrato automático</a>

                                                            <!-- MODAL -->
                                                            <!-- Modal -->
                                                            <div class="modal fade" id="regiao_afiliado_upload_contrato_modal_{{$planoAssinatura->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-md" role="document">
                                                                    <div class="modal-content">
                                                                        <form id="form_regiao_upload_{{$planoAssinatura->id}}" method="POST" class="forms-sample" action="{{ route('admin_franqueado.afiliados.store_contrato') }}" enctype="multipart/form-data">
                                                                            <input type="hidden" name="afiliado_id" value="{{$afiliado->id}}">
                                                                            <input type="hidden" name="plano_assinatura_afiliado_regiao_id" value="{{$planoAssinatura->id}}">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Upload do
                                                                                    contrato</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                {{ csrf_field() }}
                                                                                <div class="form-group">
                                                                                    <label for="tipo_assinatura">Assinaturas
                                                                                        do Contrato</label>
                                                                                    <select onchange="showHideTestemunhasUpload(this.value, <?php echo $planoAssinatura->id; ?>)" name="tipo_assinatura" id="tipo_assinatura">
                                                                                        @if($possuiTokenAutentique)
                                                                                        <option value="1">Colher assinaturas
                                                                                            pelo Autentique</option>
                                                                                        @else
                                                                                        <option value="1" disabled>Colher
                                                                                            assinaturas pelo Autentique
                                                                                            (Você não possui Token do
                                                                                            Autentique)</option>
                                                                                        @endif
                                                                                        <option value="2">Contrato já está
                                                                                            assinado por todos</option>
                                                                                    </select>
                                                                                </div>

                                                                                @if($possuiTokenAutentique)
                                                                                <div class="row container-testemunhas contrainer-{{$planoAssinatura->id}}">
                                                                                    <div class="col col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label for="email_testemunha1">E-mail
                                                                                                testemunha 1</label>
                                                                                            <input type="text" class="form-control" placeholder="E-mail testemunha 1" name="email_testemunha1" id="email_testemunha1">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label for="email_testemunha2">E-mail
                                                                                                testemunha 2</label>
                                                                                            <input type="text" class="form-control" placeholder="E-mail testemunha 2" name="email_testemunha2" id="email_testemunha2">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                @endif

                                                                                <div class="form-group container-contrato">
                                                                                    <label for="arquivo_contrato">Contrato</label>
                                                                                    <input type="file" class="form-control" name="arquivo_contrato" id="arquivo_contrato">
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-primary" onclick="validarFormRegiao('form_regiao_upload_{{$planoAssinatura->id}}')">Salvar</button>
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Modal -->
                                                            <div class="modal fade" id="regiao_afiliado_gerar_contrato_modal_{{$planoAssinatura->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-md" role="document">
                                                                    <div class="modal-content">
                                                                        <form id="form_regiao_{{$planoAssinatura->id}}" method="POST" class="forms-sample" action="{{ route('admin_franqueado.afiliados.store_contrato') }}" enctype="multipart/form-data">
                                                                            <input type="hidden" name="afiliado_id" value="{{$afiliado->id}}">
                                                                            <input type="hidden" name="plano_assinatura_afiliado_regiao_id" value="{{$planoAssinatura->id}}">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Gerando contrato
                                                                                    automaticamente</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                {{ csrf_field() }}
                                                                                <div class="form-group">
                                                                                    @if($possuiTokenAutentique)
                                                                                    <h5>O sistema irá colher as assinaturas
                                                                                        pelo Autentique.</h5>
                                                                                    <p>
                                                                                        Você reberá um e-mail na sua conta
                                                                                        de e-mail utilizada para login e
                                                                                        realizar a assinatura do contrato ;)
                                                                                    </p>
                                                                                    @else
                                                                                    <label>Você precisa inserir o token do
                                                                                        Autentique. Vá até o seu <a href="admin_franqueado/perfil/edit">Perfil</a>
                                                                                        e siga as instruções.</label>
                                                                                    @endif
                                                                                </div>

                                                                                @if($possuiTokenAutentique)
                                                                                <h5>Informe a data de início do contrato
                                                                                </h5>
                                                                                <div class="row container-testemunhas contrainer-{{$planoAssinatura->id}}">
                                                                                    <div class="col col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label for="data_contrato">Data
                                                                                                do contrato</label>
                                                                                            <input type="text" value="<?php echo date("
                                                                                            d/m/Y"); ?>" class="form-control" placeholder="Data do contrato" name="data_contrato_auto" id="data_contrato_auto">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <h5>Precisamos do e-mail de duas testemunhas
                                                                                </h5>
                                                                                <div class="row container-testemunhas contrainer-{{$planoAssinatura->id}}">
                                                                                    <div class="col col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label for="email_testemunha1">E-mail
                                                                                                testemunha 1</label>
                                                                                            <input type="text" class="form-control" placeholder="E-mail testemunha 1" name="email_testemunha1" id="email_testemunha1_auto-{{$item->id}}">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label for="email_testemunha2">E-mail
                                                                                                testemunha 2</label>
                                                                                            <input type="text" class="form-control" placeholder="E-mail testemunha 2" name="email_testemunha2" id="email_testemunha2_auto-{{$item->id}}">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                @endif

                                                                                <div class="form-group">
                                                                                    <label for="tipo_contrato">Tipo do
                                                                                        contrato</label>

                                                                                    @if($planoAssinatura->tipo==1)
                                                                                    <h5>O sistema escolheu o template para
                                                                                        contrato <u>Parceiro</u> <button type="button" class="btn-question" data-trigger="focus" data-toggle="popover" title="Como chegamos nesta conclusão?" data-content="O plano deste afiliado está marcado como Parceiro."><i class="fa fa-question"></i></button>
                                                                                    </h5>
                                                                                    @elseif($planoAssinatura->valor==0)
                                                                                    <h5>O sistema escolheu o template para
                                                                                        contrato <u>Gratuito</u> <button type="button" class="btn-question" data-trigger="focus" data-toggle="popover" title="Como chegamos nesta conclusão?" data-content="O valor para este plano é de zero reais."><i class="fa fa-question"></i></button>
                                                                                    </h5>
                                                                                    @elseif($planoAssinatura->isTerceirizada==1)
                                                                                    <h5>O sistema escolheu o template para
                                                                                        contrato <u>Terceirizada</u> <button type="button" class="btn-question" data-trigger="focus" data-toggle="popover" title="Como chegamos nesta conclusão?" data-content="O plano que o afiliado optou foi marcado pelo super admin como um plano de Terceirizadas. Quando um plano é marcado como Terceirizada, não importa o percentual de comissão, o contrato será considerado como o de Terceirizada."><i class="fa fa-question"></i></button>
                                                                                    </h5>
                                                                                    @elseif($planoAssinatura->valor_comissao!==null
                                                                                    && $planoAssinatura->valor_comissao==0)
                                                                                    <h6>O sistema escolheu o template para
                                                                                        contrato <u>Não Comissionado</u>
                                                                                        <button type="button" class="btn-question" data-trigger="focus" data-toggle="popover" title="Como chegamos nesta conclusão?" data-content="O plano que o afiliado optou não possui comissão."><i class="fa fa-question"></i></button>
                                                                                    </h6>
                                                                                    @elseif($planoAssinatura->valor_comissao>0)
                                                                                    <h5>O sistema escolheu o template para
                                                                                        contrato <u>Comissionado</u> <button type="button" class="btn-question" data-trigger="focus" data-toggle="popover" title="Como chegamos nesta conclusão?" data-content="O plano que o afiliado optou possui uma comissão de {{$planoAssinatura->valor_comissao}}%."><i class="fa fa-question"></i></button>
                                                                                    </h5>
                                                                                    @else
                                                                                    <h5>O sistema não encontrou o template
                                                                                        para este tipo de contrato</h5>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                @if($possuiTokenAutentique)
                                                                                <button type="button" id="btn-gerar-{{$item->id}}" class="btn btn-primary" onclick="gerarContrato('{{$item->id}}')">Gerar
                                                                                    Contrato</button>
                                                                                @else
                                                                                Não possui token do ASAAS cadastrado.
                                                                                @endif
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- MODAL -->
                                                            @endif
                                                            @endif
                                        </td>
                                        <td>
                                            @if($item->modo=='debug')
                                            <label class="badge badge-danger mb-2">MODO TESTE</label>
                                            <br>
                                            @endif
                                            <label class="badge badge-primary" style="font-size: 14px;">{{isset($planoAssinatura->nome) ?
                                            $planoAssinatura->nome : 'Sem plano assinado'}}</label> <a href="javascript:void(0)" onclick="detalhesPlano({{$item->id}})">+
                                                Detalhes</a>
                                            <div class="mais-info mais-info-{{$item->id}}">
                                                <label class="badge badge-info mt-1">Data de assinatura:
                                                    <?php echo isset($planoAssinatura->data_cadastro) ? Formatacao::data($planoAssinatura->data_cadastro) : 'Sem data de cadastro'; ?>
                                                </label>
                                                <br><label class="badge badge-info mt-1">Valor:
                                                    R${{isset($planoAssinatura->valor) ? $planoAssinatura->valor :
                                                0}}</label>
                                                <br><label class="badge badge-info mt-1">Comissão:
                                                    {{isset($planoAssinatura->valor_comissao) ?
                                                $planoAssinatura->valor_comissao : 0}}%</label>
                                                @if(isset($planoAssinatura->isTerceirizada) &&
                                                $planoAssinatura->isTerceirizada==1)
                                                <br><label class="badge badge-info mt-1">Terceirizada</label>
                                                @endif
                                                <br>

                                                <?php $diasRestantes = isset($planoAssinatura->data_expiracao) ? Formatacao::diasPeriodo(date("Y-m-d"), $planoAssinatura->data_expiracao) : null; ?>
                                                @if($diasRestantes!=null && $planoAssinatura->data_cancelamento==null)
                                                @if($diasRestantes==1)
                                                <label class="badge badge-warning">Expira Amanhã</label>
                                                @elseif($diasRestantes>1)
                                                <label class="badge badge-success">Expira em:
                                                    <?php echo Formatacao::data($planoAssinatura->data_expiracao); ?>
                                                </label>
                                                @elseif($diasRestantes==0)
                                                <label class="badge badge-warning">Expira Hoje</label>
                                                @else
                                                <label class="badge badge-danger">Expirou em:
                                                    <?php echo Formatacao::data($planoAssinatura->data_expiracao); ?>
                                                </label>
                                                @endif
                                                @endif


                                                @if(isset($planoAssinatura->data_cancelamento) &&
                                                $planoAssinatura->data_cancelamento)
                                                <label class="badge badge-danger">Cancelado em em:
                                                    <?php echo Formatacao::data($planoAssinatura->data_cancelamento); ?>
                                                </label>
                                                @endif
                                            </div>
                                            <br><br>

                                            <?php
                                            //dd($planoAssinatura);
                                            ?>
                                            @if(isset($planoAssinatura->statusPlano) &&
                                            $planoAssinatura->data_cancelamento==null)
                                            <label class="badge badge-<?php echo StatusPlano::getColorTheme($planoAssinatura->statusPlano); ?>" style="font-size: 12px;">Status:
                                                <?php echo StatusPlano::getLabel($planoAssinatura->statusPlano); ?>
                                            </label>
                                            @endif

                                            @if(isset($planoAssinatura->asaas_assinatura_id) &&
                                            $planoAssinatura->asaas_assinatura_id)
                                            <label class="badge badge-secondary">Integrado ao asaas</label>
                                            <br><br>
                                            @if(!$planoAssinatura->data_cancelamento)
                                            <a href="javascript:void(0)" onclick="cancelarAssinatura('<?php echo $planoAssinatura->asaas_assinatura_id; ?>')">Cancelar
                                                assinatura</a>
                                            @else
                                            <label class="badge badge-danger">Plano cancelado</label>
                                            @endif
                                            @else
                                            <a href="javascript:void(0)" onclick="alterarStatus(<?php echo isset($planoAssinatura->id) ? $planoAssinatura->id : 0; ?>)">Alterar
                                                status</a>
                                            <div class="select-status select-status-{{isset($planoAssinatura->id) ? $planoAssinatura->id : 0 }}">
                                                <?php echo StatusPlano::getSelectAllStatus("status_plano", "status_plano", null, isset($planoAssinatura->statusPlano) ? $planoAssinatura->statusPlano : null, "alterarStatusPlanoRegiao(" . (isset($planoAssinatura->id) ? $planoAssinatura->id : 0) . ", this.value)"); ?>
                                            </div>
                                            <br><br>
                                            @if($possuiTokenAsaas)
                                            <a href="javascript:void(0)" onclick="vincularAsaasShow(<?php echo isset($planoAssinatura->id) ? $planoAssinatura->id : 0; ?>)">Vincular
                                                ao ASAAS</a>

                                            <div class="container-vincular-asaas-<?php echo isset($planoAssinatura->id) ? $planoAssinatura->id : 0; ?>" style="display: none;">
                                                <a href="javascript:void(0)" class="btn btn-success" style="margin-top: 12px;" onclick="vincularAsaas(<?php echo isset($planoAssinatura->id) ? $planoAssinatura->id : 0; ?>)">Vincular
                                                    ao asaas criando nova assinatura</a>
                                                <?php $cont = 0; ?>
                                                @if($assinaturasCustomer && count($assinaturasCustomer)>0)
                                                <h5 style="margin-bottom: 10px; margin-top: 10px;">OU</h5>
                                                <label>Achamos que uma destas assinaturas do ASAAS pode integrar com o
                                                    sistema <b>Casa do Síndico</b> para a região deste afiliado</label>
                                                @foreach($assinaturasCustomer as $assinaturaCust)
                                                @if($planoAssinatura->valor==$assinaturaCust['value'])
                                                <?php $cont++; ?>
                                                <div class="row" style="border: 1px solid #ccc; padding: 6px;">
                                                    <div class="col-md-6">
                                                        <h5>#{{$assinaturaCust['description']}}</h5>
                                                        <?php echo Formatacao::prefixoSufixo($assinaturaCust['value'], "R$"); ?><br>
                                                        Próximo lançamento:
                                                        <?php echo isset($assinaturaCust['nextDueDate']) ? Formatacao::data($assinaturaCust['nextDueDate']) : 'Não há'; ?><br>
                                                        Forma de pagamento: {{$assinaturaCust['billingType']}}<br>
                                                        Período: {{$assinaturaCust['cycle']}}<br>
                                                        Status: {{$assinaturaCust['status']}}
                                                    </div>
                                                    <div class="col-md-6" style="text-align: center;">
                                                        <a style="position: relative; top: 40%;" href="javascript:void(0)" onclick="vincularAssinatura('<?php echo $assinaturaCust['id']; ?>', '<?php echo $planoAssinatura->id; ?>', '<?php echo $assinaturaCust['nextDueDate']; ?>', '<?php echo $afiliado->asaas_customer_id; ?>')">Clique
                                                            para vincular esta assinatura</a>
                                                    </div>
                                                </div>
                                                @endif
                                                @endforeach
                                                @if($cont==0)
                                                <label class="badge badge-warning">Nenhuma assinatura encontrada. Crie uma
                                                    com o botão <b>Vincular ao asaas criando nova assinatura</b>.</label>
                                                @endif
                                                @endif
                                            </div>
                                            @else
                                            <label class="badge badge-warning">Você não possui o Token do ASAAS</label>
                                            @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->regiao)
                                            <a style="font-size: 12px;" href="javascript:void(0)" onclick="carregarSolicitacoes(<?php echo $item->regiao->id; ?>, '<?php echo $item->regiao->nome; ?>')" class="btn btn-primary"><i data-feather="list" style="width: 20px;"></i>
                                                {{$item->regiao->orcamentos}}</a>
                                            @endif
                                        </td>
                                    </tr>
                                <?php } ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 style="margin-top:0px; margin-bottom: 24px;">Solicitações</h4>
                        <h6 style="margin-bottom: 20px;" id="nome_condominio_solicitacao">Escolha uma regiao</h6>

                        <div class="container_solicitacoes row">

                        </div>

                    </div>
                </div>

            </div>
        </div>


    </div>



    <div class="pull-right">
        <form method="POST" action="{!!  route('admin_franqueado.afiliados.destroy', $afiliado->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
            <div class="btn-group btn-group-sm" role="group">
                <a href="{{ route('admin_franqueado.afiliados.index') }}" class="btn btn-primary" title="Ver todos afiliados">
                    <i data-feather="list"></i>
                </a>

                <a href="{{ route('admin_franqueado.afiliados.create') }}" class="btn btn-success" title="Novo afiliado">
                    <i data-feather="plus"></i>
                </a>

                <a href="{{ route('admin_franqueado.afiliados.edit', $afiliado->id) }}" class="btn btn-primary" title="Editar afiliado">
                    <i class="fa fa-pencil"></i>
                </a>
            </div>
        </form>
    </div>


</div>
</div>
</div>
</div>
</div>
{{ csrf_field() }}
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/dragula/dragula.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/dragula.js') }}"></script>

<script>
    function carregarSolicitacoes(regiao_id, nome) {
        $(".container_solicitacoes").html("<h6>Carregando...</h6>");
        $("#nome_condominio_solicitacao").html("Regiao " + nome);

        $.getJSON({
            url: "../afiliados/regiao/" + regiao_id + "/orcamentos/afiliado_id/<?php echo $afiliado->id; ?>",
            method: "GET",
            data: {},
            success: function(data) {
                $(".container_solicitacoes").html("");
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        var solicitacao = data[i];
                        $(".container_solicitacoes").append(solicitacao);
                    }
                } else {
                    $(".container_solicitacoes").html("<h6>Sem solicitações</h6>");
                }
            },
            error: function() {

            }
        });
    }

    function alterarStatus(index) {
        $(".select-status").hide();
        if ($(".select-status-" + index).css("display") == "none") {
            $(".select-status-" + index).show(300);
        } else {
            $(".select-status-" + index).hide(300);
        }
    }

    function detalhesPlano(id) {
        $(".mais-info").hide(300);
        if ($(".mais-info-" + id).css("display") == "none") {
            $(".mais-info-" + id).show(300);
        }
    }

    function alterarStatusPlanoRegiao(id, newStatus) {
        console.log(newStatus);
        var _token = $('input[name="_token"]').val();
        $.getJSON({
            url: "../alterar_status/plano_regiao/" + id,
            method: "POST",
            data: {
                _token: _token,
                newStatus: newStatus
            },
            success: function(data) {
                window.location.reload();
                console.log(data);
            },
            error: function(e) {
                window.location.reload();
            }
        });
    }



    function validaData(data) {
        try {
            $m = data.split("/");
            return data.length == 10 && $m.length == 3 && $m[0].length == 2 && $m[1].length == 2 && $m[2].length == 4;
        } catch (e) {}

    }

    function showHideTestemunhas(item) {
        if (item == 1) {
            $(".container-testemunhas").show(300);
            $(".container-contrato").show(300);
        } else if (item == 4) {
            $(".container-testemunhas").hide(300);
            $(".container-contrato").hide(300);
        } else if (item == 2) {
            $(".container-testemunhas").hide(300);
            $(".container-contrato").show(300);
        }
    }

    function showHideTestemunhasUpload(item, plano_regiao_afiliado_id) {
        if (item == 1) {
            $(".contrainer-" + plano_regiao_afiliado_id).show(300);
        } else if (item == 2) {
            $(".contrainer-" + plano_regiao_afiliado_id).hide(300);
        }
    }



    function semToken() {
        alert("Você não possui o Token do Asaas cadastrado. Vá no seu perfil e preencha o campo.");
        if (confirm("Você deseja inserir o Token do Asaas agora?")) {
            window.location = "../perfil/edit";
        }
    }

    function validarFormRegiao(form_id) {
        $("#" + form_id).submit();
    }

    function validarForm() {

        if (!$("#regiao_id").val() > 0) {
            alert("Selecione uma região");
            $("#regiao_id").focus();
            return;
        }

        if (!$("#plano_id").val() > 0) {
            alert("Selecione um plano");
            $("#plano_id").focus();
            return;
        }

        if (!$("#status_plano_regiao").val() > 0) {
            alert("Selecione um status");
            $("#status_plano_regiao").focus();
            return;
        }

        if (!$("#status_plano_regiao").val() > 0) {
            alert("Selecione um status");
            $("#status_plano_regiao").focus();
            return;
        }

        if ($("#tipo_assinatura").val() == 1) {
            if ($("#email_testemunha1").val() == "") {
                alert("Insira o e-mail da testemunha 1");
                $("#email_testemunha1").focus();
                return;
            }

            if ($("#email_testemunha2").val() == "") {
                alert("Insira o e-mail da testemunha 2");
                $("#email_testemunha2").focus();
                return;
            }
        }

        if ($("#tipo_assinatura").val() != 4) {
            if ($("#arquivo_contrato").val() == "") {
                alert("Insira um arquivo de contrato");
                return;
            }
        }

        $("#form_regiao").submit();
        return false;
    }

    function vincularAsaasShow(id) {
        if ($(".container-vincular-asaas-" + id).css("display") == "none") {
            $(".container-vincular-asaas-" + id).show(300);
        } else {
            $(".container-vincular-asaas-" + id).hide(300);
        }
    }

    function removerIntegracao() {
        if (confirm("Deseja realmente desvincular o afiliado do ASAAS?")) {
            window.location = "../afiliados/{{$afiliado->id}}/removerasaas";
        }
    }

    function vincularCustomerAsaas() {
        if (confirm("Deseja realmente vincular o afiliado ao seu ASAAS?")) {
            window.location = "../afiliados/{{$afiliado->id}}/vincularasaas";
        }
    }

    function vincularCustomerExistenteAsaas(customer_id) {
        if (confirm("Deseja realmente vincular o afiliado ao cliente " + customer_id + " do seu ASAAS?")) {
            window.location = "../afiliados/{{$afiliado->id}}/vincularasaasexistente/" + customer_id;
        }
    }

    function cancelarAssinatura(plano_assinado_id) {
        if (confirm("Deseja realmente cancelar esta assinatura?")) {
            window.location = "../afiliados/{{$afiliado->id}}/cancelarassinatura/" + plano_assinado_id;
        }
    }


    function vincularAsaas(plano_assinatura_id) {
        if (confirm("Deseja vincular esta assinatura no ASAAS?")) {
            window.location = "../afiliados/{{$afiliado->id}}/inserirassinatura/" + plano_assinatura_id;
        }
    }

    function vincularAssinatura(plano_assinatura_asaas_id, plano_assinatura_afiliado_id, data_vencimento, customer_id) {
        if (confirm("Deseja vincular esta assinatura no ASAAS?")) {
            window.location = "../afiliados/{{$afiliado->id}}/vincularassinaturaexistente/" + plano_assinatura_asaas_id + "/" +
                plano_assinatura_afiliado_id + "/" + data_vencimento + "/" + customer_id;
        }
    }

    function showHideAssinaturasPlano(index) {
        if ($(".assinaturas-" + index).css("display") == "none") {
            $(".assinaturas-" + index).show(300);
        } else {
            $(".assinaturas-" + index).hide(300);
        }
    }

    var letsgo = true

    function gerarContrato(afiliado_regiao_id) {
        if (letsgo == false) {
            alert("Não dê dupo clique nos botões. Um clique já dispara a ação no sistema.")
            letsgo = true
            return;
        }
        if (letsgo == true) {
            letsgo = false
        }
        var email_testemunha1 = $("#email_testemunha1_auto-" + afiliado_regiao_id).val().trim();
        var email_testemunha2 = $("#email_testemunha2_auto-" + afiliado_regiao_id).val().trim();

        if (email_testemunha1 == email_testemunha2) {
            alert("Operação ilegal. As testemunhas não podem ser a mesma pessoa.")
            return;
        }

        $("#btn-gerar-contrato-" + afiliado_regiao_id).html("Gerando contrato...");
        $("#btn-gerar-contrato-" + afiliado_regiao_id).addClass("disabled");

        $("#btn-gerar-" + afiliado_regiao_id).html("Gerando contrato...");
        $("#btn-gerar-" + afiliado_regiao_id).addClass("disabled");



        var data_contrato = $("#data_contrato_auto").val();

        var _token = $('input[name="_token"]').val();
        $.getJSON({
            url: "../gerarContrato/" + afiliado_regiao_id,
            method: "POST",
            data: {
                _token: _token,
                email_testemunha1: email_testemunha1,
                email_testemunha2: email_testemunha2,
                data_contrato: data_contrato
            },
            success: function(data) {

                if (data.status == true) {
                    alert("Contrato gerado com sucesso.");
                    window.location.reload();
                } else if (data.status == false) {
                    alert(data.errors[0].error_message);
                    $("#btn-gerar-contrato-" + afiliado_regiao_id).html("Gerar contrato");
                    $("#btn-gerar-contrato-" + afiliado_regiao_id).removeClass("disabled");

                    $("#btn-gerar-" + afiliado_regiao_id).html("Gerar contrato");
                    $("#btn-gerar-" + afiliado_regiao_id).removeClass("disabled");
                }
                letsgo = true
            },
            error: function(e) {
                $("#btn-gerar-contrato-" + afiliado_regiao_id).html("Gerar contrato");
                $("#btn-gerar-contrato-" + afiliado_regiao_id).removeClass("disabled");

                $("#btn-gerar-" + afiliado_regiao_id).html("Gerar contrato");
                $("#btn-gerar-" + afiliado_regiao_id).removeClass("disabled");
                alert("Tente novamente em breve.")
                letsgo = true
            }
        });
    }

    function alterarOpcaoEnvioContrato(index) {
        if (index == 1) {
            $(".container-contrato").show(300);
        } else {
            $(".container-contrato").hide(300);
        }
    }

    $(document).on('click', '.btn-confirmar-contrato', function () {
        if (!confirm('Confirmar que o contrato deste afiliado foi assinado? Isso liberará o acesso às solicitações no app.')) return;
        var btn = $(this);
        var url = btn.data('url');
        var token = $('input[name="_token"]').val();
        btn.prop('disabled', true).text('Confirmando...');
        $.ajax({
            url: url,
            method: 'POST',
            data: { _token: token },
            success: function (res) {
                if (res.status) {
                    btn.closest('td').find('.btn-confirmar-contrato').replaceWith('<label class="badge badge-success">Contrato confirmado</label>');
                } else {
                    alert(res.message || 'Erro ao confirmar contrato.');
                    btn.prop('disabled', false).text('Confirmar contrato assinado');
                }
            },
            error: function () {
                alert('Erro de comunicação. Tente novamente.');
                btn.prop('disabled', false).text('Confirmar contrato assinado');
            }
        });
    });
</script>
@endpush