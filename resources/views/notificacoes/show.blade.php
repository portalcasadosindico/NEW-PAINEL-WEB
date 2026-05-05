@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
            	<h4 class="card-title">Visualizando Notificação  / E-mail simples</h4>
                <dl class="dl-horizontal">
                    <dt>Nome </dt>
                    <dd>{{ $notificacao->titulo }}</dd>
                    <dt>Mensagem</dt>
                    <dd>{{ $notificacao->corpo }}</dd>
                </dl>
        	</div>
        </div>

        <div class="card">
        	<div class="card-body">
                <h4 class="card-title">Envios realizados por PUSH Notification</h4>
                <table class="table dataTable">
                    <thead>
                        <th>Nome</th>
                        <th>E-mail</th>
                    </thead>
                    <tbody>
                        @foreach ($envios_push as $item)
                            <tr>
                                <td>{{$item->nome}}</td>
                                <td>{{$item->email}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        	</div>
        </div>

        <div class="card">
        	<div class="card-body">
                <h4 class="card-title">Envios realizados por e-mail</h4>
                <table class="table dataTable">
                    <thead>
                        <th>Nome</th>
                        <th>E-mail</th>
                    </thead>
                    <tbody>
                        @foreach ($envios_email as $item)
                            <tr>
                                <td>{{$item->nome}}</td>
                                <td>{{$item->email}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        	</div>
        </div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/dragula/dragula.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/dragula.js') }}"></script>
@endpush
