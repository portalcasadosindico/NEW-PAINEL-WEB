<?php

use App\Models\AfiliadoFranqueadoAsaas;
use App\Uteis\StatusOrcamento;
use App\Uteis\StatusPlano;
use App\Uteis\Formatacao;
?>
@extends('admin_franqueado.layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Bem vindo ao painel administrativo</h4>
    Hoje é dia
    <?php echo date("d/m/Y - H:i:s"); ?>
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
      <div class="col-md-3 grid-margin stretch-card">
        <div class="card border-success">
          <div class="card-body">
            <div class="text-center">
              <h6 class="card-title mb-0" style="font-size:15px;">Planos Ativos</h6>
            </div>
            <div class="row text-center">
              <div class="col-12 col-md-12 col-xl-12">
                <h3 class="mb-2">{{$planos_ativos}}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 grid-margin stretch-card">
        <div class="card border-warning">
          <div class="card-body">
            <div class="text-center">
              <h6 class="card-title mb-0" style="font-size:15px;">Afiliações sem contrato</h6>
            </div>
            <div class="row text-center">
              <div class="col-12 col-md-12 col-xl-12">
                <h3 class="mb-2">{{$afiliados_sem_contrato}}</h3>
                @foreach($afiliados_sem_contrato_result as $afiliado)
                <a href="{{ route('admin_franqueado.afiliados.show', $afiliado->id ) }}" title="Ver afiliado"
                  target="_blank"><label class="badge badge-warning color-light cursor-pointer">{{$afiliado->nome_fantasia
                                        ? $afiliado->nome_fantasia : $afiliado->razao_social}} <i
                      class="fa fa-eye"></i></label></a>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 grid-margin stretch-card">
        <div class="card border-danger">
          <div class="card-body">
            <div class="text-center">
              <h6 class="card-title mb-0" style="font-size:15px;">Afiliações canceladas ou inadimplente
              </h6>
            </div>
            <div class="row text-center">
              <div class="col-12 col-md-12 col-xl-12">
                <h3 class="mb-2">{{$afiliados_inativos}}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 grid-margin stretch-card">
        <div class="card border-success">
          <div class="card-body">
            <div class="text-center">
              <h6 class="card-title mb-0" style="font-size:15px;">Total em Planos de Afiliados Ativo Com
                Desconto</h6>
            </div>
            <div class="row text-center">
              <div class="col-12 col-md-12 col-xl-12">
                <h3 class="mb-2" title="Sem desconto ficaria {{$valor_planos_afiliados_ativos}}">R$
                  {{$valor_planos_afiliados_ativos_com_dessconto}}
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12 grid-margin stretch-card">
        <div class="card border-success">
          <div class="card-body">
            <div class="d-flex text-center align-items-baseline mb-2">
              <h6 class="card-title mb-0 text-center" style="font-size:15px; text-align: center; width: 100%;">
                Detalhamento planos</h6>
            </div>

            <div class="text-center">
              <button class="btn btn-primary btn-detalhes-plano mb-2"
                onclick="expandir('detalhes-plano')">Expandir</button>
            </div>

            <div class="panel-body panel-body-with-table lista-detalhes-plano"
              style="display: none; max-height: 450px;overflow: auto;">
              <div class="table-responsive">
                <table class="table dataTableNoOrder">
                  <thead>
                    <tr>
                      <th>Razão social</th>
                      <th>Plano</th>
                      <th>Valor</th>
                      <th>Valor c/ Desc</th>
                      <th>Desconto</th>
                      <th>Data expiração</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($t as $textoDetalhe)
                    <tr style="background-color: var({{$textoDetalhe['cor']}}); color: #fff;">
                      <td><a href="afiliados/{{$textoDetalhe['afiliado_id']}}" target="_blank"
                          style="color: #fff;">{{$textoDetalhe['razao_social']}}</a></td>
                      <td>{{$textoDetalhe['nome_plano']}}</td>
                      <td>{{$textoDetalhe['valor']}}</td>
                      <td>{{$textoDetalhe['valor_desconto']}}</td>
                      <td>{{$textoDetalhe['desconto']}}%</td>
                      <td>{{$textoDetalhe['data_expiracao']}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="text-center mt-4">
                <button class="btn btn-warning btn-detalhes-plano"
                  onclick="expandir('detalhes-plano')">Colapsar</button>
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
                <h6 class="card-title mb-0" style="font-size:15px;">Solicitações em andamento</h6>
                <button type="button" class="btn-question mb-4" data-trigger="focus" data-toggle="popover"
                  title="Solicitações em andamento"
                  data-content="São solicitações que estão em processo de análise de candidatos, orçamento ou em execução."><i
                    class="fa fa-question"></i></button>
              </div>
              <div class="row text-center">
                <div class="col-12 col-md-12 col-xl-12">
                  <h3 class="mb-2" id='solicitacoes-andamento-label'>Carregando...</h3>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
          <div class="card border-danger">
            <div class="card-body">
              <div class="text-center">
                <h6 class="card-title mb-0" style="font-size:15px;">Solicitações canceladas</h6>
                <button type="button" class="btn-question mb-4" data-trigger="focus" data-toggle="popover"
                  title="Solicitações canceladas"
                  data-content="São solicitações que foram canceladas pelo super admin, franqueado, afiliado ou síndico."><i
                    class="fa fa-question"></i></button>
              </div>
              <div class="row text-center">
                <div class="col-12 col-md-12 col-xl-12">
                  <h3 class="mb-2" id='solicitacoes-canceladas-label'>Carregando...</h3>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
          <div class="card border-success">
            <div class="card-body">
              <div class="text-center">
                <h6 class="card-title mb-0" style="font-size:15px;">Solicitações de vistorias</h6>
                <button type="button" class="btn-question mb-4" data-trigger="focus" data-toggle="popover"
                  title="Solicitações de vistorias" data-content="Existem vistorias com status Em Aberto."><i
                    class="fa fa-question"></i></button>
              </div>
              <div class="row text-center">
                <div class="col-12 col-md-12 col-xl-12">
                  <h3 class="mb-2" id='solicitacoes-vistoria-label'>Carregando...</h3>

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
          <h6 class="card-title mb-0">Últimos 10 afiliados registrados</h6>
        </div>

        @if(count($afiliados) == 0)
        <div class="panel-body text-center">
          <i class="fa fa-tint fa-5x"></i>
          <h4>Nenhum afiliado listado</h4>
        </div>
        @else
        <div class="text-center">
          <button class="btn btn-primary btn-afiliados" onclick="expandir('afiliados')">Expandir</button>
        </div>
        <div class="panel-body panel-body-with-table lista-afiliados" style="display: none;">
          <div class="table-responsive">
            <table class="table table-striped ">
              <thead>
                <tr>
                  <th width="200">Razão social</th>
                  <th>
                    Contato
                  </th>
                  <th>Categorias</th>
                  <th>Regiões</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($afiliados as $afiliado)
                <tr>
                  <td>{{ $afiliado->razao_social }}
                    <span class="citacao">
                      - Via <b>{{$afiliado->forma_cadastro}}</b>
                      em
                      <?php echo Formatacao::data($afiliado->data_cadastro) ?>
                      <br>

                      <?php
                                            if ($fid > 0) {
                                                $afiliadaAsaas = AfiliadoFranqueadoAsaas::where("franqueado_id", $fid)
                                                    ->where("afiliado_id", $afiliado->id)
                                                    ->orderBy("id", "desc")
                                                    ->first();
                                                if ($afiliadaAsaas)
                                                    $afiliado->asaas_customer_id = $afiliadaAsaas->asaas_customer_id;
                                                else
                                                    $afiliado->asaas_customer_id = null;
                                            } else {
                                                $afiliado->asaas_customer_id = null;
                                            }
                                            ?>

                      @if($afiliado->asaas_customer_id)
                      <label>ID do Cliente no ASAAS</label><br>
                      <h5 class="badge badge-success">ID ASAAS: {{$afiliado->asaas_customer_id}}
                      </h5>
                      @else
                      <h5 class="badge badge-warning">Não integrado ao ASAAS</h5>
                      @endif
                    </span>
                  </td>
                  <td>
                    <h5>{{ $afiliado->responsavel ? $afiliado->responsavel->nome : '--' }}</h5>
                    @if($afiliado->usuarioApp!=null)
                    <span class="mail-link">{{ $afiliado->usuarioApp->email }}</span>
                    @else
                    Usuário App foi excluído.
                    @endif
                    <br><br>
                    <span class="whats-link">{{ $afiliado->telefone }}</span>
                  </td>

                  <td>
                    <div style="max-height: 150px; overflow: auto;">
                      @foreach($afiliado->categorias as $afiliadoCategoria)
                      @if($afiliadoCategoria->status=="aprovado")
                      <label class="badge badge-primary" style="margin-bottom: 3px;">{{$afiliadoCategoria->categoria ?
                                                $afiliadoCategoria->categoria->nome : "Categoria removida"}}</label>
                      @elseif($afiliadoCategoria->status=="pendente")
                      <label class="badge badge-warning" style="margin-bottom: 7px;">{{$afiliadoCategoria->categoria ?
                                                $afiliadoCategoria->categoria->nome : "Categoria removida"}}</label>
                      <button type="button" class="btn-question" data-trigger="focus" data-toggle="popover"
                        style="top:0px;" title="Solicitação pendente"
                        data-content="O afiliado deseja participar desta categoria."><i
                          class="fa fa-question"></i></button>
                      @endif
                      @endforeach
                    </div>
                  </td>
                  <td>
                    @if(count($afiliado->regioes)==0)
                    <p class="badge badge-danger mt-1">Sem Plano e Sem contrato</p>
                    @endif
                    @foreach($afiliado->regioes as $afiliadoRegiao)
                    <?php
                                        if ($afiliadoRegiao->regiao) {
                                            $regiaoId = $afiliadoRegiao->regiao->id;
                                            foreach ($franqueadoRegioes as $franqueadoRegiaoLinha) {
                                                if ($franqueadoRegiaoLinha->regiao_id == $regiaoId) {
                                        ?>
                    <p class="badge badge-primary">Região: {{$afiliadoRegiao->regiao ?
                                            $afiliadoRegiao->regiao->nome : "SEM REGIÃO"}}</p>
                    <p class="badge badge-success mt-1">Plano:
                      {{isset($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->nome) ?
                                            $afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->nome : "Sem plano
                                            assinado"}}
                    </p>
                    <p class="badge badge-info mt-1">Status:
                      <?php echo isset($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->statusPlano) ? StatusPlano::getLabel($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->statusPlano) : 'Sem plano'; ?>
                    </p>

                    @if($afiliadoRegiao->PlanoAssinaturaAfiliadoRegiao->arquivo_original==null)
                    <p class="badge badge-danger mt-1">Sem contrato</p>
                    @else

                    @endif

                    <hr>
                    <?php }
                                            }
                                        } ?>
                    @endforeach
                  </td>

                  <td align="right">

                    <form method="POST" action="{!! route('admin_franqueado.afiliados.destroy', $afiliado->id) !!}"
                      accept-charset="UTF-8">
                      <input name="_method" value="DELETE" type="hidden">
                      {{ csrf_field() }}

                      <div class="btn-group btn-group-xs pull-right" role="group">
                        <a href="{{ route('admin_franqueado.afiliados.show', $afiliado->id ) }}" class="btn btn-info"
                          title="Ver afiliado">
                          <i class="fa fa-eye"></i>
                        </a>
                        <a href="{{ route('admin_franqueado.afiliados.edit', $afiliado->id ) }}" class="btn btn-primary"
                          title="Editar afiliado">
                          <i class="fa fa-pencil"></i>
                        </a>

                        <button type="submit" class="btn btn-danger" title="Remover afiliado"
                          onclick="return confirm('Deseja realmete excluir o afiliado {{$afiliado->nome}}?')">
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

          <div class="text-center">
            <button class="btn btn-warning btn-afiliados" onclick="expandir('afiliados')">Colapsar</button>
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
          <h6 class="card-title mb-0">Últimos Síndicos cadastrados</h6>
        </div>

        @if(count($sindicos) == 0)
        <div class="panel-body text-center">
          <i class="fa fa-tint fa-5x"></i>
          <h4>Nenhum síndico listado</h4>
        </div>
        @else

        <div class="text-center">
          <button class="btn btn-primary btn-sindicos" onclick="expandir('sindicos')">Expandir</button>
        </div>

        <div class="panel-body panel-body-with-table lista-sindicos" style="display: none;">
          <div class="table-responsive">
            <table class="table table-striped ">
              <thead>
                <tr>
                  <th width="200">Nome</th>
                  <th>E-mail</th>
                  <th>Telefone</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($sindicos as $sindico)
                <td>
                  {{ $sindico->nome }}
                  <span class="citacao">
                    -
                    @if($sindico->franqueado)
                    Por franquia <b style="cursor: pointer;"
                      onclick="pesquisar(<?php echo $sindico->franqueado->id; ?>)">{{
                                            $sindico->franqueado->nome }}</b>
                    @else
                    Via App
                    @endif
                    em
                    <?php echo Formatacao::data($sindico->data_cadastro) ?>
                  </span>
                </td>
                <td>
                  @if($sindico->usuarioApp)
                  <a href="mailto:{{ $sindico->usuarioApp->email }}">{{ $sindico->usuarioApp->email
                                        }}</a>
                  @else
                  --
                  @endif
                </td>
                <td class="whats-link">
                  {{ $sindico->telefone }}
                </td>
                <td align="right">

                  <form method="POST" action="{!! route('admin_franqueado.sindicos.destroy', $sindico->id) !!}"
                    accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    <div class="btn-group btn-group-xs pull-right" role="group">
                      <a href="{{ route('admin_franqueado.sindicos.show', $sindico->id ) }}" class="btn btn-info"
                        title="Ver sindico">
                        <i class="fa fa-eye"></i>
                      </a>
                      <a href="{{ route('admin_franqueado.sindicos.edit', $sindico->id ) }}" class="btn btn-primary"
                        title="Editar sindico">
                        <i class="fa fa-pencil"></i>
                      </a>

                      <button type="submit" class="btn btn-danger" title="Remover sindico"
                        onclick="return confirm('Deseja realmete excluir o sindico {{$sindico->nome}}?')">
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

          <div class="text-center">
            <button class="btn btn-warning btn-sindicos" onclick="expandir('sindicos')">Expandir</button>
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
          <h6 class="card-title mb-0">Solicitações em Andamento</h6>
        </div>
        <div id='solicitacoes-andamento-table'>Carregando...</div>
      </div>
    </div>
  </div>
</div> <!-- row -->
<form method="POST">{{ csrf_field() }}</form>
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery.flot/jquery.flot.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery.flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/progressbar-js/progressbar.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/datepicker.js') }}"></script>

<script>
async function getSEA() {
  const res = await fetch("<?php echo getenv("APP_URL"); ?>/dashboard_franqueado/solicitacoes/1,2,3,4,10", {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('input[name="_token"]').val()
    },
  })
  const data = await res.json()
  $('#solicitacoes-andamento-label').html(data)
}

async function getSCA() {
  const res = await fetch("<?php echo getenv("APP_URL"); ?>/dashboard_franqueado/solicitacoes/6,7,8,9", {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('input[name="_token"]').val()
    },
  })
  const data = await res.json()
  $('#solicitacoes-canceladas-label').html(data)
}

async function getSVI() {
  const res = await fetch("<?php echo getenv("APP_URL"); ?>/dashboard_franqueado/solicitacoes/vistoria", {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('input[name="_token"]').val()
    },
  })
  const data = await res.json()
  $('#solicitacoes-vistoria-label').html(data)
}

async function getSA() {
  const res = await fetch("<?php echo getenv("APP_URL"); ?>/dashboard_franqueado/solicitacao/andamento", {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('input[name="_token"]').val()
    },
  })
  const data = await res.json()
  $('#solicitacoes-andamento-table').html(data)
}

