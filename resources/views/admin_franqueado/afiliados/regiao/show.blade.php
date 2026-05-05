@extends('admin_franqueado.layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
            	<h4 class="card-title">Regiao do afiliado {{$afiliado_regiao->afiliado->razao_social}}</h4>
                <dl class="dl-horizontal">
                    <dt>Nome</dt>
                    <dd>{{ $afiliado_regiao->regiao ? $afiliado_regiao->regiao->nome : "sem região" }}</dd>
                    <dt>Descricao</dt>
                    <dd>{{ $afiliado_regiao->regiao ? $afiliado_regiao->regiao->descricao : "sem região" }}</dd>
                    <dt>Nome plano</dt>
                    <dd>{{ $afiliado_regiao->planoassinaturaafiliadoregiao->nome }}</dd>
                    <dt>Valor</dt>
                    <dd>{{ $afiliado_regiao->planoassinaturaafiliadoregiao->valor }}</dd>
                    <dt>Valor comissao</dt>
                    <dd>{{ $afiliado_regiao->planoassinaturaafiliadoregiao->valor_comissao }}</dd>
                    <dt>Data cadastro</dt>
                    <dd>{{ $afiliado_regiao->planoassinaturaafiliadoregiao->data_cadastro }}</dd>
                    <dt>Status plano</dt>
                    <dd>{{ $afiliado_regiao->planoassinaturaafiliadoregiao->statusPlano }}</dd>
                </dl>

                <div class="pull-right">
                <button class="btn btn-light">
                <a href="{{route('admin_franqueado.afiliados.index')}}">Voltar</a>
                </button>
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
