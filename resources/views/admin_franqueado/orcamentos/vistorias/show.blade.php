@extends('admin_franqueado.layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
            	<h4 class="card-title">Visualizando dados de vistorias</h4>
                <dl class="dl-horizontal">
                    <dt>Descricao</dt>
                    <dd>{{ $vistoria->descricao }}</dd>
                    <dt>Data vistoria</dt>
                    <dd>{{ $vistoria->data_vistoria }}</dd>
                    <dt>Data checkin</dt>
                    <dd>{{ $vistoria->data_checkin }}</dd>
                    <dt>Latitude checkin</dt>
                    <dd>{{ $vistoria->latitude_checkin }}</dd>
                    <dt>Longitude checkin</dt>
                    <dd>{{ $vistoria->longitude_checkin }}</dd>
                    <dt>Data checkout</dt>
                    <dd>{{ $vistoria->data_checkout }}</dd>
                    <dt>Latitude checkout</dt>
                    <dd>{{ $vistoria->latitude_checkout }}</dd>
                    <dt>Longitude checkout</dt>
                    <dd>{{ $vistoria->longitude_checkout }}</dd>
                    <dt>Vistoriador</dt>
                    <dd>{{ $vistoria->vistoriador->nome }}</dd>
                    <dt>Orcamento</dt>
                    <dd>{{ $vistoria->orcamento->nome }}</dd>
                </dl>

                <div class="pull-right">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin_franqueado.orcamentos.index') }}" class="btn btn-primary" title="Voltar">
                               Voltar
                            </a>
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
