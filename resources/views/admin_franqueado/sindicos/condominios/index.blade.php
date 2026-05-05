@extends('admin_franqueado.layout.master')

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
                   <h4 class="mt-5 mb-5">Listagem de condominios</h4>
               </div>
   
               <div class="btn-group btn-group-sm pull-right" role="group">
                   
               </div>
   
           </div>
           
           
           
           @if(count($condominios) == 0)
               <div class="panel-body text-center">
                   <h4>Nenhum condominio listado</h4>
               </div>
           @else
               <div class="panel-body panel-body-with-table">
               <div class="table-responsive">
               <table class="table table-striped ">
                       <thead>
                           <tr>
                               <th width="200">Nome</th>
                               <th>Endereco</th>
                               <th>Regiao</th>
                               <th>Solicitacoes</th>
                               <th></th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($condominios as $condominio)
                               <td>{{ $condominio->nome }}</td>
                               <td>{{ $condominio->endereco }}</td>                  
                               <td>{{ $condominio->regiao ? $condominio->regiao->nome : "sem região" }}</td>                  
                               <td>
                               <div class="btn-group btn-group-xs pull-right" role="group">
                                           <a href="{{ route('admin_franqueado.sindicos.condominios.orcamentos', $condominio->id) }}" class="btn btn-info" title="Ver condominio">
                                              Ver
                                           </a>
                               </div>
                               </td>                  
                               <td align="right">

                               <form method="POST" action="{!! route('admin_franqueado.sindicos.condominios.destroy', $condominio->id) !!}" accept-charset="UTF-8">
                                   <input name="_method" value="DELETE" type="hidden">
                                   {{ csrf_field() }}
                                       <div class="btn-group btn-group-xs pull-right" role="group">
   
                                           <button type="submit" class="btn btn-danger" title="Remover condominio" onclick="return confirm('Deseja realmete excluir o condominio {{$condominio->nome}}?')">
                                               <i class="fa fa-trash"></i>
                                           </button>
                                       </div>
                                       </form>  
   
                               </td>
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