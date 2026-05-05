@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
            	<h4 class="card-title">Visualizando dados de ruas</h4>
                <dl class="dl-horizontal">
                    <dt>Nome</dt>
                    <dd>{{ $rua->nome }}</dd>
                    <dt>Cep</dt>
                    <dd>{{ $rua->cep }}</dd>
                    <dt>Chave</dt>
                    <dd>{{ $rua->chave }}</dd>
                    <dt>Bairro</dt>
                    <dd>{{ $rua->bairro->nome }}</dd>
                </dl>

                <div class="pull-right">
                    <form method="POST" action="{!! route('ruas.destroy', $rua->id) !!}" accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('ruas.index') }}" class="btn btn-primary" title="Ver todos ruas">
                                <i data-feather="list"></i>
                            </a>

                            <a href="{{ route('ruas.create') }}" class="btn btn-success" title="Novo rua">
                                <i data-feather="plus"></i>
                            </a>

                            <a href="{{ route('ruas.edit', $rua->id ) }}" class="btn btn-primary" title="Editar rua">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <button type="submit" class="btn btn-danger" title="Remover rua" onclick="return confirm('Deseja realmete excluir o rua {{$rua->nome}}?')">
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
