<?php 
    use App\Uteis\Formatacao;
    use App\Models\Orcamento;
    use App\Models\Condominio;
    use App\Uteis\StatusOrcamento;      
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
                    <a href="{{ route('admin.sindicos.create') }}" class="btn btn-success" title="Criar novo sindico">
                       <i data-feather="plus"></i> <span>Novo síndico</span>
                   </a>
                   <h4 class="mt-5 mb-5">Listagem de síndicos</h4>
               </div>
   

               <div class="btn-group btn-group-sm pull-right" style="float: right;" role="group">
                    <div style="position: relative; top: -12px;">
                        <label>Filtro por modo cadastro</label><br>
                        <select onchange="pesquisar(this.value)">
                            <option value="0">Todos os modos</option>
                            <option <?php



if($franqueado_id==-1) echo "selected"; ?> value="-1">Cadastrados pelo App</option>
                            @foreach($franqueados as $franqueado)
                                <option <?php if($franqueado_id==$franqueado->id) echo "selected"; ?> value="{{$franqueado->id}}">Cadastrado por {{$franqueado->nome}}</option>
                            @endforeach
                        </select>
                    </div>
               </div>
   
           </div>
           
           
           
           @if(count($sindicos) == 0)
               <div class="panel-body text-center">
                   <h4>Nenhum síndico listado</h4>
               </div>
           @else
               <div class="panel-body panel-body-with-table">
               <div class="table-responsive">
               <table data-page-length="25" id="dataTableExample" class="table table-striped dataTable no-footer" role="grid" aria-describedby="dataTableExample_info">
                       <thead>
                           <tr>
                               <th width="200">Nome</th>
                               <th>E-mail</th>
                               <th>Telefone</th>
                               <th>Solicitações</th>
                               <th></th>
                           </tr>
                       </thead>
                       <tbody>
                       @foreach($sindicos as $sindico)
                           <tr>
                               <td>
                                    {{ $sindico->nome }}
                                    <span class="citacao">
                                        - Por franquia <b>{{ $sindico->forma_cadastro }}</b>
                                        
                                        em <?php echo Formatacao::data($sindico->data_cadastro) ?>
                                    </span>
                                </td>
                               <td>
                                    @if($sindico->usuarioApp && $sindico->usuarioApp->data_confirmacao==null)
                                        <label class="badge badge-warning">E-mail não confirmado</label>
                                        <label class="badge badge-info data-ultimo-envio-{{$sindico->usuarioApp->id}}">Último envio: {{Formatacao::data($sindico->usuarioApp->ultimo_envio_email)}}</label>
                                        <a href="javascript:void(0)" class="link-reenviar-send-{{$sindico->usuarioApp->id}}" onclick="reenviarEmail({{$sindico->usuarioApp->id}})">Reenviar e-mail</a>
                                        <label class="badge badge-warning link-reenviar-sending-{{$sindico->usuarioApp->id}}" style="display: none;">Enviando...</label>
                                        <label class="badge badge-success link-reenviar-success-{{$sindico->usuarioApp->id}}" style="display: none;">Enviado</label>
                                        <label class="badge badge-danger link-reenviar-danger-{{$sindico->usuarioApp->id}}" style="display: none;">Não Enviado</label>
                                        <br>
                                    @elseif($sindico->usuarioApp)
                                        <label class="badge badge-success"> E-mail confirmado</label>
                                    @endif
                                    <br>
                                    @if(isset($sindico->usuarioApp) && isset($sindico->usuarioApp->email))
                                        <a href="mailto:{{ $sindico->usuarioApp->email }}">{{ $sindico->usuarioApp->email }}</a>
                                    @else
                                        <span>Email não disponível</span>
                                    @endif
                                    @if(isset($sindico->usuarioApp) && isset($sindico->usuarioApp->isFacebook))
                                    @if($sindico->usuarioApp->isFacebook==1)
                                        <label class="badge badge-primary">Login pelo Facebook</label>
                                    @endif
                                    @else
                                        <span>Facebook não disponível</span>
                                    @endif
                                    @if(isset($sindico->usuarioApp) && isset($sindico->usuarioApp->isEmail))
                                    @if($sindico->usuarioApp->isEmail==1)
                                        <label class="badge badge-secondary">Login pelo E-mail</label>
                                    @endif
                                    @else
                                        <span>E-mail não disponível</span>
                                    @endif
                                </td>
                               <td>
                                    <label class="whats-link">{{$sindico->telefone}}</label>
                               </td>

                               <td>
                                    <?php 
                                        $condominios = Condominio::withTrashed()->where("sindico_id", $sindico->id)->get();
                                        $countConcluidas = 0;
                                        $countCanceladas = 0;
                                        $countAndamento = 0;
                                        $countTotal = 0;
                                        $condominiosTxt = "";
                                        foreach($condominios as $condiminio){
                                            $countConcluidas += Orcamento::where("condominio_id", $condiminio->id)->where("status", StatusOrcamento::$FINALIZADO)->count();
                                            $countCanceladas += Orcamento::where("condominio_id", $condiminio->id)->whereIn("status", [StatusOrcamento::$CANCELADO_PELO_ADMIN, StatusOrcamento::$CANCELADO_PELO_SINDICO, StatusOrcamento::$CANCELADO_PELO_AFILIADO, StatusOrcamento::$CANCELADO_PELO_FRANQUEADO])->count();
                                            $countAndamento += Orcamento::where("condominio_id", $condiminio->id)->whereIn("status", [
                                                StatusOrcamento::$ANALISANDO_CANDIDATOS, StatusOrcamento::$ANALISANDO_ORCAMENTOS, StatusOrcamento::$AGUARDANDO_CONTRATO, StatusOrcamento::$CONTRATO_ASSINADO, StatusOrcamento::$EM_EXECUCAO])->count();
                                            $countTotal += Orcamento::where("condominio_id", $condiminio->id)->count();
                                            $condominiosTxt .= $condiminio->nome . "<br>";
                                        }
                                        
                                    ?>
                                    <label class="badge badge-success mb-2" style="font-size: 13px;">Concluidas: {{$countConcluidas}}</label><br>
                                    <label class="badge badge-warning mb-2" style="font-size: 13px;">Em andamento: {{$countAndamento}}</label><br>
                                    <label class="badge badge-danger mb-2" style="font-size: 13px;">Canceladas: {{$countCanceladas}}</label><br>
                                    <label class="badge badge-info mb-2" style="font-size: 13px;">Total: {{$countTotal}}</label>
                               </td>

                               <td align="right">

                                   <form method="POST" action="{!! route('admin.sindicos.destroy', $sindico->id) !!}" accept-charset="UTF-8">
                                   <input name="_method" value="DELETE" type="hidden">
                                   {{ csrf_field() }}
   
                                       <div class="btn-group btn-group-xs pull-right" role="group">
                                           <a href="{{ route('admin.sindicos.show', $sindico->id ) }}" class="btn btn-info" title="Ver sindico">
                                               <i class="fa fa-eye"></i>
                                           </a>
                                           <a href="{{ route('admin.sindicos.edit', $sindico->id ) }}" class="btn btn-primary" title="Editar sindico">
                                               <i class="fa fa-pencil"></i>
                                           </a>
   
                                           <button type="submit" class="btn btn-danger" title="Remover sindico" onclick="return confirm('Deseja realmete excluir o sindico {{$sindico->nome}}?')">
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
      function pesquisar(franqueado_id){
          if(franqueado_id==0){
            window.location = "{{$url}}sindicos";
          } else {
            window.location = "{{$url}}sindicos/franqueado_id/"+franqueado_id;
          }
      }

      function reenviarEmail(usuario_id){
            $(".link-reenviar-send-"+usuario_id).hide();
            $(".link-reenviar-sending-"+usuario_id).show();
            $(".link-reenviar-danger-"+usuario_id).hide();
            $.getJSON({
                url: "/admin/ajax/reenviarEmailConfirmarcao/" + usuario_id, 
                method: "GET", 
                data: {},
                success: function(data) {
                    $(".link-reenviar-sending-"+usuario_id).hide();
                    
                    if(data.status==true){
                        $(".link-reenviar-success-"+usuario_id).show();
                        $(".data-ultimo-envio-"+usuario_id).html(data.data)
                    } else {
                        $(".link-reenviar-danger-"+usuario_id).show();
                        $(".link-reenviar-send-"+usuario_id).show();
                    }
                }
                , error: function(e) {
                    $(".link-reenviar-send-"+usuario_id).show();
                    $(".link-reenviar-sending-"+usuario_id).hide();
                    $(".link-reenviar-danger-"+usuario_id).show();
                }
            });
        }
  </script>
@endpush