<?php 
    use App\Uteis\Formatacao;
    use App\Models\Orcamento;
    use App\Models\Condominio;
    use App\Uteis\StatusOrcamento;      
?>
@extends('layout.master2')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div>        
    <table class="table dataTableNoOrderNoPage">
        <thead>
            <tr>
                <th style="width: 200px">ID</th>
                <th style="width: 30px">Usuário</th>
                <th style="width: 100px">Descrição</th>
                <th style="width: 100px">Endpoint</th>
                <th style="width: 100px">Body</th>
                <th style="width: 100px">Response</th>
                <th style="width: 100px">Mensagem Response</th>
                <th style="width: 100px">Timing</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>
                        <b>{{$log->id}}</b>
                        <br>
                        {{$log->data_cadastro}}
                    </td>
                    <td>U:{{$log->usuario_app_id}}</td>
                    <td>{{$log->descricao}}</td>
                    <td>{{$log->metodo}} - {{$log->endpoint}}</td>
                    <td><pre  style="max-height: 300px; overflow: auto; width: 300px;">{{$log->body}}</pre></td>
                    <td style="max-height: 300px; overflow: auto;"><pre  style="max-height: 100px; overflow: auto; width: 300px;">{{$log->response}}</pre></td>
                    <td style="max-height: 300px; overflow: auto;">
                        <label class="badge badge-<?php echo $log->status_response==200 ? 'success' : 'warning'; ?>">{{$log->status_response}} - {{$log->messagem_response}}</label>
                    </td>
                    <td>{{$log->status_response==200 ? $log->delta_time : "Não finalizou"}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
             
</div>
     
               
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/dragula/dragula.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/dragula.js') }}"></script>
@endpush