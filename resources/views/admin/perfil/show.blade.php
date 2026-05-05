@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
            	<h4 class="card-title">Visualizando dados de usuario admin</h4>
                <dl class="dl-horizontal">
                    <dt>Nome</dt>
                    <dd>{{ $usuario_sistema_admin->nome }}</dd>
                    <dt>Email</dt>
                    <dd>{{ $usuario_sistema_admin->email }}</dd>
                </dl>

                <div class="pull-right">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin.profile.edit', $usuario_sistema_admin->id ) }}" class="btn btn-primary" title="Editar usuario_sistema_admin">
                                <i class="fa fa-pencil"></i>
                            </a>

                        </div>
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
