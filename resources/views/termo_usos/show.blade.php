@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Visualizando dados de termo de uso</h4>
        <dl class="dl-horizontal">
            <dt>titulo </dt>
            <dd>{{ $termoUso->titulo }}</dd>
            <dt>Texto</dt>
            <dd>{{ $termoUso->texto }}</dd>
            <dt>Data Cadastro</dt>
            <dd>{{ $termoUso->data_cadastro }}</dd>
        </dl>

        <div class="pull-right">
            <div class="btn-group btn-group-sm" role="group">
                <a href="{{ route('termo_usos.index') }}" class="btn btn-primary" title="Ver todos termoUsos">
                    <i data-feather="list"></i>
                </a>

                <a href="{{ route('termo_usos.create') }}" class="btn btn-success" title="Novo termoUso">
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
