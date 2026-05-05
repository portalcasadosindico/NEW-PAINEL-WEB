@extends('admin_franqueado.layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Novo Franqueado Regiao</h6>
        <form method="POST" class="forms-sample" action="{{ route('admin_franqueado.franqueado_regiao_planos_disponibilizados.store') }}">
            {{ csrf_field() }}
            @include ('admin_franqueado.franqueado_regiao_planos_disponibilizados.form', [
                                        'franqueado_regiao_plano_disponibilizado' => null,
                                      ])
          <button type="submit" class="btn btn-primary mr-2">Salvar</button>
          <a class="btn btn-light" href="{{route('admin_franqueado.franqueado_regiao_planos_disponibilizados.index')}}">Cancelar</a>
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