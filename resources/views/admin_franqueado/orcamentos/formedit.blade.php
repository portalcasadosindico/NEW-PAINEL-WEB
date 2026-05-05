<?php

use App\Models\Franqueado;
use App\Models\FranqueadoRegiao;
use App\Uteis\Formatacao;
use App\Uteis\StatusOrcamento;
use App\Uteis\StatusPlano;

?>
<style>
    #sindico_modal .dataTables_filter {
        display: none;
    }

    .error-form-input {
        border-color: #CC0000 !important;
    }

    .success-form-input {
        border-color: #00CC00 !important;
    }

    .assinatura {
        border: 1px solid #fefefe;
        background-color: #fff;
        padding: 8px;
        width: contain;
        box-shadow: 0 0 10px 0 rgba(68, 114, 185, 0.68);
        -webkit-box-shadow: 0 2px 10px 0 rgba(68, 114, 185, 0.68);
        -moz-box-shadow: 0 0 10px 0 rgba(68, 114, 185, 0.68);
        -ms-box-shadow: 0 0 10px 0 rgba(68, 114, 185, 0.68);
    }

    .btn-upload-contrato,
    .hide-assinatura {
        display: none;
    }
</style>
<input type="hidden" value="{{$email_franqueado}}" name="email_franqueado" id="email_franqueado">
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    @if($orcamento->modo=="debug")
                    <label class="badge badge-danger mb-2">MODO TESTE</label>
                    <br>
                    @endif
                    <h6>Dados da solicitação</h6>
                </div>
                <div class="form-group">
                    <label for="urgente">Urgência</label>
                    <select class="form-control" id="urgente" name="urgente" required="true">
                        <option value="0" <?php if ($orcamento->urgente == 0) echo "selected"; ?>>Não é Urgente</option>
                        <option value="1" <?php if ($orcamento->urgente == 1) echo "selected"; ?>>Urgente</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="categoria_id">Categoria</label>
                    <select class="form-control" id="categoria_id" name="categoria_id" required="true">
                        <option style="display: none;" value=" old('categoria_id', optional($orcamento)->categoria_id ?: '') == '' ? 'selected' : '' }}" disabled selected>Selecione uma categoria</option>
                        @foreach ($categorias as $key => $categoria_linha)
                        <option <?php if ($categoria_linha->id == $orcamento->categoria_id) echo "selected"; ?> value="{{ $categoria_linha->id }}">
                            {{ $categoria_linha->nome }}
                        </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                    <label id="categoria_id-error" class="error mt-2 text-danger" for="categoria_id">{{ $message }}</label>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', optional($orcamento)->nome) }}" placeholder="Nome">
                    @error('nome')
                    <label id="nome-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao" cols="30" rows="10" placeholder="Descrição">{{ old('descricao', optional($orcamento)->descricao) }}</textarea>
                    @error('descricao')
                    <label id="descricao-error" class="error mt-2 text-danger" for="descricao">{{ $message }}</label>
                    @enderror
                </div>


            </div>
        </div>

        <div class="card" id="changestatus">
            <div class="card-body">
                <div class="form-group">
                    <h6 style="font-size: 21px;">Avaliação</h6>
                </div>

                @if($orcamento->avaliacao)
                <div class="mt-3">
                    <label class="badge badge-success">Avaliação</label>
                    <label class="badge badge-warning">NOTA: {{$orcamento->avaliacao}}</label>
                </div>
                @else
                <div class="mt-3">
                    <label class="badge badge-danger">Não foi avaliado</label>
                </div>
                @endif

                @if($orcamento->status_sindico==5)
                @if($orcamento->motivo_cancelamento)
                <div class="form-group">
                    <label class="badge badge-default">Observações finais</label>
                    <textarea class="form-control border-success" disabled rows="8" cols="30">{{$orcamento->motivo_cancelamento}}</textarea>
                </div>
                @else
                <div class="form-group">
                    <label class="badge badge-default">Observações finais</label>
                    <p>---</p>
                </div>
                @endif
                @endif
            </div>
        </div>

        <div class="card" id="changestatus">
            <div class="card-body">
                <div class="mt-3">
                    <p>Status Atual</p>
                    <label class="badge badge-success" style="font-size: 21px;"><?php echo StatusOrcamento::getLabel($orcamento->status); ?></label>
                </div>
                <div class="form-group">
                    <label for="status">Você deve informar o novo status</label>
                    <?php if ($orcamento->status == StatusOrcamento::$CANCELADO_PELO_ADMIN || $orcamento->status == StatusOrcamento::$CANCELADO_PELO_AFILIADO || $orcamento->status == StatusOrcamento::$CANCELADO_PELO_SINDICO) { ?>
                        <h6><?php echo StatusOrcamento::getLabel($orcamento->status); ?></h6>
                    <?php } else {  ?>
                        <?php echo StatusOrcamento::getSelectAllStatus('status', 'status', 'Selecione um status', $orcamento->status, "showDescricaoCancelamento(this.value)"); ?>
                        @error('status')
                        <label id="status-error" class="error mt-2 text-danger" for="status">{{ $message }}</label>
                        @enderror
                    <?php } ?>
                </div>

                <div class="mt-3 motivo-cancelamento" style="display: none;">
                    <label class="badge badge-default">Motivo do cancelamento</label>
                    <textarea class="form-control border-danger" name="motivo_cancelamento" rows="8" cols="30" placeholder="Descreva aqui o motivo do cancelamento">{{$orcamento->motivo_cancelamento}}</textarea>
                </div>

                <div class="form-group">
                    <label for="data_inicio_operacao">Data início do serviço</label>
                    <input type="text" class="form-control" data-inputmask-alias="99/99/9999" id="data_inicio_operacao" name="data_inicio_operacao" value="{{ old('data_inicio_operacao', optional($orcamento)->data_inicio_operacao) }}" placeholder="Data de início do serviço">
                    @error('data_inicio_operacao')
                    <label id="data_inicio_operacao-error" class="error mt-2 text-danger" for="data_inicio_operacao">{{ $message }}</label>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="data_fim_operacao">Data de previsão/conclusão do serviço</label>
                    <input type="text" class="form-control" data-inputmask-alias="99/99/9999" id="data_fim_operacao" name="data_fim_operacao" value="{{ old('data_fim_operacao', optional($orcamento)->data_fim_operacao) }}" placeholder="Data de conclusão do serviço">
                    @error('data_fim_operacao')
                    <label id="data_fim_operacao-error" class="error mt-2 text-danger" for="data_fim_operacao">{{ $message }}</label>
                    @enderror
                </div>

                <div style="display: none;" class="form-group">
                    <label for="status">Status Síndico</label>
                    <?php echo StatusOrcamento::getSelectAllSindico(
                        'status_sindico',
                        'status_sindico',
                        'Deixe o sistema escolher',
                        $orcamento->status_sindico
                    ); ?>
                    @error('status_sindico')
                    <label id="status_sindico-error" class="error mt-2 text-danger" for="status_sindico">{{ $message }}</label>
                    @enderror
                </div>
                <div style="display: none;" class="form-group">
                    <label for="status">Status Afiliado</label>
                    <?php echo StatusOrcamento::getSelectAllAfiliado(
                        'status_afiliado',
                        'status_afiliado',
                        'Deixe o sistema escolher',
                        $orcamento->status_afiliado
                    ); ?>
                    @error('status_afiliado')
                    <label id="status_afiliado-error" class="error mt-2 text-danger" for="status_afiliado">{{ $message }}</label>
                    @enderror
                </div>

            </div>
        </div>

    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <div>
                            <label>Síndico</label>
                            <h5 class="sindico_escolhido">
                                {{$orcamento->condominio()->withTrashed()->first()->sindico()->withTrashed()->first()->nome}}
                            </h5>
                            <label class="whats-link">{{$orcamento->condominio()->withTrashed()->first()->sindico()->withTrashed()->first()->telefone}}</label><br>
                            <!-- [HUBBOX FIX] Prevenção de quebra de tela em encadeamento profundo -->
                            <label class="mail-link">{{ optional($orcamento->condominio()->withTrashed()->first()->sindico()->withTrashed()->first()->usuarioApp()->withTrashed()->first())->email ?? 'E-mail não cadastrado' }}</label>
                            <div>
                                @if($formato_contrato_atual==2)
                                @if($assinaturas['sindico']['state'])
                                <label class="badge badge-success">Contrato asssinado (Autenticado pelo Franqueado)</label>
                                @else
                                <label class="badge badge-warning">O contrato ainda não foi assinado</label>
                                @endif
                                @elseif($formato_contrato_atual==1)

                                @if(isset($assinaturas["sindico"]["assinatura"]) &&
                                $assinaturas["sindico"]["assinatura"]->viewed)
                                <label class="badge badge-primary">Contrato visualizado em
                                    <?php echo Formatacao::data($assinaturas["sindico"]["assinatura"]->viewed, false, false); ?></label>
                                @else
                                <label class="badge badge-warning">Não visualizou o contrato</label>
                                @endif
                                @if(isset($assinaturas["sindico"]["assinatura"]) &&
                                $assinaturas["sindico"]["assinatura"]->signed)
                                <label class="badge badge-success" title="Autenticado pelo Autentique">Assinado em
                                    <?php echo Formatacao::data($assinaturas["sindico"]["assinatura"]->signed, false, false); ?></label>
                                @endif
                                @if(isset($assinaturas["sindico"]["assinatura"]) &&
                                $assinaturas["sindico"]["assinatura"]->rejected)
                                <label class="badge badge-danger">Contrato rejeitado em
                                    <?php echo Formatacao::data($assinaturas["sindico"]["assinatura"]->rejected, false, false); ?></label>
                                @endif

                                @else
                                <label class="badge badge-danger">Nenhum contrato foi enviado para assinatura</label>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">

                        <div id="container-select-condominio">
                            <label>Dados do condomínio</label>
                            <div id="dados_condominio" style="margin-top: 0px;">
                                <div class="row">
                                    <div class="col-7">
                                        <label style="font-size: 10px;margin-bottom: 0px;">Nome do
                                            condomínio</label>
                                        <h6 style="margin-top:0px;" id="nome-condominio">{{$orcamento->condominio->nome}}</h6>
                                    </div>
                                </div>


                                <label style="font-size: 10px; margin-top: 16px; margin-bottom: 0px;">Endereço</label>
                                <h6 style="margin-top:0px;" id="endereco-condominio">
                                    {{$orcamento->condominio()->withTrashed()->first()->endereco}}.
                                    {{isset($orcamento->condominio()->withTrashed()->first()->bairro()->withTrashed()->first()->first()->nome) ? $orcamento->condominio()->withTrashed()->first()->bairro()->withTrashed()->first()->nome : "Condomínio sem bairro"}},
                                    {{$orcamento->condominio()->withTrashed()->first()->bairro()->withTrashed()->first()->cidade()->withTrashed()->first()->nome}}
                                    -
                                    {{$orcamento->condominio()->withTrashed()->first()->bairro()->withTrashed()->first()->cidade()->withTrashed()->first()->estado()->withTrashed()->first()->uf}}
                                </h6>
                                <div class="row">
                                    <div class="col-6">
                                        <label style="font-size: 10px; margin-top: 16px; margin-bottom: 0px;">Franqueado</label>
                                        <h6 style="margin-top:0px;" id="franqueado-condominio">
                                            <?php
                                            $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $orcamento->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
                                            ?>
                                            @if($franqueadoRegiao)
                                            {{$franqueadoRegiao->franqueado->nome}}
                                            @else
                                            Sem franqueado
                                            @endif
                                        </h6>
                                    </div>
                                </div>

                            </div>

                        </div> <!-- Fim container-select-condominio -->

                    </div>
                </div>

            </div>
        </div>


        <div class="card" id="add_afiliado">
            <div class="card-body">

                @if(!$orcamento->afiliado()->withTrashed()->first())
                <div class="form-group">
                    <h6>Selecione o afiliado</h6>
                </div>
                @endif

                <div class="">
                    <label>Afiliado</label>
                    <input type="hidden" name="afiliado_id" id="afiliado_id" value="{{$orcamento->afiliado_id}}" />


                    @if(!$orcamento->afiliado()->withTrashed()->first())
                    <input type="hidden" value="" name="email_afiliado" id="email_afiliado">
                    <h5 class="afiliado_escolhido">Deixar em aberto</h5><br>
                    <a href="javascript:void(0)" title="Deixar em aberto" style="display: none;" class="btn btn-danger btn-remover-selecao-afiliado" onclick="selecionarAfiliado('','Deixar em aberto','', '')">X</a>
                    <button class="btn btn-primary" onclick="return false;" data-toggle="modal" data-target="#afiliado_modal">Escolher afiliado</button>
                    <button class="btn btn-success" onclick="return false;" data-toggle="modal" data-target="#afiliado_interessado_modal">Afiliados interessados</button>
                    @elseif($isEditable)
                    <h5 class="afiliado_escolhido">{{$orcamento->afiliado()->withTrashed()->first()->nome_fantasia ? $orcamento->afiliado()->withTrashed()->first()->nome_fantasia : $orcamento->afiliado()->withTrashed()->first()->razao_social }}</h5>
                    <label class="whats-link">{{$orcamento->afiliado()->withTrashed()->first()->telefone}}</label>
                    <br>
                    <label class="mail-link">{{$orcamento->afiliado()->withTrashed()->first()->usuarioApp()->withTrashed()->first()->email}}</label>
                    @if(isset($afiliadoRegiaoAfiliado->planoAssinaturaAfiliadoRegiao))
                    <div class="mt-2 mb-2">
                        <label class="badge badge-success">Plano:{{isset($afiliadoRegiaoAfiliado->planoAssinaturaAfiliadoRegiao->nome) ? $afiliadoRegiaoAfiliado->planoAssinaturaAfiliadoRegiao->nome : "Sem plano assinado"}}</label>
                        <label class="badge badge-info">Status:<?php echo isset($afiliadoRegiaoAfiliado->planoAssinaturaAfiliadoRegiao->statusPlano) ? StatusPlano::getLabel($afiliadoRegiaoAfiliado->planoAssinaturaAfiliadoRegiao->statusPlano) : 'Sem plano'; ?></label>
                    </div>
                    @endif
                    <input type="hidden" value="{{$orcamento->afiliado()->withTrashed()->first()->usuarioApp()->withTrashed()->first()->email}}" name="email_afiliado" id="email_afiliado">
                    <a href="javascript:void(0)" title="Remover afiliado" class="btn btn-danger btn-remover-selecao-afiliado" onclick="selecionarAfiliado('','Deixar em aberto','', '')">X</a>
                    <button class="btn btn-primary" onclick="return false;" data-toggle="modal" data-target="#afiliado_modal">Alterar afiliado</button>
                    <button class="btn btn-success" onclick="return false;" data-toggle="modal" data-target="#afiliado_interessado_modal">Afiliados interessados</button>
                    @else
                    <input type="hidden" value="{{$orcamento->afiliado()->withTrashed()->first()->usuarioApp()->withTrashed()->first()->email}}" name="email_afiliado" id="email_afiliado">
                    <h5 class="afiliado_escolhido">{{$orcamento->afiliado()->withTrashed()->first()->nome_fantasia ? $orcamento->afiliado()->withTrashed()->first()->nome_fantasia : $orcamento->afiliado()->withTrashed()->first()->razao_social}}</h5>
                    <label class="whats-link">{{$orcamento->afiliado()->withTrashed()->first()->telefone}}</label>
                    <br>
                    <label class="mail-link">{{$orcamento->afiliado()->withTrashed()->first()->usuarioApp()->withTrashed()->first()->email}}</label>
                    @if(isset($afiliadoRegiaoAfiliado->planoAssinaturaAfiliadoRegiao))
                    <div class="mt-2 mb-2">
                        <label class="badge badge-success">Plano:{{isset($afiliadoRegiaoAfiliado->planoAssinaturaAfiliadoRegiao->nome) ? $afiliadoRegiaoAfiliado->planoAssinaturaAfiliadoRegiao->nome : "Sem plano assinado"}}</label>
                        <label class="badge badge-info">Status:<?php echo isset($afiliadoRegiaoAfiliado->planoAssinaturaAfiliadoRegiao->statusPlano) ? StatusPlano::getLabel($afiliadoRegiaoAfiliado->planoAssinaturaAfiliadoRegiao->statusPlano) : 'Sem plano'; ?></label>
                    </div>
                    @endif
                    @endif



                    <!-- Modal -->
                    <div class="modal fade" id="afiliado_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Selecione um afiliado</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <div>
                                        <label for="sindico_id">Afiliados</label>
                                        <table data-page-length="25" id="dataTableExample" class="table dataTable no-footer" role="grid" aria-describedby="dataTableExample_info">
                                            <thead>
                                                <tr role="row">
                                                    <th class="sorting_asc" tabindex="0" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 153px;">Nome</th>
                                                    <th class="sorting" tabindex="1" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 244px;">Categoria</th>
                                                    <th class="sorting" tabindex="1" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 244px;">Região</th>
                                                    <th>Selecionar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($afiliados as $key => $afiliado)
                                                <tr role="row">
                                                    <td>
                                                        {{ $afiliado->nome_fantasia ? $afiliado->nome_fantasia : $afiliado->razao_social }}
                                                        <span style="display:none;"><?php echo
                                                                                    str_replace('-', '', Formatacao::chave($afiliado->razao_social . '' . $afiliado->nome_fantasia .
                                                                                        '' . $afiliado->telefone)); ?></span>
                                                        <br>{{ $afiliado->telefone }}
                                                    </td>
                                                    <td>
                                                        @foreach($afiliado->categorias as $afiliadoCategoria)
                                                        @if($afiliadoCategoria->categoria &&
                                                        $afiliadoCategoria->categoria->id==$orcamento->categoria_id)
                                                        <label class="badge badge-success">{{$afiliadoCategoria->categoria->nome}}</label>
                                                        @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <div style="max-height: 150px; overflow: auto;">
                                                            @foreach($afiliado->regioes as $afiliadoRegiao)
                                                            <?php
                                                            if ($afiliadoRegiao->regiao) {
                                                                $regiaoId = $afiliadoRegiao->regiao->id;
                                                                foreach ($franqueadoRegioes as $franqueadoRegiaoLinha) {
                                                                    if ($franqueadoRegiaoLinha->regiao_id == $regiaoId) {
                                                            ?>
                                                                        <p>Região: {{$afiliadoRegiao->regiao->nome}}</p>
                                                                        <label class="badge badge-success">Plano: {{isset($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->nome) ? $afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->nome : "Sem plano assinado"}}</label>
                                                                        <label class="badge badge-info">Status: <?php echo isset($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->statusPlano) ? StatusPlano::getLabel($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->statusPlano) : 'Sem plano'; ?></label>
                                                                        <hr>
                                                            <?php }
                                                                }
                                                            } ?>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    <!-- [HUBBOX FIX] Proteção contra usuarioApp nulo no modal de Afiliado -->
                                                    <td>
                                                        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-success" onclick="selecionarAfiliado('{{ $afiliado->id }}','{{ $afiliado->nome_fantasia ? rawurlencode($afiliado->nome_fantasia) : rawurlencode($afiliado->razao_social) }}','{{ $afiliado->telefone }}','{{ optional($afiliado->usuarioApp)->email ?? 'sem_email@sistema.local' }}')">Selecionar</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="afiliado_interessado_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Selecione um afiliado interessado
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div>
                                        <label for="afiliado_id">Afiliados interessados</label>
                                        <table data-page-length="25" id="dataTableExample" class="table dataTable no-footer" role="grid" aria-describedby="dataTableExample_info">
                                            <thead>
                                                <tr role="row">
                                                    <th class="sorting_asc" tabindex="0" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 153px;">Nome</th>
                                                    <th class="sorting" tabindex="1" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 244px;">Categoria</th>
                                                    <th class="sorting" tabindex="1" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 244px;">Região</th>
                                                    <th>Selecionar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($afiliadosInteressados as $key => $afiliado)
                                                <tr role="row">
                                                    <td>
                                                        <b>{{ $afiliado->nome_fantasia ? $afiliado->nome_fantasia : $afiliado->razao_social }}</b>
                                                        <span style="display:none;"><?php echo
                                                                                    str_replace('-', '', Formatacao::chave($afiliado->razao_social . $afiliado->nome_fantasia .
                                                                                        '' . $afiliado->telefone)); ?></span>
                                                        <br>{{ $afiliado->telefone }}

                                                        @if($orcamento->deleted_at!=null)
                                                        <label class="badge badge-danger" style="font-size: 12px;">Deletado via painel</label>
                                                        @elseif($orcamento->status==6 || $orcamento->status==7 ||
                                                        $orcamento->status==8 || $orcamento->status==9)
                                                        <label class="badge badge-danger" style="font-size: 12px;">Cancelado</label>
                                                        @elseif($orcamento->afiliado_id==$afiliado->id)
                                                        <label class="badge badge-success" style="font-size: 12px;">Afiliado Selecionado</label>
                                                        @elseif($orcamento->afiliado_id!=null)
                                                        <label class="badge badge-info" style="font-size: 12px;" title="{{$orcamento->afiliado()->withTrashed()->first()->nome_fantasia ? $orcamento->afiliado()->withTrashed()->first()->nome_fantasia : $orcamento->afiliado()->withTrashed()->first()->razao_social}} foi selecionado">Outro afiliado foi selecionado</label>
                                                        @elseif($afiliado->parecer->descartado_sindico==0 &&
                                                        $orcamento->afiliado_id==null)
                                                        <label class="badge badge-info" style="font-size: 12px;">O síndico aceita orçamento</label>
                                                        @elseif($afiliado->parecer->descartado_sindico==1 &&
                                                        $orcamento->afiliado_id==null)
                                                        <label class="badge badge-danger" style="font-size: 12px;">O síndico descartou</label>
                                                        @elseif($afiliado->parecer->descartado_sindico==-1 &&
                                                        $orcamento->afiliado_id==null && $orcamento->status==1)
                                                        <label class="badge badge-dark" style="font-size: 12px;">Nenhuma reação do síndico</label>
                                                        @elseif($afiliado->parecer->descartado_sindico==-1 &&
                                                        $orcamento->afiliado_id==null && $orcamento->status==2)
                                                        <label class="badge badge-danger" style="font-size: 12px;">O síndico descartou</label>
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @foreach($afiliado->categorias as $afiliadoCategoria)
                                                        @if($afiliadoCategoria->categoria &&
                                                        $afiliadoCategoria->categoria->id==$orcamento->categoria_id)
                                                        <label class="badge badge-success">{{$afiliadoCategoria->categoria->nome}}</label>
                                                        @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <div style="max-height: 150px; overflow: auto;">
                                                            @foreach($afiliado->regioes as $afiliadoRegiao)
                                                            <?php
                                                            if ($afiliadoRegiao->regiao) {
                                                                $regiaoId = $afiliadoRegiao->regiao->id;
                                                            } else {
                                                                $regiaoId = null;
                                                            }


                                                            foreach ($franqueadoRegioes as $franqueadoRegiaoLinha) {
                                                                if ($franqueadoRegiaoLinha->regiao_id == $regiaoId) {
                                                            ?>
                                                                    <p>Região: {{$afiliadoRegiao->regiao->nome}}</p>
                                                                    <label class="badge badge-success">Plano: {{isset($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->nome) ? $afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->nome : "Sem plano assinado"}}</label>
                                                                    <label class="badge badge-info">Status: <?php echo isset($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->statusPlano) ? StatusPlano::getLabel($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->statusPlano) : 'Sem plano'; ?></label>
                                                                    <hr>
                                                            <?php }
                                                            } ?>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    <!-- [HUBBOX FIX] Proteção contra usuarioApp nulo no modal de Afiliado Interessado -->
                                                    <td>
                                                        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-success" onclick="selecionarAfiliado('{{ $afiliado->id }}','{{ $afiliado->nome_fantasia ? rawurlencode($afiliado->nome_fantasia) : rawurlencode($afiliado->razao_social) }}','{{ $afiliado->telefone }}','{{ optional($afiliado->usuarioApp)->email ?? 'sem_email@sistema.local' }}')">Selecionar</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="mt-3">
                    @if($formato_contrato_atual==2)
                    @if($assinaturas['afiliado']['state'])
                    <label class="badge badge-success">Contrato assinado (Autenticado pelo Franqueado)</label>
                    @else
                    <label class="badge badge-warning">O Contrato ainda não foi assinado</label>
                    @endif
                    @elseif($formato_contrato_atual==1)

                    @if(isset($assinaturas["afiliado"]["assinatura"]) && $assinaturas["afiliado"]["assinatura"]->viewed)
                    <label class="badge badge-primary">Contrato visualizado em <?php echo Formatacao::data($assinaturas["afiliado"]["assinatura"]->viewed, false, false); ?></label>
                    @else
                    <label class="badge badge-warning">Contrato não visualizado</label>
                    @endif
                    @if(isset($assinaturas["afiliado"]["assinatura"]) && $assinaturas["afiliado"]["assinatura"]->signed)
                    <label class="badge badge-success" title="Autenticado pelo Autentique">Contrato Assinado em <?php echo Formatacao::data($assinaturas["afiliado"]["assinatura"]->signed, false, false); ?></label>
                    @endif
                    @if(isset($assinaturas["afiliado"]["assinatura"]) &&
                    $assinaturas["afiliado"]["assinatura"]->rejected)
                    <label class="badge badge-danger">Contrato rejeitado em
                        <?php echo Formatacao::data($assinaturas["afiliado"]["assinatura"]->rejected, false, false); ?></label>
                    @endif

                    @else
                    <label class="badge badge-danger">Nenhum contrato foi enviado para assinatura</label>
                    @endif
                </div>


            </div>

        </div>


        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    @if($formato_contrato_atual==4)
                    <h6 id="upload">Selecione o contrato de prestação de serviço</h6>
                    @elseif($formato_contrato_atual==1)
                    <h6>Veja o contrato autenticado via Autentique</h6>
                    @elseif($formato_contrato_atual==2)
                    <h6>Veja o contrato autenticado via Franqueado</h6>
                    @endif
                </div>

                @if($orcamento->formato_contrato_atual==2)
                <p>{{$orcamento->contrato_assinado}}</p>
                <a href="{{$orcamento->contrato_assinado ? $orcamento->contrato_assinado : "https://admin2.casadosindico.srv.br/storage/".$orcamento->contrato}}" class="btn btn-success" target="_blank">Ver contrato</a>
                @elseif($orcamento->formato_contrato_atual==1)
                <a href="{{$orcamento->contrato_original ? $orcamento->contrato_original : "https://admin2.casadosindico.srv.br/storage/".$orcamento->contrato}}" class="btn btn-secondary" target="_blank">Ver contrato</a>
                <a href="{{$orcamento->contrato_assinado ? $orcamento->contrato_assinado : "https://admin2.casadosindico.srv.br/storage/".$orcamento->contrato}}" class="btn btn-success" target="_blank">Ver contrato assinado</a>
                @else
                <label for="" class="btn btn-secondary btn-upload-contrato">Upload do contrato</label>
                <label class="badge badge-primary arquivo-selecionado"></label>
                <label class="badge badge-warning label-upload-contrato-not" style="font-size: 14px; white-space: pre-wrap; text-align: left;">Para enviar um contrato, você deve selecionar um condomínio e um afiliado.</label>
                @endif

                {{-- @if($formato_contrato_atual==2)
                <a href="{{Storage::url($orcamento->contrato_assinado ? $orcamento->contrato_assinado : $orcamento->contrato)}}"
                class="btn btn-success" target="_blank">Ver contrato</a>
                @elseif($formato_contrato_atual==1)
                <a href="https://admin2.casadosindico.srv.br/storage/{{$orcamento->contrato_original ? $orcamento->contrato_original : $orcamento->contrato}}" class="btn btn-secondary" target="_blank">Ver contrato</a>
                <a href="https://admin2.casadosindico.srv.br/storage/{{$orcamento->contrato_assinado ? $orcamento->contrato_assinado : $orcamento->contrato}}" class="btn btn-success" target="_blank">Ver contrato assinado</a>
                @else
                <label for="" class="btn btn-secondary btn-upload-contrato">Upload do contrato</label>
                <label class="badge badge-primary arquivo-selecionado"></label>
                <label class="badge badge-warning label-upload-contrato-not" style="font-size: 14px; white-space: pre-wrap; text-align: left;">Para enviar um contrato, você deve selecionar um condomínio e um afiliado.</label>
                @endif --}}


                @if($formato_contrato_atual==4)
                <div class="form-group" style="display: none;">
                    <label>Selecione um arquivo no formato PDF</label>
                    <input type="file" class="form-control" onchange="mostrarNomeArquivo(this)" accept="application/pdf" name="contrato" id="contrato" />
                </div>
                @endif

                @if($formato_contrato_atual==4)
                <div class="estado-assinaturas" style="display: none;">
                    <a href="javascript:void(0)" class="btn btn-danger" title="Remover arquivo" onclick="removerContrato()">X</a>
                    <h5 style="margin-top: 20px; margin-bottom: 10px;" class="hide-assinatura">Informe o estado das assinaturas deste contrato</h5>
                    <select onchange="showAssinaturas(this.value)" class="hide-assinatura" name="formato_contrato_atual" id="formato_contrato_atual" style="margin-bottom: 24px; font-weight: bold;">
                        <option <?php if ($orcamento->formato_contrato_atual == 4) echo "selected"; ?> value="4">Selecione
                            uma opção</option>
                        @if($possuiTokenAutentique)
                        <option value="1" <?php if ($orcamento->formato_contrato_atual == 1) echo "selected"; ?>>Colher
                            assinaturas pelo Autentique</option>
                        @else
                        <option value="" class="disabled" disabled>Colher assinaturas pelo Autentique (Sua franquia está
                            sem o token do autentique)</option>
                        @endif
                        <option <?php if ($orcamento->formato_contrato_atual == 2) echo "selected"; ?> value="2">Este
                            contrato já foi assinado por todos</option>
                    </select>
                </div>
                @else
                <input type="hidden" name="formato_contrato_atual" value="{{$orcamento->formato_contrato_atual}}">
                @endif


                <div style="width: 100%; display: none;" class="container-assinaturas">

                    <div class="form-group assinatura assinatura-sindico">
                        <label class="display-block" for="email_testemunha1">E-mail da testemunha 1</label>
                        <input type="email" name="email_testemunha1" placeholder="Digite aqui..." id="email_testemunha1" class="form-control">
                    </div>

                    <div class="form-group assinatura assinatura-sindico">
                        <label class="display-block" for="email_testemunha2">E-mail da testemunha 2</label>
                        <input type="email" name="email_testemunha2" placeholder="Digite aqui..." id="email_testemunha2" class="form-control">
                    </div>

                </div>


            </div>
        </div>


    </div>
