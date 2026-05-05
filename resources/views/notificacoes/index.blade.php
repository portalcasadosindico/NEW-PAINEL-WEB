<?php
    use App\Uteis\Formatacao;
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
                    <a href="{{ route('admin.notificacoes.create') }}" class="btn btn-success" title="Criar novo estado">
                        <i data-feather="plus"></i> <span>Nova Notificação / E-mail Simples</span>
                    </a>
                   <h4 class="mt-5 mb-5">Listagem de notificações/e-mails enviados</h4>
               </div>
   
           </div>
           
           
           
           @if(count($notificacoes) == 0)
               <div class="panel-body text-center">
                   <h4>Nenhuma notificação listada</h4>
               </div>
           @else
               <div class="panel-body panel-body-with-table">
                    <div class="table-responsive">
                        <table data-page-length="25" id="dataTableExample" class="table table-striped dataTableDesc no-footer" role="grid" aria-describedby="dataTableExample_info">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Título</th>
                                    <th>Data</th>
                                    <th>Tipo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach($notificacoes as $notificacao)
                                        <tr>
                                            <td>{{ $notificacao->id }}</td>
                                            <td>{{ $notificacao->titulo }}</td>
                                            <td>{{Formatacao::data($notificacao->data_cadastro)}}</td>
                                            <td>
                                                @if($notificacao->isSendEmail)
                                                    <label class="badge badge-info mb-2">Enviado por e-mail</label>
                                                    <br>
                                                @endif
                                                @if($notificacao->isSendNotification)
                                                    <label class="badge badge-info">Enviado por PUSH Notification</label>
                                                @endif
                                            </td>                  
                                            <td align="right">
                                                <div class="btn-group btn-group-xs pull-right" role="group">
                                                    <a href="{{ route('admin.notificacoes.show', $notificacao->id) }}" class="btn btn-info" title="Ver notificações">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>
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