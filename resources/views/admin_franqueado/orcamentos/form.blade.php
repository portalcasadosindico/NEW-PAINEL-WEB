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
</style>
<input type="hidden" value="{{$email_franqueado}}" name="email_franqueado" id="email_franqueado">

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
              @foreach ($categorias as $key => $categoria_linha)
              <option <?php if ($categoria_linha->id == $categoria->id) echo "selected"; ?> value="{{ $categoria_linha->id }}">
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
            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', optional($orcamento)->nome) }}" placeholder="Nome" required>
            @error('nome')
            <label id="nome-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" cols="30" rows="10" required placeholder="Descrição">{{ old('descricao', optional($orcamento)->descricao) }}</textarea>
            @error('descricao')
            <label id="descricao-error" class="error mt-2 text-danger" for="descricao">{{ $message }}</label>
            @enderror
          </div>


        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <div class="form-group">
            <h6>Dados de status</h6>
          </div>
          <div class="form-group">
            <label for="status">Status Oficial</label>
            <?php echo StatusOrcamento::getSelectAllStatus('status', 'status', 'Escolha um status'); ?>
            @error('status')
            <label id="status-error" class="error mt-2 text-danger" for="status">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group" style="display: none;">
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
          <div class="form-group" style="display: none;">
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

            <input type="hidden" value="" id="email_sindico_comparar" name="email_sindico_comparar">
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
                            <!-- [HUBBOX FIX] Proteção contra usuarioApp nulo -->
                            <td> 
                                <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-success" 
                                  onclick="selecionarSindico('{{ $sindico->id }}','{{ $sindico->nome }}','{{ $sindico->telefone }}', '{{ optional($sindico->usuarioApp)->email ?? 'sem_email@sistema.local' }}')">
                                  Selecionar
                                </a>
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
                    <h5 class="modal-title" id="exampleModalLabel">Novo Síndico</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">

                    @include ('admin_franqueado.sindicos.form2', [
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
                <div class="col-12">
                  <label style="font-size: 10px; margin-top: 16px; margin-bottom: 0px;">CNPJ</label>
                  <h6 style="margin-top:0px;" id="cnpj-condominio">--</h6>
                </div>
              </div>


              <label style="font-size: 10px; margin-top: 16px; margin-bottom: 0px;">Endereço</label>
              <h6 style="margin-top:0px;" id="endereco-condominio"></h6>

              <div class="row">
                <div class="col-12">
                  <label style="font-size: 10px; margin-top: 16px; margin-bottom: 0px;">Franqueado</label>
                  <h6 style="margin-top:0px;" id="franqueado-condominio"></h6>
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
            <h6>Selecione o afiliado da mesma categoria da solicitação</h6>
          </div>
          <div class="mt-3">
            <label>Afiliado</label>
            <input type="hidden" name="afiliado_id" id="afiliado_id" />
            <input type="hidden" value="" name="email_afiliado" id="email_afiliado">
            <h5 class="afiliado_escolhido">Deixar em aberto</h5><br>
            <a href="javascript:void(0)" title="Deixar em aberto" style="display: none;" class="btn btn-danger btn-remover-selecao-afiliado" onclick="selecionarAfiliado('','Deixar em aberto','', '')">X</a>
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

                    <div class="mt-2">
                      <label for="afiliado_id">Afiliados</label>
                      <table data-page-length="25" id="dataTableExample" class="table dataTable2 no-footer" role="grid" aria-describedby="dataTableExample_info">
                        <thead>
                          <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 153px;">Nome</th>
                            <th class="sorting" tabindex="1" aria-controls="dataTableExample" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 244px;">Categoria
                            </th>
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
                              <br>{{ $afiliado->telefone }}
                            </td>
                           <td>
                              @foreach($afiliado->categorias as $afiliadoCategoria)
                                @if(isset($afiliadoCategoria->categoria->id))

                                  @if($afiliadoCategoria->categoria->id == $categoria->id)
                                    <p class="badge badge-success">{{$afiliadoCategoria->categoria->nome}}</p>
                                  @endif
                                  
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
                                      <label class="badge badge-success">Plano:
                                        {{isset($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->nome) ? $afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->nome : "Sem plano assinado"}}</label>
                                      <label class="badge badge-info">Status:
                                        <?php echo isset($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->statusPlano) ? StatusPlano::getLabel($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->statusPlano) : 'Sem plano'; ?></label>
                                <?php }
                                  }
                                } ?>
                                @endforeach
                              </div>
                            </td>
                            <td><a href="javascript:void(0)" data-dismiss="modal" class="btn btn-success" onclick='selecionarAfiliado("{{ $afiliado->id }}", "{{ rawurlencode($afiliado->razao_social) }}","{{ $afiliado->telefone }}","{{ optional($afiliado->usuarioApp)->email }}")'>Selecionar</a>
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

        </div>

      </div>


      <div class="card">
        <div class="card-body">
          <div class="form-group">
            <h6>Selecione o contrato de prestação de serviço</h6>
            <label style="font-size: 12px; font-weight: 600;">Você também poderá enviar o contrato <b>posteriormente</b>,
              realizando a <b>edição desta solicitação</b> ou clicando em <b>Upload de contrato</b> na listagem das
              solicitações.</label>
          </div>
          <div class="form-group" style="display: none;">
            <label>Selecione um arquivo no formato PDF</label>
            <input type="file" class="form-control" onchange="mostrarNomeArquivo(this)" accept="application/pdf" name="contrato" id="contrato" />
          </div>

          <label for="" class="btn btn-secondary btn-upload-contrato">Upload do contrato</label>
          <label class="badge badge-primary arquivo-selecionado"></label>
          <label class="badge badge-warning label-upload-contrato-not" style="font-size: 14px; white-space: pre-wrap; text-align: left;">Para enviar um contrato, você deve
            selecionar o condomínio de um síndico e o afiliado.</label>

          <div class="estado-assinaturas" style="display: none;">
            <a href="javascript:void(0)" class="btn btn-danger" title="Remover arquivo" onclick="removerContrato()">X</a>
            <h5 style="margin-top: 20px; margin-bottom: 10px;" class="hide-assinatura">Informe o estado das assinaturas
              deste contrato</h5>
            <select onchange="showAssinaturas(this.value)" class="hide-assinatura" id="formato_contrato_atual" name="formato_contrato_atual" style="margin-bottom: 24px; font-weight: bold;">
              <option value="4">Selecione uma opção</option>
              @if($possuiTokenAutentique)
              <option value="1">Colher assinaturas pelo Autentique</option>
              @else
              <option value="" class="disabled" disabled>Colher assinaturas pelo Autentique (Sua franquia está sem o token
                do autentique)</option>
              @endif
              <option value="2">Este contrato já foi assinado por todos</option>
              <!--<option value="3">Este contrato foi parcialmente assinado</option>-->
            </select>
          </div>

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
              <option value="">Selecione uma categoria</option>
              @foreach ($categorias as $key => $categoria)
              <option value="{{ $categoria->id }}">
                {{ $categoria->nome }}
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
  let condominios = [];

  function showDadosCondominio(condominio_id) {
    for (var i = 0; i < condominios.length; i++) {
      var condominio = condominios[i];
      if (condominio_id == condominio.id) {
        escolheuCondominio = true;
        $("#dados_condominio").show();
        $("#nome-condominio").html(condominio.nome);
        $("#endereco-condominio").html(condominio.endereco);
        $("#cnpj-condominio").html(condominio.cnpj);
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
          $(".hide-assinatura").show();
        }
        return;
      }
    }
    $("#dados_condominio").hide();
  }

  function selecionarSindico(id, nome, telefone, email) {
    console.log(id);
    $("#email_sindico_comparar").val(email)
    $("#dados_condominio").hide();
    $("#sindico_id").val(id);
    $(".sindico_escolhido").html(nome + " - " + telefone);
    carregarCondominios(id);
  }

  var escolheuCondominio = false;
  var escolheuAfiliado = false;

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
      $(".container-assinaturas").hide();
      $("#email_afiliado").val("");
      removerContrato()
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
        const activeCondominios = condominios.filter(condominio => condominio.status === 'ativo');
        const qtd_condominios = activeCondominios.length;

        $(".qtd_condominios").html(qtd_condominios);
        for (var i = 0; i < qtd_condominios; i++) {
          var condominio = activeCondominios[i];

          $("#condominio_id").append("<option value='" + condominio.id + "'>" + condominio.nome +
            ". - Bairro " + condominio.bairro_name + "</option>");
        }

        if (qtd_condominios == 1) {
          $("#condominio_id").val(activeCondominios[0].id);
          showDadosCondominio(activeCondominios[0].id);
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
    const condominioExistente = $("#condominioExistente").val();

    if (condominioExistente == "true") {
      valida = false;
      showError("condominioExistente");
    } else {
      showSucesso("condominioExistente");
    }

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
    if (cnpj == "") {
      valida = false;
      showError("cnpj");
    } else {
      showSucesso("cnpj");
    }


    return valida;
  }

  sindico_id = $("#sindico_id").val();
  nome = $("#nome_condominio").val();
  estado = $("#estadof").val();
  cnpj = $("#cnpj").val();
  cidade = $("#cidadef").val();
  bairro = $("#bairrof").val();
  numero = $("#numerof").val();
  rua = $("#ruaf").val();
  cep = $("#cep").val();


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
        url: "../../../condominios/cadastrar_modal",
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
          console.log(data)
          if (data.errors) {
            if (data.errors[0].error_code == "invalid-cnpj") {
              alert(data.errors[0].error_message);
              showError("cnpj");
              $("#btn-cadastrar-condominio").removeAttr("disabled");
              $("#btn-cadastrar-condominio").html("Cadastrar");
            } else if (data.errors.length > 0) {
              alert(data.errors[0].error_message);
            }
          } else {
            condominios.push(data);
            $("#condominio_id").val(data.id);
            showDadosCondominio(data.id);
            $("#condominio_modal_novo").modal('hide');

            $("#condominio_id").append("<option value='" + data.id + "'>" + data.nome + "</option>");
            $("#condominio_id").val(data.id);
            var qtd_condominios = condominios.length;
            $(".qtd_condominios").html(qtd_condominios);
          }
        },
        error: function(error) {
          console.log(error)
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
        // [HUBBOX FIX] Evita quebra no carregamento da criação por categoria quando síndico não possui usuarioApp relacionado
        "{{ $sindicop->telefone }}", "{{ optional($sindicop->usuarioApp)->email }}");
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

  var data_inicio_mandato = $("#data_inicio_mandato").val();
  var data_fim_mandato = $("#data_fim_mandato").val();

  function cadastrarSindico() {
    $(".error").hide();
    nome = $("#nome_sindico").val();
    cpf = $("#cpf_sindico").val();
    numero_documento = $("#numero_documento_sindico").val();
    telefone = $("#telefone_sindico").val();
    email = $("#email_sindico").val();
    senha = $("#senha_sindico").val();

    data_inicio_mandato = $("#data_inicio_mandato").val();
    data_fim_mandato = $("#data_fim_mandato").val();

    if (validarSindico() == true) {
      $("#btn-cadastrar-sindico").attr("disabled", "disabled");
      $("#btn-cadastrar-sindico").html("Salvando...");
      var _token = $('input[name="_token"]').val();
      $.getJSON({
        url: "<?php echo getenv("APP_URL"); ?>/admin_franqueado/sindicos/cadastrar_modal",
        method: "POST",
        data: {
          _token: _token,
          nome_sindico: nome,
          cpf: cpf,
          numero_documento: numero_documento,
          telefone: telefone,
          email: email,
          senha: senha,
          franqueado_id: <?php echo isset($franqueado_id) ? $franqueado_id : 0; ?>,
          data_inicio_mandato: data_inicio_mandato,
          data_fim_mandato: data_fim_mandato
        },
        success: function(data) {

          if (data.errors) {
            if (data.errors[0].error_code == "invalid-cpf") {
              alert(data.errors[0].error_message);
              showError("cpf_sindico");
              $("#btn-cadastrar-sindico").removeAttr("disabled");
              $("#btn-cadastrar-sindico").html("Cadastrar");
            } else if (data.errors.length > 0) {
              alert(data.errors[0].error_message);
              $("#btn-cadastrar-sindico").removeAttr("disabled");
              $("#btn-cadastrar-sindico").html("Cadastrar");
              showError(data.errors[0].error_code);
            }
          } else {

            selecionarSindico(data.id, data.nome, data.telefone, data.email);

            $("#sindico_modal_novo").modal("hide");

            $("#btn-cadastrar-sindico").removeAttr("disabled");
            $("#btn-cadastrar-sindico").html("Cadastrar");

            $("#nome_sindico").val("");
            $("#cpf_sindico").val("");
            $("#numero_documento_sindico").val("");
            $("#telefone_sindico").val("");
            $("#email_sindico").val("");
            $("#senha_sindico").val("");

            $("#sindico_modal_novo input").removeClass("success-form-input");
            $("#sindico_modal_novo input").removeClass("error-form-input");
          }
        },
        error: function() {
          $("#btn-cadastrar-sindico").removeAttr("disabled");
          $("#btn-cadastrar-sindico").html("Cadastrar");
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

  function selecionarCategoria(categoria_id) {
    window.location = "<?php echo getenv("APP_URL"); ?>/admin_franqueado/orcamentos/create/categoria_id/" + categoria_id
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
    $(".container-assinaturas").hide();
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
</script>
