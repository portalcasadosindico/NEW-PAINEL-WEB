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
                   <a href="{{ route('planos_disponiveis_franqueado.create') }}" class="btn btn-success" title="Criar novo plano">
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
               <table class="table table-striped ">
                       <thead>
                           <tr>
                               <th width="200">Nome</th>
                               <th>Região</th>
                               <th>Valor</th>
                               <th>Período</th>
                               <th>Comissão</th>
                               <th>Disponibilizados</th>
                               <th>Assinaturas</th>
                               <th></th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($planos_disponiveis_franqueado as $plano_disponivel_franqueado)
                               <td>{{ $plano_disponivel_franqueado->nome }}</td>
                               <td>
                                    <?php $faker = Faker\Factory::create(); if($plano_disponivel_franqueado->regiao!=null){ ?>
                                        {{$plano_disponivel_franqueado->regiao->nome}}
                                    <?php } else { ?>
                                        Todas as regiões
                                    <?php } ?>
                               </td>    
                               <td>{{ $plano_disponivel_franqueado->valor }}</td>                  
                               <td>{{ (($plano_disponivel_franqueado->quantidade_meses_vigencia==1) ? 'Mensal' : (($plano_disponivel_franqueado->quantidade_meses_vigencia==12) ? 'Anual' : 'A cada ' .$plano_disponivel_franqueado->quantidade_meses_vigencia .' meses')) }}</td>   
                               <td>{{ $plano_disponivel_franqueado->valor_comissao }}%</td>      
                               <td><span class="badge badge-success"><?php echo $faker->randomNumber($nbDigits = 1, $strict = true); ?></span></td>       
                               <td><span class="badge badge-success"><?php echo $faker->randomNumber($nbDigits = 3, $strict = true); ?></span></td>            
                               <td align="right">

                                   <form method="POST" action="{!! route('planos_disponiveis_franqueado.destroy', $plano_disponivel_franqueado->id) !!}" accept-charset="UTF-8">
                                   <input name="_method" value="DELETE" type="hidden">
                                   {{ csrf_field() }}
   
                                       <div class="btn-group btn-group-xs pull-right" role="group">
                                           <a href="{{ route('planos_disponiveis_franqueado.show', $plano_disponivel_franqueado->id ) }}" class="btn btn-info" title="Ver plano">
                                               <i class="fa fa-eye"></i>
                                           </a>
                                           <a href="{{ route('planos_disponiveis_franqueado.edit', $plano_disponivel_franqueado->id ) }}" class="btn btn-primary" title="Editar plano">
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