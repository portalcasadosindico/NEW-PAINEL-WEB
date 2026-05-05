<?php

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

    .btn-upload-contrato,
    .hide-assinatura {
        display: none;
    }
</style>

<?php if (isset($categoria->id) && $categoria->id > 0) { ?>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <h6>Dados da solicitação</h6>
                    </div>
                    <div class="form-group">
                        <label for="urgente">Urgência</label>
                        <select class="form-control" id="urgente" name="urgente" required="true">
                            <option value="0">Não é Urgente</option>
                            <option value="1">Urgente</option>
                        </select>
                        @error('categoria_id')
                        <label id="categoria_id-error" class="error mt-2 text-danger" for="categoria_id">{{ $message }}</label>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="categoria_id">Categoria</label>
                        <select onchange="selecionarCategoria(this.value)" class="form-control" id="categoria_id" name="categoria_id" required="true">
                            <option style="display: none;" value=" old('categoria_id', optional($orcamento)->categoria_id ?: '') == '' ? 'selected' : '' }}" disabled selected>Selecione uma categoria</option>
                            @foreach ($categorias as $key => $categoria_nome)
                            @php
                                // [HUBBOX FIX] Compatibiliza categoria como objeto (Eloquent) ou texto (array/pluck)
                                $categoriaOptionId = is_object($categoria_nome) ? $categoria_nome->id : $key;
                                $categoriaOptionNome = is_object($categoria_nome) ? $categoria_nome->nome : $categoria_nome;
                            @endphp
                            <option <?php if ($categoriaOptionId == $categoria->id) echo "selected"; ?> value="{{ $categoriaOptionId }}">
                                {{ $categoriaOptionNome }}
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

            <div class="card" style="display: none;">
                <div class="card-body">
                    <div class="form-group">
                        <h6>Dados de status</h6>
                    </div>
                    <div class="form-group">
                        <label for="status">Status Oficial</label>
                        <?php echo StatusOrcamento::getSelectAllStatus('status', 'status', 'Deixe o sistema
                        escolher'); ?>
                        @error('status')
                        <label id="status-error" class="error mt-2 text-danger" for="status">{{ $message }}</label>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Status Síndico</label>
                        <?php echo StatusOrcamento::getSelectAllSindico(
                            'status_sindico',
                            'status_sindico',
                            'Deixe o sistema escolher'
                        ); ?>
                        @error('status_sindico')
                        <label id="status_sindico-error" class="error mt-2 text-danger" for="status_sindico">{{ $message }}</label>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Status Afiliado</label>
                        <?php echo StatusOrcamento::getSelectAllAfiliado(
                            'status_afiliado',
                            'status_afiliado',
                            'Deixe o sistema escolher'
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
                    <div class="form-group">
                        <h6>Selecione o síndico e o seu condomínio</h6>
                    </div>
                    <div class="form-group">
                        <label>Síndico</label>
                        <input type="hidden" name="sindico_id" id="sindico_id">
                        <h5 class="sindico_escolhido">Nenhum síndico selecionado</h5><br>
                        <button class="btn btn-primary" onclick="return false;" data-toggle="modal" data-target="#sindico_modal">Escolher síndico</button>
                        <button class="btn btn-info" onclick="return false;" data-toggle="modal" data-target="#sindico_modal_novo">Novo síndico</button>

                        <!-- Modal -->
                        <div class="modal fade" id="sindico_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Selecione um síndico</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label for="sindico_id">Pesquisa (Pressione a tecla <b>Enter</b> para
                                                pesquisar)</label>
                                            <input type="text" onkeypress="return pesquisa(event);" class="form-control" id="pesquisa-sindico-modal" placeholder="Pesquise pelo nome do síndico, franqueado ou região">
                                        </div>

                                        <div class="form-group">
                                            <label for="sindico_id">Síndicos</label>
                                            <table data-page-length="25" id="dataTableExample" class="table dataTable no-footer" role="grid" aria-describedby="dataTableExample_info">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting_asc" tabindex="0" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 153px;">Nome</th>
                                                        <th class="sorting" tabindex="1" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 244px;">Telefone</th>
                                                        <th>Selecionar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($sindicos as $key => $sindico)
                                                    <tr role="row">
                                                        <td>
                                                            {{ $sindico->nome }}
                                                            <span style="display:none;"><?php echo
                                                                                        str_replace('-', '', Formatacao::chave($sindico->nome .
                                                                                            '' . $sindico->telefone)); ?></span>
                                                        </td>
                                                        <td>{{ $sindico->telefone }}</td>
                                                        <td><a href="javascript:void(0)" data-dismiss="modal" class="btn btn-success" onclick="selecionarSindico('{{ $sindico->id }}','{{ $sindico->nome }}','{{ $sindico->telefone }}')">Selecionar</a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Modal -->
                        <div class="modal fade" id="sindico_modal_novo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Novo síndico</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        @include ('admin.sindicos.form2', [
                                        'sindico' => null,
                                        ])

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button type="button" id="btn-cadastrar-sindico" class="btn btn-primary" onclick="cadastrarSindico();">Cadastrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>


                    <div style="display: none;" id="container-select-condominio" class="form-group">
                        <label>Selecione o Condomínio (<label class="qtd_condominios">..</label>)</label>
                        <select onchange="showDadosCondominio(this.value)" class="form-control" id="condominio_id" name="condominio_id" required="true">
                        </select>
                        <div style="display: none;" id="dados_condominio">
                            <div class="row">
                                <div class="col-7">
                                    <label style="font-size: 10px; margin-top: 16px; margin-bottom: 0px;">Nome do
                                        condomínio</label>
                                    <h6 style="margin-top:0px;" id="nome-condominio"></h6>
                                </div>
                                <div class="col-5">
                                    <label style="font-size: 10px; margin-top: 16px; margin-bottom: 0px;">Região</label>
                                    <h6 style="margin-top:0px;" id="regiao-condominio"></h6>
                                </div>
                            </div>


                            <label style="font-size: 10px; margin-top: 16px; margin-bottom: 0px;">Endereço</label>
                            <h6 style="margin-top:0px;" id="endereco-condominio"></h6>

                            <div class="row">
                                <div class="col-6">
                                    <label style="font-size: 10px; margin-top: 16px; margin-bottom: 0px;">Franqueado</label>
                                    <h6 style="margin-top:0px;" id="franqueado-condominio"></h6>
                                </div>
                                <div class="col-6" id="escolha-franqueado-bairro" style="display: none;">
                                    <label style="font-size: 10px; margin-top: 16px; margin-bottom: 0px;">Escolha um
                                        franqueado para este bairro</b></label>
                                    <select id="franqueado_id">
                                        <option value="">Selecione o franqueado</option>
                                        <?php foreach ($franqueados as $franq) { ?>
                                            <option value="<?php echo $franq['id']; ?>"><?php echo $franq['nome']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>


                        </div>

                        <br>
                        <button class="btn btn-info" onclick="return false;" data-toggle="modal" data-target="#condominio_modal_novo">Novo condomínio</button>

                        <!-- Modal -->
                        <div class="modal fade" id="condominio_modal_novo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelCondominio" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabelCondominio">Novo condomínio</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        @include ('condominios.form_modal', [
                                        'condominio' => null,
                                        ])

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button id="btn-cadastrar-condominio" type="button" onclick="cadastrarCondominio()" class="btn btn-primary">Cadastrar</button>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Fim modal -->
                    </div> <!-- Fim container-select-condominio -->


                </div>
            </div>


            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <h6>Selecione o afiliado</h6>
                    </div>
                    <div class="form-group">
                        <label>Afiliado</label>
                        <input type="hidden" name="afiliado_id" id="afiliado_id" />
                        <h5 class="afiliado_escolhido">Deixar em aberto</h5><br>
                        <a href="javascript:void(0)" title="Deixar em aberto" style="display: none;" class="btn btn-danger btn-remover-selecao-afiliado" onclick="selecionarAfiliado('','Deixar em aberto','')">X</a>
                        <button class="btn btn-primary" onclick="return false;" data-toggle="modal" data-target="#afiliado_modal">Escolher afiliado</button>

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

                                        <div class="form-group">
                                            <label for="afiliado_id">Pesquisa (Pressione a tecla <b>Enter</b> para
                                                pesquisar)</label>
                                            <input type="text" onkeypress="return pesquisa2(event);" class="form-control" id="pesquisa-afiliado-modal" placeholder="Pesquise pelo nome do afiliado ou categoria">
                                        </div>

                                        <div class="form-group">
                                            <label for="afiliado_id">Afiliados</label>
                                            <table data-page-length="25" id="dataTableExample" class="table dataTable2 no-footer" role="grid" aria-describedby="dataTableExample_info">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting_asc" tabindex="0" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 153px;">Nome</th>
                                                        <th class="sorting" tabindex="1" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 244px;">Telefone</th>
                                                        <th class="sorting" tabindex="1" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 244px;">Categoria</th>
                                                        <th class="sorting" tabindex="1" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 244px;">Região</th>
                                                        <th>Selecionar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($afiliados as $key => $afiliado)
                                                    <tr role="row">
                                                        <td>
                                                            {{ $afiliado->razao_social }}
                                                            <span style="display:none;"><?php echo
                                                                                        str_replace('-', '', Formatacao::chave($afiliado->razao_social .
                                                                                            '' . $afiliado->telefone)); ?></span>
                                                        </td>
                                                        <td>{{ $afiliado->telefone }}</td>
                                                        <td>
                                                            @foreach($afiliado->categorias as $afiliadoCategoria)
                                                            @if(isset($afiliadoCategoria->categoria->id) && $afiliadoCategoria->categoria->id == $categoria->id)
                                                            <p class="badge badge-success">{{$afiliadoCategoria->categoria->nome}}</p>
                                                            @endif
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            @foreach($afiliado->regioes as $afiliadoRegiao)
                                                            <p>Região: {{$afiliadoRegiao->regiao ? $afiliadoRegiao->regiao->nome : "SEM REGIÃO"}}</p>
                                                            <p class="badge badge-success">Plano: {{isset($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->nome) ? $afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->nome : "Sem plano assinado"}}</p>
                                                            <p class="badge badge-info">Status: <?php echo isset($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->statusPlano) ? StatusPlano::getLabel($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->statusPlano) : 'Sem plano'; ?></p>
                                                            @endforeach
                                                        </td>
                                                        <td><a href="javascript:void(0)" data-dismiss="modal" class="btn btn-success" onclick="selecionarAfiliado('{{ $afiliado->id }}','{{ rawurlencode($afiliado->razao_social) }}','{{ $afiliado->telefone }}')">Selecionar</a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>





                    </div>

                </div>

            </div>


            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <h6>Selecione o contrato de prestação de serviço</h6>
                        <label style="font-size: 12px; font-weight: 600;">Você também poderá enviar o contrato <b>posteriormente</b>, realizando a <b>edição desta solicitação</b> ou clicando em <b>Upload de contrato</b> na listagem das solicitações.</label>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label>Selecione um arquivo no formato PDF</label>
                        <input type="file" class="form-control" accept="application/pdf" name="contrato" id="contrato" />
                    </div>

                    <label for="" class="btn btn-secondary btn-upload-contrato">Upload do contrato</label>
                    <label class="badge badge-warning label-upload-contrato-not" style="font-size: 14px; white-space: pre-wrap; text-align: left;">Para enviar um contrato, você deve selecionar o condomínio de um síndico e o afiliado.</label>

                    <h5 style="margin-top: 20px; margin-bottom: 10px;" class="hide-assinatura">Assinaturas</h5>
                    <select onchange="showAssinaturas(this.value)" class="hide-assinatura" name="formato_contrato_atual" style="margin-bottom: 24px; font-weight: bold;">
                        <option value="4">Vou enviar o contrato depois</option>
                        <option value="1">Este contrato ainda não foi assinado por ninguém</option>
                        <option value="2">Este contrato já foi assinado por todos</option>
                        <!--<option value="3">Este contrato foi parcialmente assinado</option>-->
                    </select>

                    <div class="container-assinaturas" style="display: none;">
                        <h5 style="margin-top: 20px; margin-bottom: 10px;">Quem já assinou?</h5>

                        <div class="form-group">
                            <label>Síndico</label>
                            <input type="checkbox" data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="dd/mm/yyyy" class="form-control" name="data_assinatura_sindico" />
                        </div>

                        <div class="form-group">
                            <label>Afiliado</label>
                            <input type="checkbox" class="form-control" data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="dd/mm/yyyy" name="data_assinatura_afiliado" />
                        </div>

                        <div class="form-group">
                            <label>Franqueado</label>
                            <input type="checkbox" class="form-control" data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="dd/mm/yyyy" name="data_assinatura_franqueado" />
                        </div>
                    </div>


                </div>
            </div>


        </div>

    </div>

