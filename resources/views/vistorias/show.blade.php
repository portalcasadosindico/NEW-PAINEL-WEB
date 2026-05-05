<?php use App\Uteis\Formatacao; ?>
@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/dragula/dragula.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
       	<div class="card">
        	<div class="card-body">
            	<h4 class="card-title">Visualizando dados de vistorias</h4>
                <div class="row">
                    <div class="col-md-6">
                          <div class="card">
                              <div class="card-body">
                                <h4>Dados da vistoria</h4>
                                <dl class="dl-horizontal">
                                    <dt>Descrição do síndico</dt>
                                    <dd>{{ $vistoria->descricao ? $vistoria->descricao : "--" }}</dd>
                                    <dt>Data vistoria</dt>
                                    <dd>{{ $vistoria->data_vistoria ? Formatacao::data($vistoria->data_vistoria) : '--' }} {{ $vistoria->hora_vistoria }}</dd>
                                    <dt>Vistoriador</dt>
                                    <dd>{{ $vistoria->vistoriador ? $vistoria->vistoriador->nome : "Sem vistoriador" }}</dd>
                                    <dt>Descrição o vistoriador</dt>
                                    <dd>{{ $vistoria->descricao_vistoriador ? $vistoria->descricao_vistoriador : '--' }}</dd>
                                    <dt>Orcamento</dt>
                                    <dd>{{ $vistoria->orcamento->nome }}</dd>
                                </dl>
                              </div>
                          </div>

                          <div class="card">
                            <div class="card-body">
                              <h4>Fotos</h4>
                              <div class="row">
                                @foreach($imagens as $imagem)
                                    <div class="col-md-12" id="foto-{{$imagem->id}}">
                                        <div class="card">
                                            <div class="card-body">
                                                <a href="javascript:void(0)" onclick="removerFoto({{$imagem->id}})" style="position: absolute; right: 5px; top: 2px; font-weight: bold;" class="text-danger">X Remover</a>
                                                <a href="../../storage/{{$imagem->caminho_imagem}}" target="_blank">
                                                    <img src="../../storage/{{$imagem->caminho_imagem}}" style="max-width: 100%;">
                                                </a>
                                                <div class="mt-3">
                                                    <textarea id="descricao-foto-{{$imagem->id}}" class="form-control" rows="4" placeholder="Descrição da foto">{{$imagem->descricao}}</textarea>
                                                    <a href="javascript:void(0)" class="btn btn-success mt-1" onclick="salvarDescricao({{$imagem->id}})">Salvar descrição</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                              </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                              <h4>Chegada e Saída do local</h4>
                              <dl class="dl-horizontal">
                                <h5>Dados de chegada</h5>
                                <dt>Data checkin</dt>
                                <dd>{{ $vistoria->data_checkin ? Formatacao::data($vistoria->data_checkin, true) : '--' }}</dd>
                                <dt>Latitude checkin</dt>
                                <dd>{{ $vistoria->latitude_checkin ? $vistoria->latitude_checkin : '--' }}</dd>
                                <dt>Longitude checkin</dt>
                                <dd>{{ $vistoria->longitude_checkin ? $vistoria->longitude_checkin : '--' }}</dd>
                                @if($vistoria->latitude_checkin)
                                    <dd><a href="https://www.google.com.br/maps/dir/{{$vistoria->latitude_checkin}},{{$vistoria->longitude_checkin}}" target="_blank">Ver no mapa</a></dd>
                                @endif
                                <dd><hr></dd>
                                <h5>Dados de saída</h5>
                                <dt>Data checkout</dt>
                                <dd>{{ $vistoria->data_checkout ? Formatacao::data($vistoria->data_checkout, true) : '--' }}</dd>
                                <dt>Latitude checkout</dt>
                                <dd>{{ $vistoria->latitude_checkout ? $vistoria->latitude_checkout : '--' }}</dd>
                                <dt>Longitude checkout</dt>
                                <dd>{{ $vistoria->longitude_checkout ? $vistoria->longitude_checkout : '--' }}</dd>
                                @if($vistoria->latitude_checkout)
                                    <dd><a href="https://www.google.com.br/maps/dir/{{$vistoria->latitude_checkout}},{{$vistoria->longitude_checkout}}" target="_blank">Ver no mapa</a></dd>
                                @endif
                              </dl>
                            </div>
                        </div>
                  </div>
                </div>

                <div class="pull-right">
                    <form method="POST" action="{!! route('admin.vistorias.destroy', $vistoria->id) !!}" accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin.vistorias.index') }}" class="btn btn-primary" title="Ver todos vistorias">
                                <i data-feather="list"></i>
                            </a>

                            <a href="{{ route('admin.vistorias.create') }}" class="btn btn-success" title="Novo vistoria">
                                <i data-feather="plus"></i>
                            </a>

                            <a href="{{ route('admin.vistorias.edit', $vistoria->id ) }}" class="btn btn-primary" title="Editar vistoria">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <button type="submit" class="btn btn-danger" title="Remover vistoria" onclick="return confirm('Deseja realmete excluir o vistoria {{$vistoria->nome}}?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </form>
                </div>

        	</div>
        </div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/dragula/dragula.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/dragula.js') }}"></script>
  <script>
      function removerFoto(imagem_vistoria_id){
          if(confirm("Deseja realmente remover esta foto?")){
            var _token = $('input[name="_token"]').val();
            $.getJSON({
                url: "<?php echo getenv("APP_URL"); ?>/admin/vistoria_imagens/"+imagem_vistoria_id,
                method: "DELETE",
                data: {
                    _token: _token
                },
                success: function(data) {
                    if(data==1){
                        $("#foto-"+imagem_vistoria_id).remove()
                        alert("Removido com sucesso.")
                    }
                },
                error: function() {
                    alert("Tente novamente.")
                }
            });
          }
      }

      function salvarDescricao(imagem_vistoria_id){
        var _token = $('input[name="_token"]').val();
        $.getJSON({
            url: "<?php echo getenv("APP_URL"); ?>/admin/vistoria_imagens/"+imagem_vistoria_id,
            method: "PUT",
            data: {
                _token: _token,
                descricao: $("#descricao-foto-"+imagem_vistoria_id).val()
            },
            success: function(data) {
                if(data==1){
                    alert("Alterado com sucesso.")
                }
            },
            error: function() {
                alert("Tente novamente.")
            }
        });
      }
    </script>
@endpush
