<?php
    use App\Uteis\Asaas;
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
                   <h4 class="mt-5 mb-5">Listagem de planos</h4>
               </div>
   
               <div class="btn-group btn-group-sm pull-right" role="group">
                   <a href="{{ route('admin.planos_disponiveis_franqueado.create') }}" class="btn btn-success" title="Criar novo plano">
                       <i data-feather="plus"></i> <span>Novo plano</span>
                   </a>
               </div>
   
           </div>
           
           
           
           @if(count($planos_disponiveis_franqueado) == 0)
               <div class="panel-body text-center">
                   <h4>Nenhum plano listado</h4>
               </div>
           @else
               <div class="panel-body panel-body-with-table">
               <div class="table-responsive">
               <table class="table table-striped dataTable">
                       <thead>
                           <tr>
                               <th width="200">Nome</th>
                               <th>Região</th>
                               <th>Valores</th>
                               <th>Ciclo</th>
                               <th>Comissão</th>
                               <th>Tipo</th>
                               <th></th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($planos_disponiveis_franqueado as $plano_disponivel_franqueado)
                               <td>
                                    {{ $plano_disponivel_franqueado->nome }}
                                    <br>
                                    @if($plano_disponivel_franqueado->statusPlano==1)
                                        <label style="margin-top: 6px;" class="badge badge-success">Ativo</label>
                                    @else
                                        <label style="margin-top: 6px;" class="badge badge-danger">Inativo</label>    
                                    @endif
                                </td>
                               <td>
                                    <?php $faker = Faker\Factory::create(); if($plano_disponivel_franqueado->regiao!=null){ ?>
                                        {{$plano_disponivel_franqueado->regiao->nome}}
                                    <?php } else { ?>
                                        Todas as regiões
                                    <?php } ?>
                               </td>    
                               <td>
                                    <label style="margin-top: 6px; font-size: 14px;" class="badge badge-success">R${{ $plano_disponivel_franqueado->valor }}</label>
                                   <br>
                                   <label style="margin-top: 6px; font-size: 12px;" class="badge badge-success">Desconto: <b>{{ $plano_disponivel_franqueado->desconto }}%</b></label>
                                </td>                  
                               <td><?php echo Asaas::getLabel($plano_disponivel_franqueado->ciclo); ?></td>   
                               <td>{{ $plano_disponivel_franqueado->valor_comissao }}%</td>      
                               <td>
                                   <b>{{ $plano_disponivel_franqueado->tipo==1 ? "Parceiro" : "Afiliado" }}</b>
                                   @if($plano_disponivel_franqueado->isTerceirizada==1 )
                                    
                                    <label style="margin-top: 6px;display: block;" class="badge badge-info">Terceirizada</label>
                                   @endif
                                </td>
                               <td align="right">

                                   <form method="POST" action="{!! route('admin.planos_disponiveis_franqueado.destroy', $plano_disponivel_franqueado->id) !!}" accept-charset="UTF-8">
                                   <input name="_method" value="DELETE" type="hidden">
                                   {{ csrf_field() }}
   
                                       <div class="btn-group btn-group-xs pull-right" role="group">
                                           <a href="{{ route('admin.planos_disponiveis_franqueado.edit', $plano_disponivel_franqueado->id ) }}" class="btn btn-primary" title="Editar plano">
                                               <i class="fa fa-pencil"></i>
                                           </a>
   
                                           <button type="submit" class="btn btn-danger" title="Remover plano" onclick="return confirm('Deseja realmete excluir o plano {{$plano_disponivel_franqueado->nome}}?')">
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