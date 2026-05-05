@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
            	<h4 class="card-title">Visualizando dados de parceiros</h4>
                <dl class="dl-horizontal">
                    <dt>Nome</dt>
                    <dd>{{ $parceiro->nome }}</dd>
                    <dt>Email</dt>
                    <dd>{{ $parceiro->email }}</dd>
                    <dt>Link</dt>
                    <dd>{{ $parceiro->link }}</dd>
                    <dt>Nome responsavel</dt>
                    <dd>{{ $parceiro->nome_responsavel }}</dd>
                    <dt>Telefone</dt>
                    <dd>{{ $parceiro->telefone }}</dd>
                    <dt>Status</dt>
                    <dd>{{ $parceiro->status }}</dd>
                    <dt>Logo</dt>
                    <dd>
                        <img src="{{ Storage::url($parceiro->logo) }}" style="width: 150px" alt="logo">
                    </dd>
                </dl>

                <div class="pull-right">
                    <form method="POST" action="{!! route('parceiros.destroy', $parceiro->id) !!}" accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('parceiros.index') }}" class="btn btn-primary" title="Ver todos parceiros">
                                <i data-feather="list"></i>
                            </a>

                            <a href="{{ route('parceiros.create') }}" class="btn btn-success" title="Novo parceiro">
                                <i data-feather="plus"></i>
                            </a>

                            <a href="{{ route('parceiros.edit', $parceiro->id ) }}" class="btn btn-primary" title="Editar parceiro">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <button type="submit" class="btn btn-danger" title="Remover parceiro" onclick="return confirm('Deseja realmete excluir o parceiro {{$parceiro->nome}}?')">
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
