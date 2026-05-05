<?php

use App\Models\Bairro;
use App\Models\FranqueadoRegiao;
use App\Models\RegiaoFaixaCep;

use App\Uteis\Formatacao;
use App\Models\Orcamento;
use App\Models\Condominio;
use App\Uteis\StatusOrcamento;
?>
@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">Visualizando dados de sindicos</h4>

    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h4 style="margin-top:0px;" id="nome-condominio">Dados pessoais</h4>

            <label style="font-size: 13px; margin-top: 16px; margin-bottom: 0px;">Nome do síndico</label>
            <h4 style="margin-top:0px;" id="nome-condominio">{{ $sindico->nome }}</h4>
            <input type="hidden" id="sindico_id" value="{{ $sindico->id }}">

            <label style="font-size: 13px; margin-top: 16px; margin-bottom: 0px;">CPF</label>
            <h4 style="margin-top:0px;" id="nome-condominio">{{ $sindico->CPF }}</h4>

            <label style="font-size: 13px; margin-top: 16px; margin-bottom: 0px;">Número Documento</label>
            <h4 style="margin-top:0px;" id="nome-condominio">{{ $sindico->numero_documento }}</h4>

            <div class="row">
              <div class="col-6">
                <label style="font-size: 13px; margin-top: 16px; margin-bottom: 0px;">Data inicio mandato</label>
                <h4 style="margin-top:0px;" id="nome-condominio">{{ $sindico->data_inicio_mandato }}</h4>
              </div>
              <div class="col-6">
                <label style="font-size: 13px; margin-top: 16px; margin-bottom: 0px;">Data fim mandato</label>
                <h4 style="margin-top:0px;" id="nome-condominio">{{ $sindico->data_fim_mandato }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h4 style="margin-top:0px;" id="nome-condominio">Dados de contato</h4>

            <label style="font-size: 13px; margin-top: 16px; margin-bottom: 0px;">Telefone</label>
            <h4 style="margin-top:0px;" id="nome-condominio" class="whats-link">{{ $sindico->telefone }}</h4>

            <label style="font-size: 13px; margin-top: 16px; margin-bottom: 0px;">E-mail </label>
            @if($sindico->usuarioApp && $sindico->usuarioApp->data_confirmacao==null)
            <label class="badge badge-warning"> E-mail não confirmado</label>
            @elseif($sindico->usuarioApp)
            <label class="badge badge-success"> E-mail confirmado</label>
            @endif
            <h4 style="margin-top:0px;" id="nome-condominio"><a href="mailto:{{ $sindico->usuarioApp->email }}" target="_blank">{{ $sindico->usuarioApp->email }}</a></h4>
          </div>
        </div>
      </div>
    </div>

    <style>
      .item-condominio {
        border: 1px solid #555;
        padding: 24px;
      }
    </style>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h4 style="margin-top:0px; margin-bottom: 24px;">Condomínios</h4>

            <button style='position: absolute; right: 24px; top: 20px;' class="btn btn-info" onclick="return false;" data-toggle="modal" data-target="#condominio_modal_novo">Novo condomínio</button>

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
            </div>



            <table id="table_condominios" data-page-length="10" id="dataTableExample" class="table table-striped dataTable no-footer" role="grid" aria-describedby="dataTableExample_info">
              <thead>
                <tr>
                  <th width="200">Nome</th>
                  <th>Região/Localidade</th>
                  <th>Solicitações</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($sindico->condominios as $condominio)
                <tr>
                  <td>{{$condominio->nome}} - {{$condominio->cnpj}}</td>
                  <td>
                    @if(isset($condominio->bairroFK->regiao->nome))
                    <label class="badge badge-secondary">Região {{$condominio->bairroFK->regiao->nome}}</label>
                    @else
                    <?php
                    $bairro = Bairro::where("id", $condominio->bairro_id)->first();
                    $faixaCep = RegiaoFaixaCep::where("cidade_id", $bairro->cidade->id)->orderBy("id", "desc")->first();

                    if ($faixaCep) {
                      $regiaoFranquiado = FranqueadoRegiao::where("regiao_id", $faixaCep->regiao_id)->orderBy("id", "desc")->first();
                      $franqueado_name = "<span class='badge badge-secondary'>Franqueado " . $regiaoFranquiado->franqueado->nome . "</span>";
                      $regiao_name = "<span class='badge badge-secondary'>Região " . $regiaoFranquiado->regiao->nome . "</span>";
                    } else {
                      $franqueado_name = "<span class='badge badge-danger'>Bairro " . $bairro->nome . " sem região</span><br>
                                                                      <a style='display: block; margin-top: 6px;' href='" . route('bairros.edit', $condominio->bairroFK->id) . "'>Atribuir uma região</a>";
                      $regiao_name = "<span class='badge badge-danger'>Bairro " . $bairro->nome . " sem região</span>";
                    }
                    ?>
                    @if(isset($franqueado_name))
                    <?php echo $franqueado_name; ?><br><br>
                    <?php echo $regiao_name; ?>
                    @endif

                    @endif
                    <br><br>
                    @if(isset($condominio->bairroFK->nome))
                    <label class="badge badge-info">{{$condominio->bairroFK->nome}}. {{$condominio->bairroFK->cidade->nome}}</label>
                    @else
                    <label class="badge badge-danger">Condomínio sem bairro</label>
                    @endif
                  </td>
                  <td>
                    <?php
                    $countTotal = Orcamento::where("condominio_id", $condominio->id)->count();
                    $countConcluidas = Orcamento::where("condominio_id", $condominio->id)->where("status", StatusOrcamento::$FINALIZADO)->count();
                    $countCanceladas = Orcamento::where("condominio_id", $condominio->id)->whereIn("status", [StatusOrcamento::$CANCELADO_PELO_ADMIN, StatusOrcamento::$CANCELADO_PELO_SINDICO, StatusOrcamento::$CANCELADO_PELO_AFILIADO, StatusOrcamento::$CANCELADO_PELO_FRANQUEADO])->count();
                    $countAndamento = Orcamento::where("condominio_id", $condominio->id)->whereIn("status", [
                      StatusOrcamento::$ANALISANDO_CANDIDATOS, StatusOrcamento::$ANALISANDO_ORCAMENTOS, StatusOrcamento::$AGUARDANDO_CONTRATO, StatusOrcamento::$CONTRATO_ASSINADO, StatusOrcamento::$EM_EXECUCAO
                    ])->count();
                    ?>
                    <label class="badge badge-success mb-2" style="font-size: 16px;">Concluidas: {{$countConcluidas}}</label><br>
                    <label class="badge badge-warning mb-2" style="font-size: 16px;">Em andamento: {{$countAndamento}}</label><br>
                    <label class="badge badge-danger mb-2" style="font-size: 16px;">Canceladas: {{$countCanceladas}}</label><br>
                    <label class="badge badge-info mb-2" style="font-size: 16px;">Total: {{$countTotal}}</label>
                  </td>
                  <td style="text-align: center;">
                    @if($condominio->status === 'ativo')
                    <p class="text-success" style="font-size: 12px;">
                      Condomínio ativo
                    </p>
                    @else
                    <p class="text-danger" style="font-size: 12px;">
                      Condomínio inativo
                    </p>
                    @endif
                    <a style="font-size: 12px;" href="javascript:void(0)" onclick="carregarSolicitacoes(<?php echo $condominio->id; ?>, '<?php echo $condominio->nome; ?>')" class="btn btn-primary"><i data-feather="list" style="width: 20px;"></i> {{$condominio->orcamentos}}</a>
                    @if($condominio->status === 'ativo')
                    <a style="font-size: 12px;" href="javascript:void(0)" onclick="handleStatusCondominio(<?php echo $condominio->id; ?>, 'inativo')" class="btn btn-danger"><i data-feather="user-x" style="width: 20px;"></i> Inativar</a>
                    @else
                    <a style="font-size: 12px;" href="javascript:void(0)" onclick="handleStatusCondominio(<?php echo $condominio->id; ?>, 'ativo')" class="btn btn-success"><i data-feather="user-check" style="width: 20px;"></i> Ativar</a>
                    @endif
                  </td>

                </tr>
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
            <h6 style="margin-bottom: 20px;" id="nome_condominio_solicitacao">Escolha um condomínio</h6>
            <a style='position: absolute; right: 24px; top: 20px;' href="../orcamentos/create/sindico_id/{{$sindico->id}}" class="btn btn-info">Nova solicitação</a>

            <div class="container_solicitacoes row">


            </div>

          </div>
        </div>

      </div>
    </div>


  </div>
</div>






<div class="pull-right" style="margin-top: 40px;">
  <form method="POST" action="{!! route('admin.sindicos.destroy', $sindico->id) !!}" accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}
    <div class="btn-group btn-group-sm" role="group">

      <a href="{{ route('admin.sindicos.index') }}" class="btn btn-primary" title="Ver todos sindicos">
        <i data-feather="list"></i>
      </a>

      <a href="{{ route('admin.sindicos.create') }}" class="btn btn-success" title="Novo sindico">
        <i data-feather="plus"></i>
      </a>

      <a href="{{ route('admin.sindicos.edit', $sindico->id ) }}" class="btn btn-primary" title="Editar sindico">
        <i class="fa fa-pencil"></i>
      </a>

      <button type="submit" class="btn btn-danger" title="Remover sindico" onclick="return confirm('Deseja realmete excluir o sindico {{$sindico->nome}}?')">
        <i class="fa fa-trash"></i>
      </button>
    </div>
  </form>
</div>

</div>
</div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/dragula/dragula.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/dragula.js') }}"></script>
<script>
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
        url: "../condominios/cadastrar_modal",
        method: "POST",
        data: {
          _token: _token,
          sindico_id: <?php echo $id; ?>,
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
            document.location.reload()
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

  var condominios = []

  function carregarSolicitacoes(condominio_id, nome) {
    $(".container_solicitacoes").html("<h6>Carregando...</h6>");
    $("#nome_condominio_solicitacao").html("Condomínio " + nome);

    $.getJSON({
      url: "../condominios/" + condominio_id + "/orcamentos",
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

  async function handleStatusCondominio(condominio_id, type) {
    const text = type == "ativo" ? "ativar" : "inativar";
    if (confirm(`Deseja realmente ${text} esse condomínio?`)) {
      var _token = $('input[name="_token"]').val();
      $.getJSON({
        url: "/condominios/excluir",
        method: "POST",
        data: {
          _token: _token,
          condominio_id: condominio_id,
          payload: type
        },
        success: function(data) {
          if (data.success) {
            document.location.reload()
          } else {
            alert("Tente novamente.")
          }
        },
        error: function() {
          alert("Tente novamente.")
        }
      });
    }
  }
</script>
@endpush