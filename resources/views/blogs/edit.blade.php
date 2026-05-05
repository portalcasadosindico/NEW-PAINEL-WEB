@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Editando Post</h6>
            <form method="POST" class="forms-sample" action="{{ route('blogs.update',$blog->id) }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">
                @include ('blogs.form', [
                'blog' => $blog,
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
<script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/dragula.js') }}"></script>
<script src="{{ asset('assets/js/tinymce.js') }}"></script>
@endpush
