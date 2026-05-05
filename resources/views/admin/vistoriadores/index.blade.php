<?php
    
use App\Uteis\Formatacao;
?>
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
                <a href="{{ route('admin.vistoriadores.create') }}" class="btn btn-success" title="Criar novo vistoriador">
                       <i data-feather="plus"></i> <span>Novo vistoriador</span>
                   </a>
                   <h4 class="mt-5 mb-5">Listagem de vistoriadores</h4>
               </div>
   


               <div class="btn-group btn-group-sm pull-right" style="float: right;" role="group">
                    <div style="position: relative; top: -12px;">
                        <label>Filtro por franqueado</label><br>
                        <select onchange="pesquisar(this.value)">
                            <option value="0">Todos os franqueados</option>
                            @foreach($franqueados as $franqueado)
                                <option <?php


if($franqueado_id==$franqueado->id) echo "selected"; ?> value="{{$franqueado->id}}">{{$franqueado->nome}}</option>
                            @endforeach
                        </select>
                    </div>
               </div>
   
           </div>
           
           
           
           @if(count($vistoriadores) == 0)
               <div class="panel-body text-center">
                   <h4>Nenhum vistoriador listado</h4>
               </div>
           @else
               <div class="panel-body panel-body-with-table">
               <div class="table-responsive">
               <table data-page-length="25" id="dataTableExample" class="table table-striped dataTable no-footer" role="grid" aria-describedby="dataTableExample_info">
                       <thead>
                           <tr>
                               <th width="200">Nome</th>
                               <th>E-mail</th>
                               <th>Vistorias</th>
                               <!--<th>Vistorias realizadas</th>-->
                               <th></th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($vistoriadores as $vistoriador)
                               <td>
                                    {{ $vistoriador->nome }}
                                    <span class="citacao">
                                        -
                                        @if($vistoriador->franqueado)
                                            Por franquia <b style="cursor: pointer;" onclick="pesquisar(<?php echo $vistoriador->franqueado->id; ?>)">{{ $vistoriador->franqueado->nome }}</b>
                                        @else
                                            Todas as franquias
                                        @endif
                                        em <?php echo Formatacao::data($vistoriador->data_cadastro) ?>
                                    </span>
                                </td>
                               <td><a href="{{ isset($vistoriador->usuarioApp->email) ? 'mailto:'.$vistoriador->usuarioApp->email : 'javascript:void(0)' }}">{{ isset($vistoriador->usuarioApp->email) ? $vistoriador->usuarioApp->email : '--' }}</a></td>
                               <td>
                                    <a href="vistorias" class="btn btn-info" title="Ver agenda">
                                        <i class="fa fa-eye"></i>
                                    </a>
                               </td>
                               <!--<td>
                                    <a href="" class="btn btn-info" title="Ver agenda">
                                        <i class="fa fa-eye"></i> 2
                                    </a>
                               </td>
                               <td>
                                    <a href="" class="btn btn-success" title="Ver vistorias">
                                        <i class="fa fa-eye"></i> 25
                                    </a>
                               </td>-->
                               <td align="right">

                                   <form method="POST" action="{!! route('admin.vistoriadores.destroy', $vistoriador->id) !!}" accept-charset="UTF-8">
                                   <input name="_method" value="DELETE" type="hidden">
                                   {{ csrf_field() }}
   
                                       <div class="btn-group btn-group-xs pull-right" role="group">
                                           <a href="{{ route('admin.vistoriadores.show', $vistoriador->id ) }}" class="btn btn-info" title="Ver vistoriador">
                                               <i class="fa fa-eye"></i>
                                           </a>
                                           <a href="{{ route('admin.vistoriadores.edit', $vistoriador->id ) }}" class="btn btn-primary" title="Editar vistoriador">
                                               <i class="fa fa-pencil"></i>
                                           </a>
   
                                           <button type="submit" class="btn btn-danger" title="Remover vistoriador" onclick="return confirm('Deseja realmete excluir o vistoriador {{$vistoriador->nome}}?')">
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
  <script>
      function pesquisar(franqueado_id){
          if(franqueado_id==0){
            window.location = "{{$url}}vistoriadores";
          } else {
            window.location = "{{$url}}vistoriadores/franqueado_id/"+franqueado_id;
          }
      }
  </script>
@endpush