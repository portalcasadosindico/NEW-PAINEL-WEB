<?php

use App\Uteis\StatusOrcamento;
use App\Uteis\Formatacao;

?>
@extends('layout.dashboardpanel')

<script type="text/javascript">
  function filtroSolicitacoes(texto) {
    dataTableNoOrder.search(texto).draw();
  }
</script>
@if (count($solicitacoes) == 0)
<div class="panel-body text-center">
  <i class="fa fa-tint fa-5x"></i>
  <h4>Nenhuma solicitação em andamento</h4>
</div>
@else
<button class="btn btn-info mb-3" onclick="filtroSolicitacoes('aguardando contrato')">Ver apenas
  Aguardando Contrato</button>
<div class="panel-body panel-body-with-table" style="max-height: 600px; overflow: auto;">
  <div class="table-responsive">
    <table class="table table-striped dataTableNoOrder dashboard-solicitacoes-em-andamento">
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
        @foreach ($solicitacoes as $solicitacao)
        <td>
          <h5>#{{ $solicitacao->id }}</h5>
          Data: <b>
            <?php echo Formatacao::data($solicitacao->data_cadastro); ?>
          </b><br><br>
          Síndico:
          <b>{{
            $solicitacao->condominio()->withTrashed()->first()->sindico()->withTrashed()->first()->nome
            }}</b><br>
          Afiliado:
          <b>{{ $solicitacao->afiliado ? $solicitacao->afiliado->nome_fantasia : 'Nenhum
            afiliado' }}</b>
        </td>
        <td>
          @if ($solicitacao->regiao_id)
          <label class="badge badge-success">Região:
            {{ $solicitacao->regiao()->withTrashed()->first()->nome }}</label>
          @else
          <label class="badge badge-danger">Sem região</label>
          <p>
            Não foi encontrado uma região para a solicitação.<br>
            Isso ocorreu porque o endereço do condomínio não foi encontrado em
            nossas regiões, no momento do cadastro da solicitação.<br>
            <br>
          <h6>Solução</h6>
          1) Peça para o síndico revisar o endereço do condomínio, ou vá até o
          Perfil do síndico pelo admin e realize a verificação do
          endereço.<br><br>
          2) Você precisa Editar a solicitação que está sem região e simplesmente
          clicar em Salvar. O sistema irá revisar a região desta soicitação com
          base no endereço atual do condomínio.
          </p>
          @endif
          <br><br>
          Categoria: <b>
            <?php echo $solicitacao
                                            ->categoria()
                                            ->withTrashed()
                                            ->first()->nome; ?>
          </b><br><br>
          Condominio:
          <b>{{ $solicitacao->condominio()->withTrashed()->first()->nome }}</b><br>
          <br>
          {{ $solicitacao->condominio->endereco . '. ' . $solicitacao->bairroFK->nome . ', ' .
          $solicitacao->bairroFK->cidade->nome . '/' .
          $solicitacao->bairroFK->cidade->estado->uf }}
        </td>
        <td>
          {{ $solicitacao->status ? StatusOrcamento::getLabel($solicitacao->status) : 'Sem
          status' }}
        </td>

        <td>
          <label clas="badge badge-default"><b>{{ $solicitacao->titulo_contrato
              }}</b></label><br>
          @if ($solicitacao->formato_contrato_atual == 2)
          <a href="{{ $solicitacao->contrato_assinado ? $solicitacao->contrato_assinado : 'https://admin2.casadosindico.srv.br/storage/' . $solicitacao->contrato }}"
            class="btn btn-success" target="_blank">Ver contrato</a>
          @elseif($solicitacao->formato_contrato_atual == 1)
          <a href="{{ $solicitacao->contrato_original ? $solicitacao->contrato_original : 'https://admin2.casadosindico.srv.br/storage/' . $solicitacao->contrato }}"
            class="btn btn-secondary" target="_blank">Ver contrato</a>
          <a href="{{ $solicitacao->contrato_assinado ? $solicitacao->contrato_assinado : 'https://admin2.casadosindico.srv.br/storage/' . $solicitacao->contrato }}"
            class="btn btn-success" target="_blank">Ver contrato assinado</a>
          @else
          <a class="btn btn-danger"
            href="{{ route('admin_franqueado.orcamentos.edit', $solicitacao->id) }}#upload">Upload
            do
            contrato</a>
          @endif

        </td>

        <td align="right">

          <form method="POST" action="{!! route('admin.orcamentos.destroy', $solicitacao->id) !!}"
            accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}

            <div class="btn-group btn-group-xs pull-right" role="group">
              <a href="{{ route('admin.orcamentos.edit', $solicitacao->id) }}" class="btn btn-primary"
                title="Editar solicitacao">
                <i class="fa fa-pencil"></i>
              </a>

              <button type="submit" class="btn btn-danger" title="Remover solicitacao"
                onclick="return confirm('Deseja realmete excluir o solicitacao {{ $solicitacao->id }}?')">
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