<?php } else { ?>


    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <h6>Selecione uma categoria</h6>
                    </div>
                    <div class="form-group">
                        <label for="categoria_id">Categoria</label>
                        <select onchange="selecionarCategoria(this.value)" class="form-control" id="categoria_id" name="categoria_id" required="true">
                            <option style="display: none;" value=" old('categoria_id', optional($orcamento)->categoria_id ?: '') == '' ? 'selected' : '' }}" disabled selected>Selecione uma categoria</option>
                            @foreach ($categorias as $key => $categoria)
                            @php
                                // [HUBBOX FIX] Compatibiliza categoria como objeto (Eloquent) ou texto (array/pluck)
                                $categoriaOptionId = is_object($categoria) ? $categoria->id : $key;
                                $categoriaOptionNome = is_object($categoria) ? $categoria->nome : $categoria;
                            @endphp
                            <option value="{{ $categoriaOptionId }}" {{ old('categoria_id', optional($orcamento)->categoria_id) == $categoriaOptionId ? 'selected' : '' }}>
                                {{ $categoriaOptionNome }}
                            </option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                        <label id="categoria_id-error" class="error mt-2 text-danger" for="categoria_id">{{ $message }}</label>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>




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

    var escolheuCondominio = false;
    var escolheuAfiliado = false;

    function selecionarAfiliado(id, nome, telefone) {
        if (id != "") {
            escolheuAfiliado = true;
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

        }
        $("#afiliado_id").val(id);
        $(".afiliado_escolhido").html(decodeURIComponent(nome) + " - " + telefone);
    }

    function carregarCondominios(sindico_id) {
        var _token = $('input[name="_token"]').val();
        $("#condominio_id").html("<option value=''>Carregando...</option>");
        $("#container-select-condominio").show();

        $.getJSON({
            url: "<?php echo getenv("APP_URL"); ?>/admin/sindicos/" + sindico_id + "/condominios",
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

    function pesquisa2(e) {
        var termos = $("#pesquisa-afiliado-modal").val();
        var tecla = event.which || event.keyCode;
        if (tecla == 13) {
            dataTable2.search($("#pesquisa-afiliado-modal").val()).draw();
            return false;
        }
        return true;
    }

    function showAssinaturas(tipo) {
        if (tipo == 1) {
            $(".container-assinaturas").hide();
        } else if (tipo == 2) {
            $(".container-assinaturas").hide();
        } else if (tipo == 3) {
            $(".container-assinaturas").show();
        }
    }

    function selecionarCategoria(categoria_id) {
        window.location = "<?php echo getenv("APP_URL"); ?>/admin/orcamentos/create/categoria_id/" + categoria_id
    }
</script>