</div>

<input type="hidden" name="franqueado_id" value="{{$franqueado_id}}" />

<?php if ($orcamento->status == StatusOrcamento::$CANCELADO_PELO_ADMIN) { ?>
    <button class="btn btn-light" name="cancel">Voltar</button>
<?php } else {  ?>
    <button onclick="validarForm()" type="button" class="btn btn-primary mr-2 hide-btn-send">Salvar</button>
    <a href="{{ route('admin_franqueado.orcamentos.index') }}" class="btn btn-light">Cancelar</a>
<?php } ?>
<script>
    var condominios = [];

    function showDadosCondominio(condominio_id) {
        for (var i = 0; i < condominios.length; i++) {
            var condominio = condominios[i];
            if (condominio_id == condominio.id) {
                escolheuCondominio = true;
                $("#dados_condominio").show();
                $("#nome-condominio").html(condominio.nome);
                $("#endereco-condominio").html(condominio.endereco);
                $("#franqueado-condominio").html(condominio.franqueado_name);
                $("#regiao-condominio").html(condominio.regiao_name);
                if (condominio.regiao_status == 0) $("#escolha-franqueado-bairro").show();
                else $("#escolha-franqueado-bairro").hide();
                if (escolheuCondominio && escolheuAfiliado) {
                    $(".btn-upload-contrato").attr("for", "contrato");
                    $(".btn-upload-contrato").addClass("btn-warning");
                    $(".btn-upload-contrato").removeClass("btn-secondary");
                    $(".btn-upload-contrato").show();
                    $(".label-upload-contrato-not").hide();
                }
                return;
            }
        }
        $("#dados_condominio").hide();
    }

    function selecionarSindico(id, nome, telefone) {
        $("#sindico_id").val(id);
        $(".sindico_escolhido").html(nome + " - " + telefone);
        carregarCondominios(id);
    }

    var escolheuCondominio = true;
    var escolheuAfiliado = <?php if ($orcamento->afiliado_id > 0) echo "true";
                            else echo "false"; ?>;
    if (escolheuCondominio && escolheuAfiliado) {
        $(".btn-upload-contrato").attr("for", "contrato");
        $(".btn-upload-contrato").addClass("btn-warning");
        $(".btn-upload-contrato").removeClass("btn-secondary");
        $(".btn-upload-contrato").show();
        $(".label-upload-contrato-not").hide();
        $(".hide-assinatura").show();
    }

    function selecionarAfiliado(id, nome, telefone, email) {
        if (id != "") {
            escolheuAfiliado = true;
            $("#email_afiliado").val(email);
            $(".btn-remover-selecao-afiliado").show();
            if (escolheuCondominio && escolheuAfiliado) {
                $(".btn-upload-contrato").attr("for", "contrato");
                $(".btn-upload-contrato").addClass("btn-warning");
                $(".btn-upload-contrato").removeClass("btn-secondary");
                $(".btn-upload-contrato").show();
                $(".label-upload-contrato-not").hide();
                $(".hide-assinatura").show();
            }
        } else {
            escolheuAfiliado = false;
            $(".btn-remover-selecao-afiliado").hide();

            $(".btn-upload-contrato").attr("for", "");
            $(".btn-upload-contrato").addClass("btn-secondary");
            $(".btn-upload-contrato").removeClass("btn-warning");
            $(".btn-upload-contrato").hide();
            $(".label-upload-contrato-not").show();
            $(".hide-assinatura").hide();
            $("#email_afiliado").val("");
        }
        $("#afiliado_id").val(id);
        $(".afiliado_escolhido").html(decodeURIComponent(nome) + " - " + telefone);
    }

    function carregarCondominios(sindico_id) {
        var _token = $('input[name="_token"]').val();
        $("#condominio_id").html("<option value=''>Carregando...</option>");
        $("#container-select-condominio").show();

        $.getJSON({
            url: "<?php echo getenv("APP_URL"); ?>/admin_franqueado/sindicos/" + sindico_id + "/condominios",
            method: "GET",
            data: {
                _token: _token
            },
            success: function(data) {
                $("#condominio_id").html("<option value=''>Selecione um condomínio</option>");
                condominios = data.dados;
                var qtd_condominios = condominios.length;
                $(".qtd_condominios").html(qtd_condominios);
                for (var i = 0; i < qtd_condominios; i++) {
                    var condominio = condominios[i];
                    $("#condominio_id").append("<option value='" + condominio.id + "'>" + condominio.nome +
                        ". Bairro " + condominio.bairro_name + "</option>");
                }

                if (qtd_condominios == 1) {
                    $("#condominio_id").val(condominios[0].id);
                    showDadosCondominio(condominios[0].id);
                }
            },
            error: function(error) {
                console.log(error);

            }


        });
    }

    function showError(id) {
        $("#" + id + "-error").show();
        $("#" + id).focus();
        $("#" + id).removeClass("success-form-input");
        $("#" + id).addClass("error-form-input");
    }

    function showSucesso(id) {
        $("#" + id).removeClass("error-form-input");
        $("#" + id).addClass("success-form-input");
    }

    function validarCondominio() {
        var valida = true;

        if (numero == "") {
            valida = false;
            showError("numerof");
        } else {
            showSucesso("numerof");
        }
        if (rua.length < 2) {
            valida = false;
            showError("ruaf");
        } else {
            showSucesso("ruaf");
        }
        if (bairro.length < 2) {
            valida = false;
            showError("bairrof");
        } else {
            showSucesso("bairrof");
        }
        if (cidade.length < 2) {
            valida = false;
            showError("cidadef");
        } else {
            showSucesso("cidadef");
        }
        if (estado.length < 2) {
            valida = false;
            showError("estadof");
        } else {
            showSucesso("estadof");
        }
        if (cep.length < 8) {
            valida = false;
            showError("cep");
        } else {
            showSucesso("cep");
        }
        if (nome == "") {
            valida = false;
            showError("nome_condominio");
        } else {
            showSucesso("nome_condominio");
        }
        return valida;
    }

    sindico_id = $("#sindico_id").val();
    nome = $("#nome_condominio").val();
    estado = $("#estadof").val();
    cidade = $("#cidadef").val();
    bairro = $("#bairrof").val();
    numero = $("#numerof").val();
    rua = $("#ruaf").val();
    cep = $("#cep").val();
    cnpj = $("#cnpj").val();

    function cadastrarCondominio() {
        $(".error").hide();
        sindico_id = $("#sindico_id").val();
        nome = $("#nome_condominio").val();
        estado = $("#estadof").val();
        cidade = $("#cidadef").val();
        bairro = $("#bairrof").val();
        numero = $("#numerof").val();
        rua = $("#ruaf").val();
        cep = $("#cep").val();
        cnpj = $("#cnpj").val();

        if (validarCondominio() == true) {
            $("#btn-cadastrar-condominio").attr("disabled", "disabled");
            $("#btn-cadastrar-condominio").html("Salvando...");
            var _token = $('input[name="_token"]').val();
            $.getJSON({
                url: "<?php echo getenv("APP_URL"); ?>/admin/condominios/cadastrar_modal",
                method: "POST",
                data: {
                    _token: _token,
                    sindico_id: sindico_id,
                    nome_condominio: nome,
                    estado: estado,
                    cidade: cidade,
                    bairro: bairro,
                    numero: numero,
                    rua: rua,
                    cep: cep,
                    cnpj: cnpj
                },
                success: function(data) {
                    condominios.push(data);
                    $("#condominio_id").append("<option value='" + data.id + "'>" + data.nome +
                        ". Bairro " + data.bairro_name + "</option>");
                    $("#condominio_id").val(data.id);
                    showDadosCondominio(data.id);

                    var qtd_condominios = condominios.length;
                    $(".qtd_condominios").html(qtd_condominios);

                    $("#condominio_modal_novo").modal("hide");

                    $("#btn-cadastrar-condominio").removeAttr("disabled");
                    $("#btn-cadastrar-condominio").html("Cadastrar");

                    $("#nome_condominio").val("");
                    $("#estadof").val("");
                    $("#cidadef").val("");
                    $("#bairrof").val("");
                    $("#numerof").val("");
                    $("#ruaf").val("");
                    $("#cep").val("");
                    $("#cnpj").val("");
                    $("#condominio_modal_novo input").removeClass("success-form-input");
                    $("#condominio_modal_novo input").removeClass("error-form-input");
                },
                error: function() {
                    $("#btn-cadastrar-condominio").removeAttr("disabled");
                    $("#btn-cadastrar-condominio").html("Cadastrar");
                    $("#condominio_modal_novo input").removeClass("success-form-input");
                    $("#condominio_modal_novo input").removeClass("error-form-input");
                    alert("Tente novamente.")
                }
            });
        }
    }

    <?php
    if ($sindico_param_id) {
    ?>
        $(document).ready(function() {
            selecionarSindico("{{ $sindicop->id }}", "{{ $sindicop->nome }}",
                "{{ $sindicop->telefone }}");
        });
    <?php
    } ?>





    function validarSindico() {
        var valida = true;


        if (senha.length < 2) {
            valida = false;
            showError("senha_sindico");
        } else {
            showSucesso("senha_sindico");
        }
        if (email.length < 2) {
            valida = false;
            showError("email_sindico");
        } else {
            showSucesso("email_sindico");
        }
        if (telefone.length < 2) {
            valida = false;
            showError("telefone_sindico");
        } else {
            showSucesso("telefone_sindico");
        }
        /*if(numero_documento.length<2){
          valida = false;
          showError("numero_documento_sindico");
        }  else {
          showSucesso("numero_documento_sindico");
        }*/
        if (cpf.length < 2) {
            valida = false;
            showError("cpf_sindico");
        } else {
            showSucesso("cpf_sindico");
        }
        if (nome == "") {
            valida = false;
            showError("nome_sindico");
        } else {
            showSucesso("nome_sindico");
        }


        return valida;
    }

    var nome = $("#nome_sindico").val();
    var cpf = $("#cpf_sindico").val();
    var numero_documento = $("#numero_documento_sindico").val();
    var telefone = $("#telefone_sindico").val();
    var email = $("#email_sindico").val();
    var senha = $("#senha_sindico").val();

    function cadastrarSindico() {
        $(".error").hide();
        nome = $("#nome_sindico").val();
        cpf = $("#cpf_sindico").val();
        numero_documento = $("#numero_documento_sindico").val();
        telefone = $("#telefone_sindico").val();
        email = $("#email_sindico").val();
        senha = $("#senha_sindico").val();

        if (validarSindico() == true) {
            $("#btn-cadastrar-sindico").attr("disabled", "disabled");
            $("#btn-cadastrar-sindico").html("Salvando...");
            var _token = $('input[name="_token"]').val();
            $.getJSON({
                url: "<?php echo getenv("APP_URL"); ?>/admin/sindicos/cadastrar_modal",
                method: "POST",
                data: {
                    _token: _token,
                    nome_sindico: nome,
                    cpf: cpf,
                    numero_documento: numero_documento,
                    telefone: telefone,
                    email: email,
                    senha: senha
                },
                success: function(data) {
                    selecionarSindico(data.id, data.nome, data.telefone);

                    $("#sindico_modal_novo").modal("hide");

                    $("#btn-cadastrar-condominio").removeAttr("disabled");
                    $("#btn-cadastrar-condominio").html("Cadastrar");

                    $("#nome_sindico").val("");
                    $("#cpf_sindico").val("");
                    $("#numero_documento_sindico").val("");
                    $("#telefone_sindico").val("");
                    $("#email_sindico").val("");
                    $("#senha_sindico").val("");

                    $("#sindico_modal_novo input").removeClass("success-form-input");
                    $("#sindico_modal_novo input").removeClass("error-form-input");
                },
                error: function() {
                    $("#btn-cadastrar-condominio").removeAttr("disabled");
                    $("#btn-cadastrar-condominio").html("Cadastrar");
                    $("#sindico_modal_novo input").removeClass("success-form-input");
                    $("#sindico_modal_novo input").removeClass("error-form-input");
                    alert("Tente novamente.")
                }
            });
        }
    }


    $(document).ready(function() {
        $('#sindico_modal').on('shown.bs.modal', function(event) {
            $('#pesquisa-sindico-modal').trigger('focus')
        });

        $('#condominio_modal_novo').on('shown.bs.modal', function(event) {
            $('#nome_condominio').trigger('focus')
        });

    });
    var termoAnterior = null;

    function pesquisa(e) {
        var termos = $("#pesquisa-sindico-modal").val();
        var tecla = event.which || event.keyCode;
        if (tecla == 13) {
            dataTable.search($("#pesquisa-sindico-modal").val()).draw();
            return false;
        }
        return true;
    }

    function showAssinaturas(tipo) {
        console.log(tipo)
        if (tipo == 1) {
            $(".container-assinaturas").show();
        } else if (tipo == 2) {
            $(".container-assinaturas").hide();
        } else if (tipo == 4) {
            $(".container-assinaturas").hide();
        } else if (tipo == 3) {
            $(".container-assinaturas").hide();
        }
    }


    function validarForm() {
        var tipo_assinatura = $("#formato_contrato_atual").val();
        if (tipo_assinatura == 1) {
            var email_testemunha1 = $("#email_testemunha1").val();
            var email_testemunha2 = $("#email_testemunha2").val();

            var email_afiliado = $("#email_afiliado").val();
            var email_sindico = $("#email_sindico_comparar").val();
            var email_franqueado = $("#email_franqueado").val();


            if (email_testemunha1 == "") {
                alert("Informe o e-mail da testemunha 1")
            } else if (email_testemunha2 == "") {
                alert("Informe o e-mail da testemunha 2")
            } else if (email_testemunha1 == email_testemunha2) {
                alert("Operação ilegal. O e-mail das testemunhas devem ser diferentes.")
            } else if (email_testemunha1 == email_afiliado || email_testemunha2 == email_afiliado) {
                alert("Operação ilegal. O afiliado não pode ser testemunha do seu próprio contrato.")
            } else if (email_testemunha1 == email_sindico || email_testemunha2 == email_sindico) {
                alert("Operação ilegal. O síndico não pode ser testemunha do seu próprio contrato.")
            } else if (email_testemunha1 == email_franqueado || email_testemunha2 == email_franqueado) {
                alert("Operação ilegal. O franqueado não pode ser testemunha do seu próprio contrato.")
            } else {
                $("#form-edit-orcamento").submit();
            }
        } else {
            $("#form-edit-orcamento").submit();
        }


    }


    function showDescricaoCancelamento(status) {

        let data = new Date();
        let dia = ("00" + data.getDate()).slice(-2)
        let mes = ("00" + (data.getMonth() + 1)).slice(-2)
        let ano = data.getFullYear()

        let dataCompleta = dia + "/" + mes + "/" + ano

        if (status == 4 && $("#data_inicio_operacao").val() == "") {
            $("#data_inicio_operacao").val(dataCompleta)
        } else if (status == 5 && $("#data_fim_operacao").val() == "") {
            $("#data_fim_operacao").val(dataCompleta)
        }

        $(".hide-btn-send").show(300);
        if (status == <?php echo StatusOrcamento::$CANCELADO_PELO_FRANQUEADO ?> || status == <?php echo StatusOrcamento::$CANCELADO_PELO_ADMIN ?> || status == <?php echo StatusOrcamento::$CANCELADO_PELO_AFILIADO ?> || status == <?php echo StatusOrcamento::$CANCELADO_PELO_SINDICO ?>) {
            $(".motivo-cancelamento").show(300);
            $(".hide-status").hide(300);
        } else {
            $(".motivo-cancelamento").hide(300);
            $(".motivo-cancelamento textarea").val("");
            $(".hide-status").show(300);

            if (status == <?php echo StatusOrcamento::$CONTRATO_ASSINADO ?> || status == <?php echo StatusOrcamento::$EM_EXECUCAO ?> || status == <?php echo StatusOrcamento::$FINALIZADO ?>) {

                if ("<?php echo $orcamento->formato_contrato_atual; ?>" == 1 && "<?php echo $assinaturas['sindico']['state']; ?>" == false && "<?php echo $assinaturas['afiliado']['state']; ?>" == false) {
                    //alert("O contrato deve estar assinado por todos");
                    //$(".hide-btn-send").hide(300);
                }

            }

        }
    }
    showDescricaoCancelamento(<?php echo $orcamento->status; ?>);

    function selecionarCategoria(categoria_id) {
        window.location = "<?php echo getenv("APP_URL"); ?>/admin_franqueado/orcamentos/<?php echo $orcamento->id ?>/edit/" + categoria_id
    }

    function mostrarNomeArquivo(obj) {
        $(".arquivo-selecionado").html(obj.value)
    }


    function mostrarNomeArquivo(obj) {
        $(".arquivo-selecionado").html(obj.value)
        $(".estado-assinaturas").show(300)
    }

    function removerContrato() {
        $("#contrato").val("")
        $(".arquivo-selecionado").html("")
        $(".estado-assinaturas").hide(300)
        $("#formato_contrato_atual").val(4)
    }
</script>