function abrirMotivo(index) {
  if ($("#content-motivo-" + index).css("display") == "none") {
    $("#content-motivo-" + index).show();
  } else {
    $("#content-motivo-" + index).hide();
  }
}

function abrirMotivoDocPendente(index, tipo) {
  if ($("#content-motivo-doc-" + index + "-" + tipo).css("display") == "none") {
    $("#content-motivo-doc-" + index + "-" + tipo).show();
  } else {
    $("#content-motivo-doc-" + index + "-" + tipo).hide();
  }
}

function aprovarCategoria(afiliado_categoria_id, newStatus) {

  if (newStatus == "aprovado") {
    var textoConfirm = "Você confirma a aprovação desta categoria?";
  } else if (newStatus == "reprovado") {
    var textoConfirm = "Você confirma a reprovação desta categoria?";
  }

  if (confirm(textoConfirm)) {
    $(".btn-aprovar-" + afiliado_categoria_id).html("Aguarde...")
    $(".btn-aprovar-" + afiliado_categoria_id).addClass("disabled")
    $(".btn-reprovar-" + afiliado_categoria_id).html("Aguarde...")
    $(".btn-reprovar-" + afiliado_categoria_id).addClass("disabled")
    var motivo = $("#motivo-reprovado-" + afiliado_categoria_id).val()
    var _token = $('input[name="_token"]').val();
    $.getJSON({
      url: '<?php echo getenv("APP_URL"); ?>/admin_franqueado/alterar_status_categoria_afiliado/' +
        afiliado_categoria_id,
      method: "POST",
      data: {
        _token: _token,
        status: newStatus,
        motivo: motivo
      },
      success: function(data) {
        if (data.res == true) {
          $("#linha-categoria-pendente-" + afiliado_categoria_id).remove();
          contarQuantidaeCategoriasPendentes();
        } else {
          $(".btn-aprovar-" + afiliado_categoria_id).html("Aprovar")
          $(".btn-aprovar-" + afiliado_categoria_id).removeClass("disabled")
          $(".btn-reprovar-" + afiliado_categoria_id).html("Aprovar")
          $(".btn-reprovar-" + afiliado_categoria_id).removeClass("disabled")
          alert("Tente novamente.")
        }
      },
      error: function() {
        $(".btn-aprovar-" + afiliado_categoria_id).html("Aprovar")
        $(".btn-aprovar-" + afiliado_categoria_id).removeClass("disabled")
        $(".btn-reprovar-" + afiliado_categoria_id).html("Aprovar")
        $(".btn-reprovar-" + afiliado_categoria_id).removeClass("disabled")
        alert("Tente novamente.")
      }
    });
  }

}

