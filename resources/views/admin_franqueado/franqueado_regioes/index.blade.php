@extends('admin_franqueado.layout.master')
<?php

use App\Models\Afiliado;

use Illuminate\Support\Facades\Auth;


$numero_afiliados = Afiliado::join('afiliado_regiao', 'afiliado_regiao.afiliado_id', 'afiliado.id')
->whereIn('afiliado_regiao.regiao_id', function ($query) {
    $query->select('regiao_id')
        ->from('franqueado_regiao')
        ->where('franqueado_regiao.status', "ativo")
        ->where('franqueado_regiao.franqueado_id', Auth::guard('franqueados')->user()->id);
})->count();
?>
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="panel panel-secondary">

    @if(Session::has('success_message'))
         <div class="alert alert-success">
             <span class="glyphicon glyphicon-ok"></span>
             {!! session('success_message') !!}

             <button type="button" class="close" data-dismiss="alert" aria-label="close">
                 <span aria-hidden="true">&times;</span>
             </button>

         </div>
     @endif
            <div class="panel-heading clearfix">

               <div class="pull-left">
                   <h4 class="mt-5 mb-5">Listagem das suas regiões</h4>
               </div>

           </div>



           @if(count($franqueado_regioes) == 0)
               <div class="panel-body text-center">
                   <h4>Nenhum franqueado região listada</h4>
               </div>
           @else
               <div class="panel-body panel-body-with-table">
               <div class="table-responsive">
               <table class="table table-striped ">
                       <thead>
                           <tr>
                               <th width="200">Nome regiao</th>
                               <th>Número afiliados</th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($franqueado_regioes as $franqueado_regiao)
                               <td>{{ $franqueado_regiao->regiao ? $franqueado_regiao->regiao->nome : "sem região" }}</td>
                               <td>{{ $numero_afiliados }}</td>
                           </tr>
                       @endforeach
                       </tbody>
                   </table>
             </div>
           </div>
               @endif


@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/dragula/dragula.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/dragula.js') }}"></script>
@endpush