@extends('admin_franqueado.layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Editando Franqueado Regiao</h6>
        <form method="POST" class="forms-sample" action="{{ route('admin_franqueado.franqueado_regiao_planos_disponibilizados.update',$franqueado_regiao_plano_disponibilizado->id) }}">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('admin_franqueado.franqueado_regiao_planos_disponibilizados.form', [
                                        'franqueado_regiao_plano_disponibilizado' => $franqueado_regiao_plano_disponibilizado,
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