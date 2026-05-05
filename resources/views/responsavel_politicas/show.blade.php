@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Visualizando dados de responsavelPoliticas</h4>
        <dl class="dl-horizontal">
            <dt>Nome</dt>
            <dd>{{ $responsavelPolitica->nome }}</dd>
            <dt>Email</dt>
            <dd>{{ $responsavelPolitica->email }}</dd>
            <dt>Telefone</dt>
            <dd>{{ $responsavelPolitica->telefone }}</dd>
            <dt>Cpf</dt>
            <dd>{{ $responsavelPolitica->cpf }}</dd>
            <dt>Data Cadastro</dt>
            <dd>{{ $responsavelPolitica->data_cadastro }}</dd>
            <dt>Politica Privacidade</dt>
            <dd>{{ optional($responsavelPolitica->PoliticaPrivacidade)->titulo }}</dd>
            <dt>Deleted At</dt>
            <dd>{{ $responsavelPolitica->deleted_at }}</dd>

        </dl>

        <div class="pull-right">
            <div class="btn-group btn-group-sm" role="group">
                <a href="{{ route('responsavel_politicas.index') }}" class="btn btn-primary" title="Ver todos responsavelPoliticas">
                    <i data-feather="list"></i>
                </a>

                <a href="{{ route('responsavel_politicas.create') }}" class="btn btn-success" title="Novo responsavelPolitica">
                    <i data-feather="plus"></i>
                </a>

                <a href="{{ route('responsavel_politicas.edit', $responsavelPolitica->id ) }}" class="btn btn-primary" title="Editar responsavelPolitica">
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
