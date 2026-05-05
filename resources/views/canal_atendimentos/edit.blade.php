@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="col-md-8 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Editando Post</h6>
            <form method="POST" class="forms-sample" action="{{ route('canal_atendimentos.update',$canalAtendimento->id) }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">
                @include ('canal_atendimentos.form', [
                'canalAtendimento' => $canalAtendimento,
                ])
                <button type="submit" class="btn btn-primary mr-2">Salvar</button>
                <a class="btn btn-light" href="{{route('canal_atendimentos.index')}}">Cancelar</a>
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
