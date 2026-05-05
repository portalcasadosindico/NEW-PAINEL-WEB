@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
            	<h4 class="card-title">Visualizando dados de estados</h4>
                <dl class="dl-horizontal">
                    <dt>Nome </dt>
                    <dd>{{ $estado->nome }}</dd>
                    <dt>Uf</dt>
                    <dd>{{ $estado->uf }}</dd>
                    <dt>Chave</dt>
                    <dd>{{ $estado->chave }}</dd>
                </dl>

                <div class="pull-right">
                    <form method="POST" action="{!! route('estados.destroy', $estado->id) !!}" accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('estados.index') }}" class="btn btn-primary" title="Ver todos estados">
                                <i data-feather="list"></i>
                            </a>

                            <a href="{{ route('estados.create') }}" class="btn btn-success" title="Novo estado">
                                <i data-feather="plus"></i>
                            </a>

                            <a href="{{ route('estados.edit', $estado->id ) }}" class="btn btn-primary" title="Editar estado">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <button type="submit" class="btn btn-danger" title="Remover estado" onclick="return confirm('Deseja realmete excluir o estado {{$estado->nome}}?')">
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
