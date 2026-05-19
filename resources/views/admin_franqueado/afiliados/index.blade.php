<?php

use App\Models\AfiliadoFranqueadoAsaas;
use App\Models\AfiliadoRegiao;
use App\Uteis\Formatacao;
use App\Uteis\StatusPlano;
use App\Uteis\Asaas;
use App\Models\Orcamento;
use App\Uteis\StatusOrcamento;  
use App\Models\FranqueadoRegiao;
use App\Models\FranqueadoRegiaoPlanoDisponibilizado;
?>
@extends('admin_franqueado.layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="panel panel-secondary">

    @if(Session::has('success_message'))
    <div class="alert alert-success">
        <span class="glyphicon glyphicon-ok"></span>
        {!! session('success_message') !!}

        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>

    </div>
    @endif
    <div class="panel-heading clearfix">

        <div class="pull-left">
            <a href="{{ route('admin_franqueado.afiliados.create') }}" class="btn btn-success" title="Criar novo afiliado">
                <i data-feather="plus"></i> <span>Novo afiliado</span>
            </a>
            <h4 class="mt-5 mb-5">Listagem de afiliados ({{ $afiliados->total() }})</h4>
        </div>

    </div>

    <div class="panel-body">
        <form method="GET" action="{{ route('admin_franqueado.afiliados.index') }}" class="form-inline mb-3">
            <input type="text" name="search" class="form-control mr-2" placeholder="Buscar por razão social ou nome fantasia" value="{{ request('search') }}" style="width: 380px;">
            <button type="submit" class="btn btn-primary mr-1">Buscar</button>
            @if(request('search'))
                <a href="{{ route('admin_franqueado.afiliados.index') }}" class="btn btn-secondary">Limpar</a>
            @endif
        </form>
    </div>

    @if($afiliados->isEmpty())
    <div class="panel-body text-center">
        <h4>Nenhum afiliado listado</h4>
    </div>
    @else
    <div class="panel-body panel-body-with-table">
        <div class="table-responsive">
            <table id="afiliados-table" class="table table-striped">
                <thead>
                    <tr>
                        <th width="200">Razão social</th>
                        <th>Contato</th>
                        <th>Solicitações</th>
                        <th>Categorias</th>
                        <th>Regiões</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($afiliados as $afiliado)
                    <td>{{ $afiliado->razao_social }}
                        <span class="citacao">
                            - Via <b>{{$afiliado->forma_cadastro}}</b>
                            em <?php echo Formatacao::data($afiliado->data_cadastro) ?>
                            <br>
                            @if(isset($afiliado->usuarioApp) && $afiliado->usuarioApp->isFacebook==1)
                                <label class="badge badge-primary">Login pelo Facebook</label>
                            @endif
                            @if(isset($afiliado->usuarioApp) && $afiliado->usuarioApp->isEmail==1)
                                <label class="badge badge-secondary">Login pelo E-mail</label>
                            @endif
                            <?php
                                $afiliadaAsaas = isset($asaasMap[$afiliado->id]) ? $asaasMap[$afiliado->id]->first() : null;
                                $afiliado->asaas_customer_id = $afiliadaAsaas ? $afiliadaAsaas->asaas_customer_id : null;
                            ?>

                            @if($afiliado->asaas_customer_id)
                                <label>ID do Cliente no ASAAS</label><br>
                                <h5 class="badge badge-success">ID ASAAS: {{$afiliado->asaas_customer_id}}</h5>
                                <?php
                                    $vencidas = $afiliadaAsaas->asaas_cobrancas_vencidas ? json_decode($afiliadaAsaas->asaas_cobrancas_vencidas) : [];
                                ?>
                                <br>
                                @if(count($vencidas)>0 && Asaas::isPossuiCobrancaVencida($vencidas)==false)
                                    <label  class="badge badge-warning mt-2" style="font-size: 14px;">Inadimplente</label>
                                @elseif(count($vencidas)>0 && Asaas::isPossuiCobrancaVencida($vencidas)==true)
                                    <label  class="badge badge-danger mt-2" style="font-size: 14px;">Bloqueado</label>
                                @else
                                    <label  class="badge badge-success mt-2" style="font-size: 14px;">Liberado sem cobranças pendentes</label>
                                @endif
                            @else
                                <h5 class="badge badge-warning">Não integrado ao ASAAS</h5>
                            @endif
                        </span>
                    </td>
                    <td>
                        <h5>{{ $afiliado->responsavel ? $afiliado->responsavel->nome : '--' }}</h5>

                        
                        @if($afiliado->usuarioApp!=null)
                            @if($afiliado->usuarioApp && $afiliado->usuarioApp->data_confirmacao==null)
                                <label class="badge badge-warning">E-mail não confirmado</label>
                                <label class="badge badge-info data-ultimo-envio-{{$afiliado->usuarioApp->id}}">Último envio: {{Formatacao::data($afiliado->usuarioApp->ultimo_envio_email)}}</label>
                                <a href="javascript:void(0)" class="link-reenviar-send-{{$afiliado->usuarioApp->id}}" onclick="reenviarEmail({{$afiliado->usuarioApp->id}})">Reenviar e-mail</a>
                                <label class="badge badge-warning link-reenviar-sending-{{$afiliado->usuarioApp->id}}" style="display: none;">Enviando...</label>
                                <label class="badge badge-success link-reenviar-success-{{$afiliado->usuarioApp->id}}" style="display: none;">Enviado</label>
                                <label class="badge badge-danger link-reenviar-danger-{{$afiliado->usuarioApp->id}}" style="display: none;">Não Enviado</label>
                                <br>
                            @elseif($afiliado->usuarioApp)
                                <label class="badge badge-success"> E-mail confirmado</label>
                            @endif
                            <br>
                            <span class="mail-link">{{ $afiliado->usuarioApp->email }}</span>
                        @else
                            Usuário App foi excluído.
                        @endif
                        <br><br>
                        <span class="whats-link">{{ $afiliado->telefone }}</span>
                    </td>
                    <td>
                        <?php
                            $_oc = $orcamentoCounts[$afiliado->id] ?? null;
                            $countConcluidas = $_oc ? $_oc->concluidas : 0;
                            $countCanceladas = $_oc ? $_oc->canceladas : 0;
                            $countAndamento  = $_oc ? $_oc->andamento  : 0;
                            $countTotal      = $_oc ? $_oc->total      : 0;
                        ?>
                        <label class="badge badge-success mb-2" style="font-size: 13px;">Concluidas: {{$countConcluidas}}</label><br>
                        <label class="badge badge-warning mb-2" style="font-size: 13px;">Em andamento: {{$countAndamento}}</label><br>
                        <label class="badge badge-danger mb-2" style="font-size: 13px;">Canceladas: {{$countCanceladas}}</label><br>
                        <label class="badge badge-info mb-2" style="font-size: 13px;">Total: {{$countTotal}}</label>
                    </td>
                    <td style="width: 200px;">
                        <div style="max-height: 150px; overflow: auto;">
                            @foreach($afiliado->categorias as $afiliadoCategoria)
                                @if($afiliadoCategoria->status=="aprovado")
                                    <label class="badge badge-primary" style="margin-bottom: 3px;">{{$afiliadoCategoria->categoria ? $afiliadoCategoria->categoria->nome : "Categoria removida"}}</label>
                                @elseif($afiliadoCategoria->status=="pendente")
                                    <label class="badge badge-warning" style="margin-bottom: 7px;">{{$afiliadoCategoria->categoria ? $afiliadoCategoria->categoria->nome : "Categoria removida"}}</label>
                                    <button type="button"  class="btn-question" data-trigger="focus" data-toggle="popover" style="top:0px;" title="Solicitação pendente" data-content="O afiliado deseja participar desta categoria. Siga para o Dashboard e faça a sua análise."><i class="fa fa-question"></i></button>
                                @endif

                            @endforeach
                        </div>    
                    </td>
                    <td style="width: 200px;">
                        <div style="max-height: 300px; overflow: auto;">
                            @if(count($afiliado->regioes)==0)
                                <p class="badge badge-danger mt-1">Sem Plano e Sem contrato</p>
                            @endif    
                            @foreach($afiliado->regioes as $afiliadoRegiao)
                                <?php
                                if($afiliadoRegiao->regiao){
                                    $regiaoId = $afiliadoRegiao->regiao->id;
                                    foreach($franqueadoRegioes as $franqueadoRegiaoLinha){
                                        if($franqueadoRegiaoLinha->regiao_id==$regiaoId){
                                ?>
                                        <p class="badge badge-primary">Região: {{$afiliadoRegiao->regiao->nome}}</p>
                                        <p class="badge badge-success mt-1">Plano: {{isset($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->nome) ? $afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->nome : "Sem plano assinado"}}</p>
                                        <p class="badge badge-info mt-1">Status: <?php echo isset($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->statusPlano) ? StatusPlano::getLabel($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->statusPlano) : 'Sem plano'; ?></p>
                                        
                                        <?php
                                            if($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao){
                                                $franqueado_regiao_plano_disponibilizado = FranqueadoRegiaoPlanoDisponibilizado::where("id", $afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->franqueado_regiao_plano_disponibilizado_id)->first();
                                                if($franqueado_regiao_plano_disponibilizado){
                                                    $franqueado_regiao = FranqueadoRegiao::where("id", $franqueado_regiao_plano_disponibilizado->franqueado_regiao_id)->first();
                                                }
                                            }
                                            
                                                
                                        ?>
                                        @if($franqueado_regiao)
                                            @if($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->arquivo_original==null && $franqueado_regiao->status=="ativo")
                                                <p class="badge badge-danger mt-1">Sem contrato</p>
                                            @elseif($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->arquivo_original==null && $franqueado_regiao->status=="inativo")
                                                <p class="badge badge-danger mt-1">Sem contrato</p>
                                            @endif
                                        @endif
                                        <hr>
                                <?php } } } ?>
                            @endforeach
                        </div>
                    </td>

                    <td align="right">

                        <form method="POST" action="{!! route('admin_franqueado.afiliados.destroy', $afiliado->id) !!}" accept-charset="UTF-8">
                            <input name="_method" value="DELETE" type="hidden">
                            {{ csrf_field() }}

                            <div class="btn-group btn-group-xs pull-right" role="group">
                                <a href="{{ route('admin_franqueado.afiliados.show', $afiliado->id ) }}" class="btn btn-info" title="Ver afiliado">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('admin_franqueado.afiliados.edit', $afiliado->id ) }}" class="btn btn-primary" title="Editar afiliado">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </div>

                        </form>

                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $afiliados->links() }}
        </div>
    </div>
    @endif


    @endsection

    @push('plugin-scripts')
    <script src="{{ asset('assets/plugins/dragula/dragula.min.js') }}"></script>
    @endpush

    @push('custom-scripts')
    <script src="{{ asset('assets/js/dragula.js') }}"></script>
    <script>
        function pesquisar(franqueado_id) {
            if (franqueado_id == 0) {
                window.location = window.location.origin+"/admin_franqueado/afiliados";
            } else {
                window.location = window.location.origin+"/admin_franqueado/afiliados/franqueado_id/" + franqueado_id;
            }
        }

        function reenviarEmail(usuario_id){
            $(".link-reenviar-send-"+usuario_id).hide();
            $(".link-reenviar-sending-"+usuario_id).show();
            $(".link-reenviar-danger-"+usuario_id).hide();
            $.getJSON({
                url: "/admin_franqueado/ajax/reenviarEmailConfirmarcao/" + usuario_id, 
                method: "GET", 
                data: {},
                success: function(data) {
                    $(".link-reenviar-sending-"+usuario_id).hide();
                    
                    if(data.status==true){
                        $(".link-reenviar-success-"+usuario_id).show();
                        $(".data-ultimo-envio-"+usuario_id).html(data.data)
                    } else {
                        $(".link-reenviar-danger-"+usuario_id).show();
                        $(".link-reenviar-send-"+usuario_id).show();
                    }
                }
                , error: function(e) {
                    $(".link-reenviar-send-"+usuario_id).show();
                    $(".link-reenviar-sending-"+usuario_id).hide();
                    $(".link-reenviar-danger-"+usuario_id).show();
                }
            });
        }

    </script>
    @endpush
