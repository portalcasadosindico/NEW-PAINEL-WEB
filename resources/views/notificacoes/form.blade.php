<?php
  use App\Models\Afiliado;
  use App\Models\Sindico;
?>

<input type="hidden" id="isSendGrupo" name="isSendGrupo" value="1" >
<div class="row">
  <div class="col-md-5">
        <div class="card">
          <div class="card-body">
              <div class="form-group">
                <h6 class="mb-2">Formas de envio</h6>
                <label>
                  <input type="checkbox"  id="isSendNotification" name="isSendNotification" value="true">
                  - Enviar via PUSH Notification e painel de notificação inteno do App
                </label>
                <br>
                <label>
                  <input type="checkbox" id="isSendEmail" name="isSendEmail" value="true">
                  - Enviar via e-mail e painel de notificação inteno do App
                </label>
              </div>
          </div>
        </div>
        

        <div class="form-group">
          <label for="titulo">Botão para orçamento de uma determinada categoria</label>
          <select name="categoria_id">
              <option value="">Sem botão</option>
              <option value="0">Sem categoria selecionada</option>
              @foreach($categorias as $categoria)
                <option value="{{$categoria->id}}">{{$categoria->nome}}</option>
              @endforeach
          </select>
          @error('titulo')
            <label id="titulo-error" class="error mt-2 text-danger" for="titulo">{{ $message }}</label>
          @enderror
        </div>

        <div class="form-group">
          <label for="titulo">Título</label>
          <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo', optional($notificacao)->titulo) }}" placeholder="Título">
          @error('titulo')
            <label id="titulo-error" class="error mt-2 text-danger" for="titulo">{{ $message }}</label>
          @enderror
        </div>
        <div class="form-group">
          <label for="corpo">Mensagem</label>
          <textarea class="form-control" rows="6" id="corpo" name="corpo" value="{{ old('corpo', optional($notificacao)->corpo) }}" autocomplete="off" placeholder="Digite aqui..."></textarea>
          @error('corpo')
            <label id="corpo-error" class="error mt-2 text-danger" for="corpo">{{ $message }}</label>
          @enderror
        </div>
  </div>

  <div class="col-md-7">
    <div class="card">

      <div class="card-body grupo-usuarios">
            <h4 class="mb-3">Grupo de usuários</h4>
            <div class="form-group">
              <h6 class="mb-2">Enviar para</h6>
              <label>
                <input type="checkbox" id="grupo-afiliado" name="grupo-sindico" value="sindico">
                - Síndicos
              </label>
              <br>
              <label>
                <input type="checkbox" id="grupo-afiliado" name="grupo-afiliado" value="afiliado">
                - Afiliados
              </label>
            </div>

            <div class="form-group">
              <h6 class="mb-3">Selecione as regiões</h6>
              @foreach($regioes as $regiao)
                <label class="d-block">
                  <input type="checkbox"  name="regioes[]" value="{{$regiao->id}}">
                  - {{$regiao->nome}}
                </label>
              @endforeach
            </div>

            <h4 class="mt-3 mb-3" style="color: #909090;">Ou Você pode também</h4>

            <div class="form-group">
              <h6 class="mb-3">Selecionar usuários individualmente?</h6>
              <button type="button"   class="btn btn-warning" onclick="showListaUsuarios()">Adicionar usuários</button>
            </div>
      </div>


      <div class="card-body lista-usuarios d-none">
        <h4 class="mb-3">Listas de Síndicos e Afiliados</h4>
        <div class="form-group">
          
          <h4 class="mt-5 mb-2">Selecionar síndicos</h4>
          <div class="form-group" style="max-height: 400px; overflow: auto;">
          <table data-page-length="500" id="dataTableExample" class="table table-striped dataTable no-footer" role="grid" aria-describedby="dataTableExample_info">
          <thead>
              <th>Afiliado</th>
            </thead>
            <tbody>
                  @foreach($sindicos as $sindico)
                    <tr>
                      <td>
                          @if($sindico->usuarioApp->token_notification)
                            <label class="d-block">
                                <input type="checkbox"  name="usuarios[]" value="{{$sindico->usuarioApp->id}}">
                                - {{$sindico->nome}} ({{$sindico->usuarioApp->tipo}})
                            </label>
                          @else
                            <label class="d-block" title="Ele não receberá o push, mas ficará nas notificações assim que ele voltar ao aplicativo.">
                              <input type="checkbox" name="usuarios[]" value="{{$sindico->usuarioApp->id}}" >
                              - {{$sindico->nome}} ({{$sindico->usuarioApp->tipo}}) (SEM TOKEN)
                              </label>
                          @endif

                        </td>
                      </tr>
                  @endforeach
                </tbody>
            </table>
          </div>

          <h4 class="mt-5 mb-2">Selecionar afiliados</h4>
          <div class="form-group" style="max-height: 400px; overflow: auto;">
          <table data-page-length="500" id="dataTableExample" class="table table-striped dataTable no-footer" role="grid" aria-describedby="dataTableExample_info">
            <thead>
              <th>Afiliado</th>
            </thead>
            <tbody>
              @foreach($afiliados as $afiliado)
                <tr>
                    <td>
                      @if($afiliado->usuarioApp->token_notification)
                        <label class="d-block">
                            <input type="checkbox"  name="usuarios[]" value="{{$afiliado->usuarioApp->id}}">
                            - {{$afiliado->razao_social ? $afiliado->razao_social : $afiliado->nome_fantasia}} ({{$afiliado->usuarioApp->tipo}})
                        </label>
                      @else
                        <label class="d-block" title="Ele não receberá o push, mas ficará nas notificações assim que ele voltar ao aplicativo.">
                          <input type="checkbox" name="usuarios[]" value="{{$afiliado->usuarioApp->id}}" >
                          - {{$afiliado->razao_social ? $afiliado->razao_social : $afiliado->nome_fantasia}} ({{$afiliado->usuarioApp->tipo}}) (SEM TOKEN)
                          </label>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <h4 class="mt-3 mb-3" style="color: #909090;">Ou Você pode também</h4>

          <div class="form-group">
            <h6 class="mb-3">Enviar para um grupo de usuários?</h6>
            <button type="button" class="btn btn-warning" onclick="showListaUsuarios()">Selecionar grupo</button>
          </div>
        </div>
      </div>



    </div>
  </div>
</div>

<script>
  function showListaUsuarios(){   
    if($(".grupo-usuarios").css("display")=="none"){
      $(".lista-usuarios").addClass("d-none");
      $(".grupo-usuarios").removeClass("d-none");
      $("#isSendGrupo").val(1);
    } else {
      $(".lista-usuarios").removeClass("d-none");
      $(".grupo-usuarios").addClass("d-none");
      $("#isSendGrupo").val(0);
    }
    
  }
</script>
