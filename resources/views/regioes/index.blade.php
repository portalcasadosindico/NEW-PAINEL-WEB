<?php use App\Models\FranqueadoRegiao; ?>
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
                   <h4 class="mt-5 mb-5">Listagem de regioes</h4>
               </div>
   
               <div class="btn-group btn-group-sm pull-right" role="group">
                   <a href="{{ route('regioes.create') }}" class="btn btn-success" title="Criar nova regiao">
                       <i data-feather="plus"></i> <span>Nova regiao</span>
                   </a>
               </div>
   
           </div>
           
           
           
           @if(count($regioes) == 0)
               <div class="panel-body text-center">
                   <h4>Nenhuma regiao listada</h4>
               </div>
           @else
               <div class="panel-body panel-body-with-table">
               <div class="table-responsive">
               <table data-page-length="25" id="dataTableExample" class="table table-striped no-footer" role="grid" aria-describedby="dataTableExample_info">
                       <thead>
                           <tr>
                               <th width="200">Nome</th>
                               <th width="200">Franqueado</th>
                               <th></th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($regioes as $regiao)

                       <?php

                        

                        $regiaoFranqueado = FranqueadoRegiao::where("regiao_id", $regiao->id)->orderBy("id", "desc")->first();
                       ?>
                               <td>{{ $regiao->nome }}</td>
                               <td>{{ ($regiaoFranqueado ? ($regiaoFranqueado->franqueado?$regiaoFranqueado->franqueado->nome:'Sem franquia') : 'Sem franquia') }}</td>

                               <td align="right">

                                   <form method="POST" action="{!! route('regioes.destroy', $regiao->id) !!}" accept-charset="UTF-8">
                                   <input name="_method" value="DELETE" type="hidden">
                                   {{ csrf_field() }}
   
                                       <div class="btn-group btn-group-xs pull-right" role="group">
                                           <a href="{{ route('regioes.show', $regiao->id ) }}" class="btn btn-info" title="Ver regiao">
                                               <i class="fa fa-eye"></i>
                                           </a>
                                           <a href="{{ route('regioes.edit', $regiao->id ) }}" class="btn btn-primary" title="Editar regiao">
                                               <i class="fa fa-pencil"></i>
                                           </a>
   
                                           <button type="submit" class="btn btn-danger" title="Remover regiao" onclick="return confirm('Deseja realmete excluir o regiao {{$regiao->nome}}?')">
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