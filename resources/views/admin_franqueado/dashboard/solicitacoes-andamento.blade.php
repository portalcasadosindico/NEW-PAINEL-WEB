<?php

use App\Uteis\StatusOrcamento;
use App\Uteis\Formatacao;

?>


<script type="text/javascript">
  let solicitacoesAndamentoDT;
  solicitacoesAndamentoDT = $('#dashboard-solicitacoes-em-andamento-franqueado').DataTable({
    "ordering": false,
    "paging": true,
    "lengthChange": true,
    //"searching": false,
    "language": {
      "paginate": {
        "next": "Próximo",
        "previous": "Anterior",
        "emptyTable": "Nada para listar",
        "info": "Mostrando página _PAGE_ de _PAGES_",
        "decimal": ",",
        "thousands": "."
      },
      "searchPlaceholder": "Pesquise",
      "search": ""
    }
  });

  function filtroSolicitacoes(texto) {
    solicitacoesAndamentoDT.search(texto).draw();
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
    <table class="table table-striped dataTableNoOrder" id="dashboard-solicitacoes-em-andamento-franqueado">
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
        @foreach($solicitacoes as $solicitacao)
        @php
          $condominio = $solicitacao->condominio()->withTrashed()->first();
          $sindico = optional($condominio)->sindico()->withTrashed()->first();
          $categoria = $solicitacao->categoria()->withTrashed()->first();
          $bairro = $solicitacao->bairroFK;
        @endphp
        <td>
          #{{ $solicitacao->id }}<br>
          Data: <b>
            <?php echo Formatacao::data($solicitacao->data_cadastro) ?>
          </b><br><br>
          Síndico:
          <b>{{ optional($sindico)->nome ?? 'Sem síndico' }}</b>
          Afiliado: <b>{{$solicitacao->afiliado ? $solicitacao->afiliado->nome_fantasia :
                                        'Nenhum afiliado'}}</b>
        </td>
        <td>
          @if($solicitacao->regiao_id)
          <label class="badge badge-success">Região: {{
                                        $solicitacao->regiao()->withTrashed()->first()->nome }}</label>
          @else
          <label class="badge badge-danger">Sem região</label>
          <p>
            Não foi encontrado uma região para a solicitação.
          </p>
          @endif
          <br><br>
          Categoria: <b>
            {{ optional($categoria)->nome ?? 'Sem categoria' }}
          </b><br><br>
          Condominio: <b>{{ optional($condominio)->nome ?? 'Sem condomínio'
                                        }}</b><br>
          {{ optional($condominio)->endereco ?? 'Endereço indisponível' }}.
          {{ optional($bairro)->nome ?? 'Bairro indisponível' }},
          {{ optional(optional($bairro)->cidade)->nome ?? 'Cidade indisponível' }}/{{ optional(optional(optional($bairro)->cidade)->estado)->uf ?? '--' }}
        </td>
        <td>
          {{ $solicitacao->status ? StatusOrcamento::getLabel( $solicitacao->status) : 'Sem
                                    status' }}
        </td>
        <td>
          <label clas="badge badge-default"><b>{{$solicitacao->titulo_contrato}}</b></label><br>
          @if($solicitacao->formato_contrato_atual==2)
          <a href="{{$solicitacao->contrato_assinado ? $solicitacao->contrato_assinado : "
                                        https://admin2.casadosindico.srv.br/storage/".$solicitacao->contrato}}" class="btn btn-success" target="_blank">Ver contrato</a>
          @elseif($solicitacao->formato_contrato_atual==1)
          <a href="{{$solicitacao->contrato_original ? $solicitacao->contrato_original : "
                                        https://admin2.casadosindico.srv.br/storage/".$solicitacao->contrato}}" class="btn btn-secondary" target="_blank">Ver contrato</a>
          <a href="{{$solicitacao->contrato_assinado ? $solicitacao->contrato_assinado : "
                                        https://admin2.casadosindico.srv.br/storage/".$solicitacao->contrato}}" class="btn btn-success" target="_blank">Ver contrato assinado</a>
          @else
          <a class="btn btn-danger" href="{{ route('admin_franqueado.orcamentos.edit', $solicitacao->id ) }}#upload">Upload
            do
            contrato</a>
          @endif

        </td>
        <td align="right">

          <form method="POST" action="{!! route('admin_franqueado.orcamentos.destroy', $solicitacao->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}

            <div class="btn-group btn-group-xs pull-right" role="group">
              <a href="{{ route('admin_franqueado.orcamentos.edit', $solicitacao->id ) }}" class="btn btn-primary" title="Editar solicitacao">
                <i class="fa fa-pencil"></i>
              </a>

              <button type="submit" class="btn btn-danger" title="Remover solicitacao" onclick="return confirm('Deseja realmete excluir o solicitacao {{$solicitacao->id}}?')">
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
