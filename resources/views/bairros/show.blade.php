@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
            	<h4 class="card-title">Visualizando dados de bairros</h4>
                <dl class="dl-horizontal">
                    <dt>Nome </dt>
                    <dd>{{ $bairro->nome }}</dd>
                    <dt>Chave</dt>
                    <dd>{{ $bairro->chave }}</dd>
                    <dt>Cidade</dt>
                    <dd>{{ $bairro->cidade->nome }}</dd>
                    <dt>Regiao</dt>
                    <dd>{{ $bairro->regiao ? $bairro->regiao->nome : "sem região" }}</dd>
                </dl>

                <div class="pull-right">
                    <form method="POST" action="{!! route('bairros.destroy', $bairro->id) !!}" accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('bairros.index') }}" class="btn btn-primary" title="Ver todos bairros">
                                <i data-feather="list"></i>
                            </a>

                            <a href="{{ route('bairros.create') }}" class="btn btn-success" title="Novo bairro">
                                <i data-feather="plus"></i>
                            </a>

                            <a href="{{ route('bairros.edit', $bairro->id ) }}" class="btn btn-primary" title="Editar bairro">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <button type="submit" class="btn btn-danger" title="Remover bairro" onclick="return confirm('Deseja realmete excluir o bairro {{$bairro->nome}}?')">
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
