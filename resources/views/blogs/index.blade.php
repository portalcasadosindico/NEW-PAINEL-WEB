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
                   <h4 class="mt-5 mb-5">Listagem de posts</h4>
               </div>
   
               <div class="btn-group btn-group-sm pull-right" role="group">
                   <a href="{{ route('blogs.create') }}" class="btn btn-success" title="Criar novo post">
                       <i data-feather="plus"></i> <span>Novo post</span>
                   </a>
               </div>
   
           </div>
           
           
           
           @if(count($blogs) == 0)
               <div class="panel-body text-center">
                   <h4>Nenhum post listado</h4>
               </div>
           @else
               <div class="panel-body panel-body-with-table">
               <div class="table-responsive">
               <table class="table table-striped ">
                       <thead>
                           <tr>
                               <th>#</th>
                               <th>Nome</th>
                               <th>Status</th>
                               <th></th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($blogs as $blog)
                                <td>{{$blog->id}}</td>
                               <td>{{ $blog->nome }}</td>
                               <td style="text-transform: capitalize;">
                                   @if($blog->status=="publicado")
                                        <label class="badge badge-success">{{ $blog->status }}</label>
                                   @elseif($blog->status=="rascunho")
                                        <label class="badge badge-info">{{ $blog->status }}</label>
                                    @elseif($blog->status=="inativo")
                                        <label class="badge badge-danger">{{ $blog->status }}</label>
                                   @endif
                                </td>                  
                               <td align="right">

                                   <form method="POST" action="{!! route('blogs.destroy', $blog->id) !!}" accept-charset="UTF-8">
                                   <input name="_method" value="DELETE" type="hidden">
                                   {{ csrf_field() }}
   
                                       <div class="btn-group btn-group-xs pull-right" role="group">
                                           <a href="{{ route('blogs.show', $blog->id ) }}" class="btn btn-info" title="Ver blog">
                                               <i class="fa fa-eye"></i>
                                           </a>
                                           <a href="{{ route('blogs.edit', $blog->id ) }}" class="btn btn-primary" title="Editar blog">
                                               <i class="fa fa-pencil"></i>
                                           </a>
   
                                           <button type="submit" class="btn btn-danger" title="Remover blog" onclick="return confirm('Deseja realmete excluir o blog {{$blog->nome}}?')">
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