function aprovarDocPendente(documento_id, newStatus, tipo) {

  if (newStatus == "aprovado") {
    var textoConfirm = "Você confirma a aprovação deste documento?";
  } else if (newStatus == "reprovado") {
    var textoConfirm = "Você confirma a reprovação deste documento?";
  }

  if (confirm(textoConfirm)) {
    $(".btn-aprovar-doc-" + documento_id + "-" + tipo).html("Aguarde...")
    $(".btn-aprovar-doc-" + documento_id + "-" + tipo).addClass("disabled")
    $(".btn-reprovar-doc-" + documento_id + "-" + tipo).html("Aguarde...")
    $(".btn-reprovar-doc-" + documento_id + "-" + tipo).addClass("disabled")
    var motivo = $("#motivo-reprovado-doc-" + documento_id + "-" + tipo).val()
    var _token = $('input[name="_token"]').val();
    $.getJSON({
      url: '<?php echo getenv("APP_URL"); ?>/admin_franqueado/alterar_status_documento_pendente/' + documento_id,
      method: "POST",
      data: {
        _token: _token,
        status: newStatus,
        motivo: motivo,
        tipo: tipo
      },
      success: function(data) {
        if (data.res == true) {
          $("#linha-doc-pendente-" + documento_id + "-" + tipo).remove();
          contarQuantidaeDocPendentes();
        } else {
          $(".btn-aprovar-doc-" + documento_id + "-" + tipo).html("Aprovar")
          $(".btn-aprovar-doc-" + documento_id + "-" + tipo).removeClass("disabled")
          $(".btn-reprovar-doc-" + documento_id + "-" + tipo).html("Reprovar")
          $(".btn-reprovar-doc-" + documento_id + "-" + tipo).removeClass("disabled")
          alert("Tente novamente.")
        }
      },
      error: function() {
        $(".btn-aprovar-doc-" + documento_id + "-" + tipo).html("Aprovar")
        $(".btn-aprovar-doc-" + documento_id + "-" + tipo).removeClass("disabled")
        $(".btn-reprovar-doc-" + documento_id + "-" + tipo).html("Reprovar")
        $(".btn-reprovar-doc-" + documento_id + "-" + tipo).removeClass("disabled")
        alert("Tente novamente.")
      }
    });
  }


}

