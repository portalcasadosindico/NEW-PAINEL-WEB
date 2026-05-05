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
                   <h4 class="mt-5 mb-5">Disponibilizar Planos</h4>
               </div>

           </div>



           @if(count($franqueado_regiao_planos_disponibilizados) == 0)
               <div class="panel-body text-center">
                   <h4>Nenhum plano listado</h4>
               </div>
           @else
               <div class="panel-body panel-body-with-table">
               <div class="table-responsive">
               <table class="table table-striped ">
                       <thead>
                           <tr>
                               <th width="200">Nome plano</th>
                               <th>Valor</th>
                               <th>Valor comissao</th>
                               <th>Disponibilizar</th>
                               <th></th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($franqueado_regiao_planos_disponibilizados as $franqueado_regiao_plano_disponibilizado)
                            @if($franqueado_regiao_plano_disponibilizado->planoDisponivelFranqueado->is_public==1)
                                <tr>
                                    <td>{{ $franqueado_regiao_plano_disponibilizado->planoDisponivelFranqueado->nome }}</td>
                                    <td>{{ $franqueado_regiao_plano_disponibilizado->planoDisponivelFranqueado->valor }}</td>
                                    <td>{{ $franqueado_regiao_plano_disponibilizado->planoDisponivelFranqueado->valor_comissao }}</td>
                                    <td>
                                        <form action="{{ route('admin_franqueado.franqueado_regiao_planos_disponibilizados.status',$franqueado_regiao_plano_disponibilizado->id)}}" method="post">
                                        <input name="_method" value="PUT" type="hidden">
                                        <input type="hidden" name="statusPlano" value="{{ $franqueado_regiao_plano_disponibilizado->planoDisponivelFranqueado->statusPlano }}">
                                        {{ csrf_field() }}
    
                                        <button type="submit" class="btn btn-danger" title="Remover franqueado_regiao_plano_disponibilizado">
                                                    {{$franqueado_regiao_plano_disponibilizado->planoDisponivelFranqueado->statusPlano}}
                                        </button>
                                        </form>
    
                                    </td>
                                    <td align="right">
    
                                        <form method="POST" action="{!! route('admin_franqueado.franqueado_regiao_planos_disponibilizados.destroy', $franqueado_regiao_plano_disponibilizado->id) !!}" accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}
    
                                            <div class="btn-group btn-group-xs pull-right" role="group">
                                                <a href="{{ route('admin_franqueado.franqueado_regiao_planos_disponibilizados.show', $franqueado_regiao_plano_disponibilizado->id ) }}" class="btn btn-info" title="Ver franqueado_regiao_plano_disponibilizado">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin_franqueado.franqueado_regiao_planos_disponibilizados.edit', $franqueado_regiao_plano_disponibilizado->id ) }}" class="btn btn-primary" title="Editar franqueado_regiao_plano_disponibilizado">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
    
                                                <button type="submit" class="btn btn-danger" title="Remover franqueado_regiao_plano_disponibilizado" onclick="return confirm('Deseja realmete excluir o franqueado_regiao_plano_disponibilizado {{$franqueado_regiao_plano_disponibilizado->nome}}?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
    
                                        </form>
    
                                    </td>
                                </tr>
                            @endif
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