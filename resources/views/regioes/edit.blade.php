@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Editando Região</h6>
        <form method="POST" class="forms-sample" action="{{ route('regioes.update',$regiao->id) }}">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('regioes.form', [
                                        'regiao' => $regiao,
                                      ])
          <button type="submit" class="btn btn-primary mr-2">Salvar</button>
          <button class="btn btn-light" name="cancel">Cancelar</button>
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
  <script src="{{ asset('assets/js/support/dynamicDropRegiao.js') }}"></script>
@endpush