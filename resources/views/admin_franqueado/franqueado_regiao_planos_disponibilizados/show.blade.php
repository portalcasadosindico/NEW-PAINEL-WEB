@extends('admin_franqueado.layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
            	<h4 class="card-title">Visualizando dados de franqueado regiao</h4>
                <dl class="dl-horizontal">
                    <dt>Status </dt>
                    <dd>{{ $franqueado_regiao_plano_disponibilizado->planoDisponivelFranqueado->statusPlano }}</dd>
                    <dt>Franqueado</dt>
                    <dd>{{ $franqueado_regiao_plano_disponibilizado->franqueadoRegiao->franqueado->nome }}</dd>
                    <dt>Regiao</dt>
                    <dd>{{ $franqueado_regiao_plano_disponibilizado->franqueadoRegiao->regiao ? $franqueado_regiao_plano_disponibilizado->franqueadoRegiao->regiao->nome : "sem região" }}</dd>
                    <dt>Usuario sistema admin</dt>
                    <dd>{{ $franqueado_regiao_plano_disponibilizado->franqueadoRegiao->usuarioSistemaAdmin->nome }}</dd>
                </dl>

                <div class="pull-right">
                    <form method="POST" action="{!! route('admin_franqueado.franqueado_regiao_planos_disponibilizados.destroy', $franqueado_regiao_plano_disponibilizado->id) !!}" accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin_franqueado.franqueado_regiao_planos_disponibilizados.index') }}" class="btn btn-primary" title="Ver todos franqueado_regiao">
                                <i data-feather="list"></i>
                            </a>

                            <a href="{{ route('admin_franqueado.franqueado_regiao_planos_disponibilizados.create') }}" class="btn btn-success" title="Novo franqueado_regiao">
                                <i data-feather="plus"></i>
                            </a>

                            <a href="{{ route('admin_franqueado.franqueado_regiao_planos_disponibilizados.edit', $franqueado_regiao_plano_disponibilizado->id ) }}" class="btn btn-primary" title="Editar franqueado_regiao">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <button type="submit" class="btn btn-danger" title="Remover franqueado_regiao" onclick="return confirm('Deseja realmete excluir o franqueado_regiao {{$franqueado_regiao_plano_disponibilizado->nome}}?')">
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
