@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Visualizando dados de politica Privacidades</h4>
        <dl class="dl-horizontal">
            <dt>Titulo</dt>
            <dd>{{ $politicaPrivacidade->titulo }}</dd>
            <dt>Texto</dt>
            <dd>{{ $politicaPrivacidade->texto }}</dd>
            <dt>Data Cadastro</dt>
            <dd>{{ $politicaPrivacidade->data_cadastro }}</dd>
            <dt>Deleted At</dt>
            <dd>{{ $politicaPrivacidade->deleted_at }}</dd>

        </dl>

        <div class="pull-right">
            <div class="btn-group btn-group-sm" role="group">
                <a href="{{ route('politica_privacidades.index') }}" class="btn btn-primary" title="Ver todos politicaPrivacidades">
                    <i data-feather="list"></i>
                </a>

                <a href="{{ route('politica_privacidades.create') }}" class="btn btn-success" title="Novo politicaPrivacidade">
                    <i data-feather="plus"></i>
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
