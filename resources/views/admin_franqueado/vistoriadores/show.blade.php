@extends('admin_franqueado.layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
            	<h4 class="card-title">Visualizando dados de vistoriadores</h4>
                <dl class="dl-horizontal">
                    <dt>Nome </dt>
                    <dd>{{ $vistoriador->nome }}</dd>
                    <dt>Usuario app</dt>
                    <dd>{{ $vistoriador->usuarioApp->email }}</dd>
                </dl>

                <div class="pull-right">
                    <form method="POST" action="{!! route('admin_franqueado.vistoriadores.destroy', $vistoriador->id) !!}" accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin_franqueado.vistoriadores.index') }}" class="btn btn-primary" title="Ver todos vistoriadores">
                                <i data-feather="list"></i>
                            </a>

                            <a href="{{ route('admin_franqueado.vistoriadores.create') }}" class="btn btn-success" title="Novo vistoriador">
                                <i data-feather="plus"></i>
                            </a>

                            <a href="{{ route('admin_franqueado.vistoriadores.edit', $vistoriador->id ) }}" class="btn btn-primary" title="Editar vistoriador">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <button type="submit" class="btn btn-danger" title="Remover vistoriador" onclick="return confirm('Deseja realmete excluir o vistoriador {{$vistoriador->nome}}?')">
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
