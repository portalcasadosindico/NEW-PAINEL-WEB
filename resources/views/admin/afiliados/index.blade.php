<?php

use App\Models\AfiliadoFranqueadoAsaas;
use App\Models\Franqueado;
use App\Uteis\Formatacao;
use App\Uteis\StatusPlano;


use App\Models\Orcamento;
use App\Uteis\StatusOrcamento;  


?>
@extends('layout.master')

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
            <a href="{{ route('admin.afiliados.create') }}" class="btn btn-success" title="Criar novo afiliado">
                <i data-feather="plus"></i> <span>Novo afiliado</span>
            </a>
            <h4 class="mt-5 mb-5">Listagem de afiliados</h4>
        </div>

        <div class="btn-group btn-group-sm pull-right" style="float: right;" role="group">
            <div style="position: relative; top: -12px;">
                <label>Filtro por modo cadastro</label><br>
                <select onchange="pesquisar(this.value)">
                    <option value="0">Todos os modos</option>
                    <option <?php if($franqueado_id==-1) echo "selected"; ?> value="-1">Cadastrados pelo App</option>
                    @foreach($franqueados as $franqueado)
                        <option <?php if($franqueado_id==$franqueado->id) echo "selected"; ?> value="{{$franqueado->id}}">Cadastrado por {{$franqueado->nome}}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>



    @if(count($afiliados) == 0)
    <div class="panel-body text-center">
        <h4>Nenhum afiliado listado</h4>
    </div>
    @else
    <div class="panel-body panel-body-with-table">
        <div class="table-responsive">
            <table data-page-length="25" id="dataTableExample" class="table table-striped dataTable no-footer" role="grid" aria-describedby="dataTableExample_info">
                <thead>
                    <tr>
                        <th>Razão social</th>
                        <th>
                            Contato
                        </th>
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
                            @if(isset($afiliado->usuarioApp) && isset($afiliado->usuarioApp->isFacebook))
                            @if($afiliado->usuarioApp && $afiliado->usuarioApp->isFacebook==1)
                                <label class="badge badge-primary">Login pelo Facebook</label>
                            @endif
                            @else
                                <span>Facebook não disponível</span>
                            @endif
                            @if(isset($afiliado->usuarioApp) && isset($afiliado->usuarioApp->isEmail))
                            @if($afiliado->usuarioApp && $afiliado->usuarioApp->isEmail==1)
                                <label class="badge badge-secondary">Login pelo E-mail</label>
                            @endif
                            @else
                                <span>E-mail não disponível</span>
                            @endif
                            <?php
                                $afiliadaAsaas = AfiliadoFranqueadoAsaas::where("afiliado_id", $afiliado->id)
                                ->orderBy("id", "desc")
                                ->get();

                                foreach($afiliadaAsaas as $i => $afil){
                                    $franqueado = Franqueado::where("id", $afil->franqueado_id)->first();
                                    if($franqueado){
                                        $afiliadaAsaas[$i]->franqueado_nome = $franqueado->nome;
                                    } else {
                                        $afiliadaAsaas[$i]->franqueado_nome = "Sys";
                                    }
                                    
                                }

                                $afiliado->asaas_customer_id = $afiliadaAsaas;
                            ?>
                                    @foreach($afiliado->asaas_customer_id as $afiliadoAsaas)
                                        <div style="margin-bottom: 16px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                                            <label>ID do Cliente no ASAAS franquia <b>{{$afiliadoAsaas->franqueado_nome}}</b></label><br>
                                            <h5 class="badge badge-success" style="font-size: 12px; margin-top: 0px;">{{$afiliadoAsaas->asaas_customer_id}}</h5>
                                            @if($afiliadoAsaas->modo=="debug")
                                                <label class="badge badge-danger">MODO TESTE</label>
                                            @endif
                                        </div>
                                    @endforeach
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
                            $countConcluidas = Orcamento::where("afiliado_id", $afiliado->id)->where("status", StatusOrcamento::$FINALIZADO)->count();
                            $countCanceladas = Orcamento::where("afiliado_id", $afiliado->id)->whereIn("status", [StatusOrcamento::$CANCELADO_PELO_ADMIN, StatusOrcamento::$CANCELADO_PELO_SINDICO, StatusOrcamento::$CANCELADO_PELO_AFILIADO, StatusOrcamento::$CANCELADO_PELO_FRANQUEADO])->count();
                            $countAndamento = Orcamento::where("afiliado_id", $afiliado->id)->whereIn("status", [
                                StatusOrcamento::$ANALISANDO_CANDIDATOS, StatusOrcamento::$ANALISANDO_ORCAMENTOS, StatusOrcamento::$AGUARDANDO_CONTRATO, StatusOrcamento::$CONTRATO_ASSINADO, StatusOrcamento::$EM_EXECUCAO])->count();
                            $countTotal = Orcamento::where("afiliado_id", $afiliado->id)->count();
                            
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
                                    <div>
                                        <label class="badge badge-warning" style="margin-bottom: 7px;" title="Aguardando análise do franqueado">{{$afiliadoCategoria->categoria ? $afiliadoCategoria->categoria->nome : "Categoria removida"}}</label>
                                    </div>
                                @endif
                            @endforeach
                        </div>    
                    </td>
                    <td style="width: 200px;">    
                        <div style="max-height: 150px; overflow: auto;">
                            @foreach($afiliado->regioes as $afiliadoRegiao)
                                <div style="margin-bottom: 7px;">    
                                    <p class="badge badge-primary">Região: {{$afiliadoRegiao->regiao ? $afiliadoRegiao->regiao->nome : "SEM REGIÃO"}}</p>
                                    <p class="badge badge-success mt-1">Plano: {{isset($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->nome) ? $afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->nome : "Sem plano assinado"}}</p>
                                    <p class="badge badge-info mt-1">Status: <?php echo isset($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->statusPlano) ? StatusPlano::getLabel($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->statusPlano) : 'Sem plano'; ?></p>
                                    <hr>
                                </div>
                            @endforeach
                        </div>
                    </td>

                    <td align="right">

                        <form method="POST" action="{!! route('admin.afiliados.destroy', $afiliado->id) !!}" accept-charset="UTF-8">
                            <input name="_method" value="DELETE" type="hidden">
                            {{ csrf_field() }}

                            <div class="btn-group btn-group-xs pull-right" role="group">
                                <a href="{{ route('admin.afiliados.show', $afiliado->id ) }}" class="btn btn-info" title="Ver afiliado">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.afiliados.edit', $afiliado->id ) }}" class="btn btn-primary" title="Editar afiliado">
                                    <i class="fa fa-pencil"></i>
                                </a>

                                <button type="submit" class="btn btn-danger" title="Remover afiliado" onclick="return confirm('Deseja realmete excluir o afiliado {{$afiliado->nome}}?')">
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


    @endsection

    @push('plugin-scripts')
    <script src="{{ asset('assets/plugins/dragula/dragula.min.js') }}"></script>
    @endpush

    @push('custom-scripts')
    <script src="{{ asset('assets/js/dragula.js') }}"></script>
    <script>
        function pesquisar(franqueado_id) {
            if (franqueado_id == 0) {
                window.location = window.location.origin+"/admin/afiliados";
            } else {
                window.location = window.location.origin+"/admin/afiliados/franqueado_id/" + franqueado_id;
            }
        }
        function reenviarEmail(usuario_id){
            $(".link-reenviar-send-"+usuario_id).hide();
            $(".link-reenviar-sending-"+usuario_id).show();
            $(".link-reenviar-danger-"+usuario_id).hide();
            $.getJSON({
                url: "/admin/ajax/reenviarEmailConfirmarcao/" + usuario_id, 
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
