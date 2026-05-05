@extends('admin_franqueado.layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
                @if($orcamento->modo=="debug")
                    <label class="badge badge-danger mb-2">MODO TESTE</label>
                    <br>
                @endif
            	<h4 class="card-title">Visualizando dados de orcamentos</h4>
                <dl class="dl-horizontal">
                    <dt>Nome</dt>
                    <dd>{{ $orcamento->nome }}</dd>
                    <dt>Descricao</dt>
                    <dd>{{ $orcamento->descricao }}</dd>
                    <dt>Status</dt>
                    <dd>{{ $orcamento->status }}</dd>
                    <dt>Status sindico</dt>
                    <dd>{{ $orcamento->status_sindico }}</dd>
                    <dt>Status afiliado</dt>
                    <dd>{{ $orcamento->status_afiliado }}</dd>
                    <dt>Condominio</dt>
                    <dd>{{ $orcamento->condominio->nome }}</dd>
                    <dt>Afiliado</dt>
                    <dd>{{ $orcamento->afiliado()->withTrashed()->first()->razao_social }}</dd>
                    <dt>Categoria</dt>
                    <dd>{{ $orcamento->categoria->nome }}</dd>
                </dl>

                <div class="pull-right">
                    <form method="POST" action="{!! route('admin_franqueado.orcamentos.destroy', $orcamento->id) !!}" accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin_franqueado.orcamentos.index') }}" class="btn btn-primary" title="Ver todos orcamentos">
                                <i data-feather="list"></i>
                            </a>

                            <a href="{{ route('admin_franqueado.orcamentos.create') }}" class="btn btn-success" title="Novo orcamento">
                                <i data-feather="plus"></i>
                            </a>

                            <a href="{{ route('admin_franqueado.orcamentos.edit', $orcamento->id ) }}" class="btn btn-primary" title="Editar orcamento">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <button type="submit" class="btn btn-danger" title="Remover orcamento" onclick="return confirm('Deseja realmete excluir o orcamento {{$orcamento->nome}}?')">
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
