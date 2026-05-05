@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
            	<h4 class="card-title">Visualizando dados de regiaos</h4>
                <dl class="dl-horizontal">
                    <dt>Nome</dt>
                    <dd>{{ $regiao->nome }}</dd>
                    <dt>Descricao</dt>
                    <dd>{{ $regiao->descricao }}</dd>
                </dl>

                <div class="pull-right">
                    <form method="POST" action="{!! route('regioes.destroy', $regiao->id) !!}" accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('regioes.index') }}" class="btn btn-primary" title="Ver todas regioes">
                                <i data-feather="list"></i>
                            </a>

                            <a href="{{ route('regioes.create') }}" class="btn btn-success" title="Nova regiao">
                                <i data-feather="plus"></i>
                            </a>

                            <a href="{{ route('regioes.edit', $regiao->id ) }}" class="btn btn-primary" title="Editar regiao">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <button type="submit" class="btn btn-danger" title="Remover regiao" onclick="return confirm('Deseja realmete excluir o regiao {{$regiao->nome}}?')">
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
