@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
            	<h4 class="card-title">Visualizando dados de Plano</h4>
                <dl class="dl-horizontal">
                    <dt>Nome</dt>
                    <dd>{{ $plano_disponivel_franqueado->nome }}</dd>
                    <dt>Descricao</dt>
                    <dd>{{ $plano_disponivel_franqueado->descricao }}</dd>
                    <dt>Valor</dt>
                    <dd>{{ $plano_disponivel_franqueado->valor }}</dd>
                    <dt>Valor comissao</dt>
                    <dd>{{ $plano_disponivel_franqueado->valor_comissao }}</dd>
                    <dt>Quantidade meses vigencia</dt>
                    <dd>{{ $plano_disponivel_franqueado->quantidade_meses_revigencia }}</dd>
                    <dt>Dias de teste</dt>
                    <dd>{{ $plano_disponivel_franqueado->dias_trial }}</dd>
                    <dt>Usuario sistema admin id</dt>
                    <dd>{{ $plano_disponivel_franqueado->usuarioSistemaAdmin->nome }}</dd>
                    <dt>Regiao id</dt>
                    <dd>{{ $plano_disponivel_franqueado->regiao ? $plano_disponivel_franqueado->regiao->nome : "sem região" }}</dd>
                    <dt>Status</dt>
                    <dd>{{ $plano_disponivel_franqueado->statusPlano }}</dd>
                </dl>

                <div class="pull-right">
                    <form method="POST" action="{!! route('planos_disponiveis_franqueado.destroy', $plano_disponivel_franqueado->id) !!}" accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('planos_disponiveis_franqueado.index') }}" class="btn btn-primary" title="Ver todos plano_disponivel_franqueados">
                                <i data-feather="list"></i>
                            </a>

                            <a href="{{ route('planos_disponiveis_franqueado.create') }}" class="btn btn-success" title="Novo plano_disponivel_franqueado">
                                <i data-feather="plus"></i>
                            </a>

                            <a href="{{ route('planos_disponiveis_franqueado.edit', $plano_disponivel_franqueado->id ) }}" class="btn btn-primary" title="Editar plano_disponivel_franqueado">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <button type="submit" class="btn btn-danger" title="Remover plano_disponivel_franqueado" onclick="return confirm('Deseja realmete excluir o plano_disponivel_franqueado {{$plano_disponivel_franqueado->nome}}?')">
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
@endpush
