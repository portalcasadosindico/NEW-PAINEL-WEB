@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Visualizando dados de canalAtendimentos</h4>
        <dl class="dl-horizontal">
            <dt>Nome</dt>
            <dd>{{ $canalAtendimento->nome }}</dd>
            <dt>Email</dt>
            <dd>{{ $canalAtendimento->email }}</dd>
            <dt>Telefone</dt>
            <dd>{{ $canalAtendimento->telefone }}</dd>
            <dt>Data Cadastro</dt>
            <dd>{{ $canalAtendimento->data_cadastro }}</dd>
            <dt>Deleted At</dt>
            <dd>{{ $canalAtendimento->deleted_at }}</dd>

        </dl>

        <div class="pull-right">
            <form method="POST" action="{!! route('canal_atendimentos.destroy', $canalAtendimento->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('canal_atendimentos.index') }}" class="btn btn-primary" title="Ver todos canalAtendimentos">
                        <i data-feather="list"></i>
                    </a>

                    <a href="{{ route('canal_atendimentos.create') }}" class="btn btn-success" title="Novo canalAtendimento">
                        <i data-feather="plus"></i>
                    </a>

                    <a href="{{ route('canal_atendimentos.edit', $canalAtendimento->id ) }}" class="btn btn-primary" title="Editar canalAtendimento">
                        <i class="fa fa-pencil"></i>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Remover canalAtendimento" onclick="return confirm('Deseja realmete excluir o canalAtendimento {{$canalAtendimento->nome}}?')">
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
