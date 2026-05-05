<?php

use App\Models\FranqueadoRegiao;
use App\Uteis\Formatacao;
use App\Uteis\StatusOrcamento;

?>
@extends('admin_franqueado.layout.master')

<style>
table td .badge {
  margin-bottom: 5px !important;
}
</style>

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

    <div>
      <h4 class="mt-5 mb-5">Listagem de solicitações/orçamentos/serviços</h4>
      <a href="{{ route('admin_franqueado.orcamentos.create') }}" class="btn btn-success"
        title="Criar nova solicitação">
        <i data-feather="plus"></i> <span>Nova solicitação</span>
      </a>
    </div>

    <div class="btn-group btn-group-sm" role="group">
      <form action="{{ route('admin_franqueado.orcamentos.indexByDate') }}" method="POST" class="form-inline">
        @csrf
        <div class="form-group row m-2">
          <label for="filtro_ano" class="mr-1">Ano</label>
          <select id="filtro_ano" name="filtro_ano">
            @for ($i = date("Y"); $i >= 2017; $i--)
            <option value="{{$i}}" {{ ($ano ?? date('Y')==strval($i) ? 'selected' : '') }}>
              {{$i}}
            </option>
            @endfor
            <option value="-1" {{ $ano ?? ''=='-1' ? 'selected' : '' }}>Todos os meses</option>
          </select>
        </div>

        <div class="form-group row m-2">
          <label for="filtro_mes" class="mr-1">Mês</label>
          <select id="filtro_mes" name="filtro_mes">
            @for ($i=1; $i<=12; $i++) <option value="{{$i}}" {{ ($mes ?? date('m'))==strval($i) ? 'selected'
                            : '' }}>
              {{Formatacao::mesTexto($i)}}
              </option>
              @endfor
          </select>
        </div>
        <input type="hidden" name="franqueado_id" value="{{$franqueado_id}}">
        <button class="btn btn-primary m-1" type="submit">Pesquisar</button>
        <a href="{{route('admin_franqueado.orcamentos.index')}}" class="btn btn-secondary m-1">Limpar
          filtros</a>
      </form>
    </div>


    @if(count($orcamentos) == 0 && $franqueado_id != null)
    <div class="panel-body text-center">
      <h4>Nenhuma solicitação listada</h4>
    </div>
    @else
    <div class="panel-body panel-body-with-table">
      <div class="table-responsive">

        <table data-page-length="25" id="dataTableExample" class="table table-striped dataTableDesc no-footer"
          role="grid" aria-describedby="dataTableExample_info">
          <thead>
            <tr>
              <th>#</th>
              <th>Data e hora</th>
              <th>Status da solicitação</th>
              <th>Categoria</th>
              <th>Síndico</th>
              <th>Condomínio</th>
              <th>Contrato</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($orcamentos as $orcamento)
            <td>
              {{ $orcamento->id }}
            </td>
            <td>
              <?php echo Formatacao::data($orcamento->data_cadastro); ?>
            </td>
            <td>
              <?php if ($orcamento->status == StatusOrcamento::$CANCELADO_PELO_FRANQUEADO || $orcamento->status == StatusOrcamento::$CANCELADO_PELO_ADMIN || $orcamento->status == StatusOrcamento::$CANCELADO_PELO_AFILIADO || $orcamento->status == StatusOrcamento::$CANCELADO_PELO_SINDICO) { ?>
              <label class="badge badge-danger">
                <?php echo StatusOrcamento::getLabel($orcamento->status); ?>
              </label>
              <?php } else {  ?>
              <label class="badge badge-info"><?php echo StatusOrcamento::getLabel($orcamento->status); ?></label>
              <?php } ?>
            </td>
            <td>
              @if($orcamento->modo=="debug")
              <label class="badge badge-danger mb-2">MODO TESTE</label>
              <br>
              @endif
              <h6><?php echo isset($orcamento->categoria->nome) ? $orcamento->categoria->nome : ''; ?></h6>
              <br>
            </td>
            <td><?php echo isset($orcamento->condominio->sindico->nome)
                                ? $orcamento->condominio->sindico->nome : ''; ?></td>

            <td><?php echo isset($orcamento->condominio->nome)
                                ? $orcamento->condominio->nome : ''; ?>
            </td>

            <td>

              <label clas="badge badge-default"><b>{{$orcamento->titulo_contrato}}</b></label><br>
              @if($orcamento->formato_contrato_atual==2)
              <a href="{{$orcamento->contrato_assinado ? $orcamento->contrato_assinado : "
                                https://admin2.casadosindico.srv.br/storage/".$orcamento->contrato}}"
                class="btn btn-success" target="_blank">Ver contrato</a>
              @elseif($orcamento->formato_contrato_atual==1)
              <a href="{{$orcamento->contrato_original ? $orcamento->contrato_original : "
                                https://admin2.casadosindico.srv.br/storage/".$orcamento->contrato}}"
                class="btn btn-secondary" target="_blank">Ver contrato</a>
              <a href="{{$orcamento->contrato_assinado ? $orcamento->contrato_assinado : "
                                https://admin2.casadosindico.srv.br/storage/".$orcamento->contrato}}" class="btn
                                btn-success" target="_blank">Ver contrato assinado</a>
              @else
              <a class="btn btn-danger"
                href="{{ route('admin_franqueado.orcamentos.edit', $orcamento->id ) }}#upload">Upload
                do
                contrato</a>
              @endif

            </td>
            <td align="right">

              <form method="POST" action="{!! route('admin_franqueado.orcamentos.destroy', $orcamento->id) !!}"
                accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <div class="btn-group btn-group-xs pull-right" role="group">
                  <!--<a href="{{ route('admin_franqueado.orcamentos.show', $orcamento->id ) }}" class="btn btn-info" title="Ver orcamento">
                                                        <i class="fa fa-eye"></i>
                                                    </a>-->
                  <a href="{{ route('admin_franqueado.orcamentos.edit', $orcamento->id ) }}" class="btn btn-primary"
                    title="Editar orcamento">
                    <i class="fa fa-pencil"></i>
                  </a>

                  <button type="submit" class="btn btn-danger" title="Remover orcamento"
                    onclick="return confirm('Deseja realmete excluir o orcamento {{$orcamento->nome}}?')">
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
@push('plugin-scripts')
<script src="{{ asset('assets/plugins/dragula/dragula.min.js') }}"></script>
@endpush
@push('custom-scripts')
<script src="{{ asset('assets/js/dragula.js') }}"></script>
@endpush
@endsection