@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
            	<h4 class="card-title">Visualizando dados de cidades</h4>
                <dl class="dl-horizontal">
                    <dt>Nome </dt>
                    <dd>{{ $cidade->nome }}</dd>
                    <dt>Chave</dt>
                    <dd>{{ $cidade->chave }}</dd>
                    <dt>Estado</dt>
                <dd>{{ $cidade->estado->nome }}</dd>
                </dl>

                <div class="pull-right">
                    <form method="POST" action="{!! route('cidades.destroy', $cidade->id) !!}" accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('cidades.index') }}" class="btn btn-primary" title="Ver todos cidades">
                                <i data-feather="list"></i>
                            </a>

                            <a href="{{ route('cidades.create') }}" class="btn btn-success" title="Novo cidade">
                                <i data-feather="plus"></i>
                            </a>

                            <a href="{{ route('cidades.edit', $cidade->id ) }}" class="btn btn-primary" title="Editar cidade">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <button type="submit" class="btn btn-danger" title="Remover cidade" onclick="return confirm('Deseja realmete excluir o cidade {{$cidade->nome}}?')">
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