function contarQuantidaeCategoriasPendentes() {
  var qtd = $(".linha-categoria-pendente").length;
  $("#qtd-categorias-pendentes").html(qtd);
  if (qtd == 0) {
    $("#container-categorias-pendentes").html(
      '<tr><td colspan="3"><div class="panel-body text-center"><br><i class="fa fa-thumbs-o-up fa-5x"></i><h4>Nenhuma solicitação pendente</h4></div></td></tr>'
    )
  }
  return true;
}

function contarQuantidaeDocPendentes() {
  var qtd = $(".linha-doc-pendente").length;
  $("#qtd-doc-pendentes").html(qtd);
  if (qtd == 0) {
    $("#container-doc-pendentes").html(
      '<tr><td colspan="3"><div class="panel-body text-center"><br><i class="fa fa-thumbs-o-up fa-5x"></i><h4>Nenhuma solicitação pendente</h4></div></td></tr>'
    )
  }
  return true;
}

function expandir(className) {
  if ($(".lista-" + className).css("display") == "none") {
    $(".lista-" + className).slideDown(400);
    $(".btn-" + className).html("Colapsar");
  } else {
    $(".lista-" + className).slideUp(400);
    $(".btn-" + className).html("Expandir");
  }

}

$(document).ready(function() {

  Promise.all([
    getSEA(),
    getSCA(),
    getSVI(),
    getSA()
  ]);

});
</script>
@endpush