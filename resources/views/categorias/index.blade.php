<?php
    use App\Models\Categoria;
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
                   <h4 class="mt-5 mb-5">Listagem de categorias</h4>
               </div>
   
               <div class="btn-group btn-group-sm pull-right" role="group">
                   <a href="{{ route('categorias.create') }}" class="btn btn-success" title="Criar nova categoria">
                       <i data-feather="plus"></i> <span>Nova categoria</span>
                   </a>
               </div>
   
           </div>
           
           
           
           @if(count($categorias) == 0)
               <div class="panel-body text-center">
                   <h4>Nenhuma categoria listada</h4>
               </div>
           @else
               <div class="panel-body panel-body-with-table">
               <div class="table-responsive">
               <table data-page-length="25" id="dataTableExample" class="dataTable table table-striped  no-footer" role="grid" aria-describedby="dataTableExample_info">
                       <thead>
                           <tr>
                               <th>#</th>
                               <th width="200">Categoria pai</th>
                               <th width="200">Nome</th>
                               <th>Descricao</th>
                               <th>Status</th>
                               <th></th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($categorias as $categoria)
                               <td>
                                    @if($categoria->imagem)
                                        <img style="width: auto; height: auto; max-width: 120px; border-radius: 0px; max-height: 120px;" src="{{ Storage::url($categoria->imagem) }}" alt="--">
                                    @else
                                        <a href="{{ route('categorias.edit', $categoria->id ) }}">Adicionar imagem</a>
                                    @endif
                               </td>
                               <td>
                                   <?php
                                        $cat = Categoria::where("id", $categoria->categoria_pai_id)->first();
                                   ?>
                                    {{ $cat ? $cat->nome : '---' }}
                                </td>
                               <td>{{ $categoria->nome }}</td>
                               <td>{{ $categoria->descricao }}</td>
                               <td>
                                    @if($categoria->status==1)
                                        <label class="badge badge-success">Ativo</label>
                                    @elseif($categoria->status==-1)
                                        <label class="badge badge-danger">Inativo</label>
                                    @endif

                                    @if($categoria->show_afiliado==1)
                                        <label class="badge badge-success">O Afiliado pode selecionar no App</label>
                                    @elseif($categoria->stshow_afiliadoatus==0)
                                        <label class="badge badge-warning">O afiliado não pode selecionar no App</label>
                                    @endif
                                </td>
                               <td align="right">

                                   <form method="POST" action="{!! route('categorias.destroy', $categoria->id) !!}" accept-charset="UTF-8">
                                   <input name="_method" value="DELETE" type="hidden">
                                   {{ csrf_field() }}
   
                                       <div class="btn-group btn-group-xs pull-right" role="group">
                                           <a href="{{ route('categorias.show', $categoria->id ) }}" class="btn btn-info" title="Ver categoria">
                                               <i class="fa fa-eye"></i>
                                           </a>
                                           <a href="{{ route('categorias.edit', $categoria->id ) }}" class="btn btn-primary" title="Editar categoria">
                                               <i class="fa fa-pencil"></i>
                                           </a>
   
                                           <button type="submit" class="btn btn-danger" title="Remover categoria" onclick="return confirm('Deseja realmete excluir o categoria {{$categoria->nome}}?')">
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