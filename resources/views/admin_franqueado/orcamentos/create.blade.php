@extends('admin_franqueado.layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h6 class="card-title">Nova Solicitação</h6>
      <form method="POST" id="form-edit-orcamento" class="forms-sample"
        action="{{ route('admin_franqueado.orcamentos.store') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        @include ('admin_franqueado.orcamentos.form', [
        'orcamento' => null,
        ])
        <button onclick="validarForm()" type="button" class="btn btn-primary mr-2 hide-btn-send">Salvar</button>
        <a href="{{ route('admin_franqueado.orcamentos.index') }}" class="btn btn-light">Cancelar</a>
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