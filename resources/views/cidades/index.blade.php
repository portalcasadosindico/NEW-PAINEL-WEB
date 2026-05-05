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
                    <a href="{{ route('cidades.create') }}" class="btn btn-success" title="Criar nova cidade">
                       <i data-feather="plus"></i> <span>Nova cidade</span>
                   </a>
                   <h4 class="mt-5 mb-5">Listagem de cidades</h4>
               </div>
   

               <div class="btn-group btn-group-sm pull-right" style="float: right;" role="group">
                    <div style="position: relative; top: -12px;">
                        <label>Filtro por estado</label><br>
                        <select onchange="pesquisar(this.value)">
                            <option value="0">Todos os estados</option>
                            @foreach($estados as $estado)
                                <option <?php if($estado_id==$estado->id) echo "selected"; ?> value="{{$estado->id}}">{{$estado->nome}}</option>
                            @endforeach
                        </select>
                    </div>
               </div>

   
           </div>
           
           
           
           @if(count($cidades) == 0)
               <div class="panel-body text-center">
                   <h4>Nenhuma cidade listada</h4>
               </div>
           @else
               <div class="panel-body panel-body-with-table">
               <div class="table-responsive">
               <table data-page-length="25" class="table table-striped dataTable">
                       <thead>
                           <tr>
                               <th width="200">Nome</th>
                               <th>Estado</th>
                               <th>Bairros</th>
                               <th></th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($cidades as $cidade)
                               <td>{{ $cidade->nome }}</td>
                               <td>
                                @if($cidade->estado==null)
                                    <a href="{{ route('cidades.edit', $cidade->id ) }}">Vincular a um estado</a>
                                @else
                                    {{ $cidade->estado->nome }} / {{ $cidade->estado->uf }}</td>   
                                @endif
                               <td>

                                    <a href="<?php echo getenv('APP_URL'); ?>/admin/bairros/cidade_id/{{$cidade->id}}" class="btn btn-info" title="Ver bairros">
                                    <i class="fa fa-eye"></i> Bairros
                                    </a>

                               </td>               
                               <td align="right">

                                   <form method="POST" action="{!! route('cidades.destroy', $cidade->id) !!}" accept-charset="UTF-8">
                                   <input name="_method" value="DELETE" type="hidden">
                                   {{ csrf_field() }}
   
                                       <div class="btn-group btn-group-xs pull-right" role="group">
                                           <a href="{{ route('cidades.show', $cidade->id ) }}" class="btn btn-info" title="Ver cidade">
                                               <i class="fa fa-eye"></i>
                                           </a>
                                           <a href="{{ route('cidades.edit', $cidade->id ) }}" class="btn btn-primary" title="Editar cidade">
                                               <i class="fa fa-pencil"></i>
                                           </a>
   
                                           <button type="submit" class="btn btn-danger" title="Remover cidade" onclick="return confirm('Deseja realmete excluir o cidade {{$cidade->nome}}?')">
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
      function pesquisar(estado_id){
          if(estado_id==0){
            window.location = "<?php echo getenv('APP_URL'); ?>/admin/cidades";
          } else {
            window.location = "<?php echo getenv('APP_URL'); ?>/admin/cidades/estado_id/"+estado_id;
          }
      }
  </script>
@endpush