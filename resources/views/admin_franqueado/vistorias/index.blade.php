<?php 
    use App\Uteis\Formatacao; 
    use App\Uteis\StatusVistoria; 
?>
@extends('admin_franqueado.layout.master')

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
                   <h4 class="mt-5 mb-5">Listagem de vistorias</h4>
               </div>
   
               <div class="btn-group btn-group-sm pull-right" role="group">
                   <a href="{{ route('admin_franqueado.vistorias.create') }}" class="btn btn-success" title="Criar nova vistoria">
                       <i data-feather="plus"></i> <span>Nova vistoria</span>
                   </a>
               </div>
   
           </div>
           
           
           
           @if(count($vistorias) == 0)
               <div class="panel-body text-center">
                   <h4>Nenhuma vistoria listada</h4>
               </div>
           @else
               <div class="panel-body panel-body-with-table">
               <div class="table-responsive">
               <table data-page-length="25" id="dataTableExample" class="table table-striped dataTableDesc no-footer" role="grid" aria-describedby="dataTableExample_info">
                       <thead>
                           <tr>
                                <th>#</th>
                               <th>Datas</th>
                               <th width="200">Solicitação/Local</th>
                               <th width="200">Vistoriador</th>
                               <th>Check-in/Check-out</th>
                               <th></th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($vistorias as $vistoria)
                               <td>{{ $vistoria->id }}</td>
                               <td> 
                                   <p>
                                        <b class="d-block mb-0">Solicitado em</b>
                                        <?php
                                            if($vistoria->data_cadastro)
                                                echo Formatacao::data($vistoria->data_cadastro); 
                                            else
                                                echo "--";
                                        ?>
                                   </p>
                                   <p class="mt-1">
                                        <b class="d-block">Data da vistoria</b>
                                        <?php
                                            if($vistoria->data_vistoria){
                                                echo Formatacao::data($vistoria->data_vistoria); 
                                                if($vistoria->hora_vistoria)
                                                    echo " - ". Formatacao::hora($vistoria->hora_vistoria, true, true, false);
                                            } else
                                                echo "--";

                                            
                                        ?>
                                   </p>
                                </td>    
                                <td>
                                    <h5 class="mb-0"><a href="<?php echo env("APP_URL"); ?>/admin_franqueado/orcamentos/{{$vistoria->orcamento->id}}/edit" title="Ver solicitação em nova aba" target="_blank">#{{ $vistoria->orcamento->id }} - {{ $vistoria->orcamento->nome }}</a></h5>
                                    <label class="mb-2 badge badge-<?php echo StatusVistoria::getColor($vistoria->status); ?>"><?php echo StatusVistoria::getLabel($vistoria->status); ?></label>
                                    <h5>{{ $vistoria->orcamento->condominio->nome }}</h5>
                                    <a title="Abrir no mapa" style="font-size: 14px; margin-top: 6px; display: block;" target="_blank" href="https://www.google.com/maps/place/{{$vistoria->orcamento->condominio->endereco . "," . $vistoria->orcamento->condominio->numero."-".$vistoria->orcamento->condominio->bairro."+,+".$vistoria->orcamento->condominio->cidade."+-+".$vistoria->orcamento->condominio->estado.",+".$vistoria->orcamento->condominio->cep}}">
                                        {{ $vistoria->orcamento->condominio->cep.". ".$vistoria->orcamento->condominio->endereco.", ".$vistoria->orcamento->condominio->numero.". ".$vistoria->orcamento->condominio->bairro.", ".$vistoria->orcamento->condominio->cidade."/".$vistoria->orcamento->condominio->estado }}
                                    </a>
                                </td>
                                <td>
                                    @if($vistoria->vistoriador_id>0)    
                                        <h5>{{$vistoria->vistoriador->nome}}</h5>
                                        <p><?php echo nl2br($vistoria->vistoriador->dados_acesso_condominio); ?></p>
                                    @else
                                        --
                                    @endif
                                </td> 
                                <td>
                                    <p>
                                        <b class="d-block mb-0">Check-in</b>
                                        <?php
                                            if($vistoria->data_checkin)
                                                echo Formatacao::data($vistoria->data_checkin); 
                                            else
                                                echo "--";
                                        ?>
                                   </p>
                                   <p class="mt-1">
                                        <b class="d-block">Check-out</b>
                                        <?php
                                            if($vistoria->data_checkout)
                                                echo Formatacao::data($vistoria->data_checkout); 
                                            else
                                                echo "--";
                                        ?>
                                   </p>
                                </td>
                               <td align="right">

                                   <form method="POST" action="{!! route('admin_franqueado.vistorias.destroy', $vistoria->id) !!}" accept-charset="UTF-8">
                                   <input name="_method" value="DELETE" type="hidden">
                                   {{ csrf_field() }}
   
                                       <div class="btn-group btn-group-xs pull-right" role="group">
                                           <a href="{{ route('admin_franqueado.vistorias.show', $vistoria->id ) }}" class="btn btn-info" title="Ver vistoria">
                                               <i class="fa fa-eye"></i>
                                           </a>
                                           <a href="{{ route('admin_franqueado.vistorias.edit', $vistoria->id ) }}" class="btn btn-primary" title="Editar vistoria">
                                               <i class="fa fa-pencil"></i>
                                           </a>
   
                                           <button type="submit" class="btn btn-danger" title="Remover vistoria" onclick="return confirm('Deseja realmete excluir o vistoria {{$vistoria->nome}}?')">
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