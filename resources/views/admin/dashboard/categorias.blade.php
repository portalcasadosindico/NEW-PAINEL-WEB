@if (count($categorias) == 0)
<div class="panel-body text-center">
  <i class="fa fa-tint fa-5x"></i>
  <h4>Nenhuma categoria</h4>
</div>
@else
<div class="panel-body panel-body-with-table" style="max-height: 600px; overflow: auto;">
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th width="200">Categoria</th>
          <th>Solicitações</th>
          <th>Afiliados</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($categorias as $categoria)
        <td>
          <h5>{{ $categoria->nome }}</h5>
        </td>
        <td>
          <label class="badge badge-success"> Concluidas:
            {{ $categoria->concluidas }}</label>
          <label class="badge badge-danger"> Canceladas:
            {{ $categoria->canceladas }}</label>
          <label class="badge badge-info"> Total:
            {{ sizeof($categoria->orcamentos) }}</label>
        </td>
        <td>
          {{ $categoria->afiliados }}
        </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif