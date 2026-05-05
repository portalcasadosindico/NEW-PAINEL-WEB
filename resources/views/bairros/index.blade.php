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
               <div class="btn-group btn-group-sm pull-right" role="group">
                   <a href="{{ route('bairros.create') }}" class="btn btn-success" title="Criar novo bairro">
                       <i data-feather="plus"></i> <span>Novo bairro</span>
                   </a>
               </div>

               <div class="pull-left">
                   <h4 class="mt-5 mb-5">Listagem dos bairros</h4>
                   <div class="btn-group btn-group-sm pull-right" style="float: right;" role="group">
                    <div style="position: relative; top: -12px;">
                        <label>Filtro por cidade</label><br>
                        <select onchange="pesquisar(this.value)">
                            <option value="0">Todos os bairros</option>
                            @foreach($cidades as $cidade)
                                <option <?php if($cidade_id==$cidade->id) echo "selected"; ?> value="{{$cidade->id}}">{{$cidade->nome}}</option>
                            @endforeach
                        </select>
                    </div>
                   </div>
               </div>
   
           </div>
           
           
           
           @if(count($bairros) == 0)
               <div class="panel-body text-center">
                   <h4>Nenhum bairro listado</h4>
               </div>
           @else
               <div class="panel-body panel-body-with-table">
               <div class="table-responsive">
               <table data-page-length="25" id="dataTableExample" class="table table-striped dataTable no-footer" role="grid" aria-describedby="dataTableExample_info">
                       <thead>
                           <tr>
                               <th width="200">Nome</th>
                               <th>Cidade</th>
                               <th>Região</th>
                               <th></th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($bairros as $bairro)
                               <td>{{ $bairro->nome }}</td>
                               <td>
                                    <?php if($bairro->cidade!=null){ ?>
                                        {{ $bairro->cidade->nome }}/{{ $bairro->cidade->estado->uf }}         
                                    <?php } else { ?>
                                        <a href="{{ route('bairros.edit', $bairro->id ) }}">Vincular a uma cidade</a>
                                    <?php } ?>
                               </td>                  
                               <td>
                                    <?php if($bairro->regiao!=null){ 
                                        echo $bairro->regiao->nome; ?>
                                        <br>
                                        <a href="{{ route('bairros.edit', $bairro->id ) }}">Alterar região</a>
                                    <?php } else { echo "Sem região"; ?>
                                    <br>
                                    <a href="{{ route('bairros.edit', $bairro->id ) }}">Vincular a uma região</a>
                                    <?php } ?>
                            </td>                  
                               <td align="right">

                                   <form method="POST" action="{!! route('bairros.destroy', $bairro->id) !!}" accept-charset="UTF-8">
                                   <input name="_method" value="DELETE" type="hidden">
                                   {{ csrf_field() }}
   
                                       <div class="btn-group btn-group-xs pull-right" role="group">
                                           <a href="{{ route('bairros.show', $bairro->id ) }}" class="btn btn-info" title="Ver bairro">
                                               <i class="fa fa-eye"></i>
                                           </a>
                                           <a href="{{ route('bairros.edit', $bairro->id ) }}" class="btn btn-primary" title="Editar bairro">
                                               <i class="fa fa-pencil"></i>
                                           </a>
   
                                           <button type="submit" class="btn btn-danger" title="Remover bairro" onclick="return confirm('Deseja realmete excluir o bairro {{$bairro->nome}}?')">
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
      function pesquisar(cidade_id){
          if(cidade_id==0){
            window.location = "<?php echo getenv('APP_URL'); ?>/admin/bairros";
          } else {
            window.location = "<?php echo getenv('APP_URL'); ?>/admin/bairros/cidade_id/"+cidade_id;
          }
      }
  </script>
@endpush