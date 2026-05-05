<?php 
    use App\Uteis\Asaas;
    use App\Models\FranqueadoRegiaoPlanoDisponibilizado; 
    use App\Models\FranqueadoRegiao; 
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
                   <h4 class="mt-5 mb-5">Disponibilizar planos</h4>
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
                               <th>Nome</th>
                               <th>Região</th>
                               <th>Valores</th>
                               <th>Ciclo</th>
                               <th>Comissão</th>
                               <th>Disponibilizar</th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($planos_disponiveis_franqueado as $plano_disponivel_franqueado) 
                       <tr>       
                        <td>
                                   {{ $plano_disponivel_franqueado->nome }}
                                   @if($plano_disponivel_franqueado->isTerceirizada==1)
                                        <br>
                                        <label class="badge badge-info" style="margin-top: 4px;">Terceirizada</label>
                                   @endif
                                </td>
                               <td>
                                    <?php



$faker = Faker\Factory::create();if ($plano_disponivel_franqueado->regiao != null) {?>
                                        {{$plano_disponivel_franqueado->regiao->nome}}
                                    <?php } else {?>
                                        Todas as regiões
                                    <?php }?>
                               </td>
                               <td>
                                    <label class="badge badge-success" style="font-size: 14px;">R${{ $plano_disponivel_franqueado->valor }}</label>
                                    <br>
                                    <label class="badge badge-success" style="font-size: 12px; margin-top: 6px;">Desconto: {{$plano_disponivel_franqueado->desconto}}%</label>
                                </td>
                                <td>
                                    <?php echo Asaas::getLabel($plano_disponivel_franqueado->ciclo) ?>
                                </td>
                                <td>{{ $plano_disponivel_franqueado->valor_comissao }}%</td>
                               <td>
                                   <form action="{{ route('admin_franqueado.planos_disponiveis_franqueado.status',$plano_disponivel_franqueado->id)}}" method="post">
                                   <input name="_method" value="PUT" type="hidden">
                                   <input type="hidden" name="statusPlano" value="{{ $plano_disponivel_franqueado->statusPlano }}">
                                   {{ csrf_field() }}
                                   <?php 
                                        $regiaoesFranqueado = FranqueadoRegiao::where("franqueado_id", $franqueado_id)->where("status", "ativo")->get();
                                        foreach ($regiaoesFranqueado as $key => $value) {
                                            $disponibilidade = FranqueadoRegiaoPlanoDisponibilizado::where("franqueado_regiao_id", $value->id)->where("plano_disponivel_franqueado_id", $plano_disponivel_franqueado->id)->orderBy("id", "desc")->first();    
                                            if($disponibilidade) break;
                                        }
                                        
                                    ?>

                                    @if($plano_disponivel_franqueado->is_public==1)
                                        @if($disponibilidade)
                                            <button type="submit" class="btn btn-success" title="Remover franqueado_regiao_plano_disponibilizado">
                                                Disponível
                                            </button>
                                            <br><br>
                                            <label>Clique para INDISPONIBILIZAR este plano.</label>
                                            <input type="hidden" name="newStatus" value="inativo">
                                        @else
                                            <button type="submit" class="btn btn-warning" title="">
                                                Disponibilizar plano aos afiliados
                                            </button>
                                            <input type="hidden" name="newStatus" value="ativo">
                                        @endif
                                    @else
                                        <label>Plano disponível apenas para admins</label>
                                    @endif

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