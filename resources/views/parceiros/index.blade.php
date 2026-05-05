<?php
    use App\Models\PlanoDisponivelFranqueado;
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
                   <h4 class="mt-5 mb-5">Listagem de parceiros</h4>
               </div>
   
               <div class="btn-group btn-group-sm pull-right" role="group">
                   <a href="{{ route('parceiros.create') }}" class="btn btn-success" title="Criar novo parceiro">
                       <i data-feather="plus"></i> <span>Novo parceiro</span>
                   </a>
               </div>
   
           </div>
           
           
           
           @if(count($parceiros) == 0)
               <div class="panel-body text-center">
                   <h4>Nenhum parceiro listado</h4>
               </div>
           @else
               <div class="panel-body panel-body-with-table">
               <div class="table-responsive">
               <table data-page-length="25" class="table table-striped dataTable">
                       <thead>
                           <tr>
                               <th>Logo</th>
                               <th>Parceiro</th>
                               <th width="250">Plano</th>
                               <th>Status</th>
                               <th></th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($parceiros as $parceiro)
                               <td>
                                    @if($parceiro->logo!=null)
                                        <img style="width: auto; height: auto; max-height: 100px; max-width: 300px; border-radius: 0px;" src="{{ Storage::url($parceiro->logo) }}" alt="--">
                                    @else
                                        <a href="parceiros/{{$parceiro->id}}/edit">Adicionar uma logo</a>
                                    @endif
                                </td>
                               <td>
                                   <h5 style="margin-bottom: 6px;">{{ $parceiro->nome }}</h5>
                                   <span class="mail-link" style="display: block; margin-bottom: 3px;">{{$parceiro->email}}</span>
                                   
                                   <span class="whats-link" style="display: block; margin-bottom: 3px;">{{ $parceiro->telefone }}</span>
                                   @if($parceiro->link)
                                        <span><a href="{{$parceiro->link}}" target="_blank">Visitar LINK</a></span>
                                   @else
                                        <span>Sem link</span>
                                   @endif
                                </td>
                                <td>
                                    <?php
                                        $plano = PlanoDisponivelFranqueado::where("id", $parceiro->plano_id)->first();
                                    ?> 
                                    
                                    @if($plano)
                                        <a href="planos_disponiveis_franqueado/{{$plano->id}}/edit" target="_blank" title="">{{$plano->nome}} - R${{$plano->valor}}</a>
                                    @endif
                                    <br><br>
                                    <?php /*@if($parceiro->asaas_assinatura_id)
                                        <label class="badge badge-success">Interado ao ASAAS</label>
                                        - <a href="">Cancelar assinatura</a>
                                    @elseif($plano->valor>0)
                                        <label class="badge badge-warning">Não integrado ao ASAAS</label>
                                        - <a href="javascript:alert('Verificar como está hoje.')">Integrar assinatura ao ASAAS</a>
                                    @endif*/ ?>
                                </td>
                               <td>
                                   @if($parceiro->status=="ativo")
                                        <label class="badge badge-success">{{ $parceiro->status }}</label>
                                   @elseif($parceiro->status=="pendente")
                                        <label class="badge badge-warning">{{ $parceiro->status }}</label>
                                   @elseif($parceiro->status=="inativo")
                                        <label class="badge badge-danger">{{ $parceiro->status }}</label>
                                   @endif
                                </td>
                               <td align="right">

                                   <form method="POST" action="{!! route('parceiros.destroy', $parceiro->id) !!}" accept-charset="UTF-8">
                                   <input name="_method" value="DELETE" type="hidden">
                                   {{ csrf_field() }}
   
                                       <div class="btn-group btn-group-xs pull-right" role="group">
                                           <a href="{{ route('parceiros.show', $parceiro->id ) }}" class="btn btn-info" title="Ver parceiro">
                                               <i class="fa fa-eye"></i>
                                           </a>
                                           <a href="{{ route('parceiros.edit', $parceiro->id ) }}" class="btn btn-primary" title="Editar parceiro">
                                               <i class="fa fa-pencil"></i>
                                           </a>
   
                                           <button type="submit" class="btn btn-danger" title="Remover parceiro" onclick="return confirm('Deseja realmete excluir o parceiro {{$parceiro->nome}}?')">
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