@extends('layout.master')

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
                   <h4 class="mt-5 mb-5">Listagem de estados</h4>
               </div>
   
               <div class="btn-group btn-group-sm pull-right" role="group">
                   <a href="{{ route('estados.create') }}" class="btn btn-success" title="Criar novo estado">
                       <i data-feather="plus"></i> <span>Novo estado</span>
                   </a>
               </div>
   
           </div>
           
           
           
           @if(count($estados) == 0)
               <div class="panel-body text-center">
                   <h4>Nenhum estado listado</h4>
               </div>
           @else
               <div class="panel-body panel-body-with-table">
               <div class="table-responsive">
               <table data-page-length="25" id="dataTableExample" class="table table-striped dataTable no-footer" role="grid" aria-describedby="dataTableExample_info">
                       <thead>
                           <tr>
                               <th width="200">Nome</th>
                               <th>Uf</th>
                               <th>Cidades</th>
                               <th></th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($estados as $estado)
                               <td>{{ $estado->nome }}</td>
                               <td>{{ $estado->uf }}</td>    
                               <td>

                                    <a href="<?php echo getenv('APP_URL'); ?>/admin/cidades/estado_id/{{$estado->id}}" class="btn btn-info" title="Ver cidades">
                                    <i class="fa fa-eye"></i> Cidades
                                    </a>

                               </td>              
                               <td align="right">

                                   <form method="POST" action="{!! route('estados.destroy', $estado->id) !!}" accept-charset="UTF-8">
                                   <input name="_method" value="DELETE" type="hidden">
                                   {{ csrf_field() }}
   
                                       <div class="btn-group btn-group-xs pull-right" role="group">
                                           <a href="{{ route('estados.show', $estado->id ) }}" class="btn btn-info" title="Ver estado">
                                               <i class="fa fa-eye"></i>
                                           </a>
                                           <a href="{{ route('estados.edit', $estado->id ) }}" class="btn btn-primary" title="Editar estado">
                                               <i class="fa fa-pencil"></i>
                                           </a>
   
                                           <button type="submit" class="btn btn-danger" title="Remover estado" onclick="return confirm('Deseja realmete excluir o estado {{$estado->nome}}?')">
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