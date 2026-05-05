<div class="row">
  <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                  <div class="form-group">
                    <h6>Dados vistoria</h6>
                  </div>
                  <div class="form-group">
                    <label for="vistoriador_id">Status</label>
                    <select class="form-control" id="status" name="status" required="true">
                        <option value="pendente" <?php if(isset($vistoria) && $vistoria->status=="pendente") echo "selected"; ?>>Em aberto</option>
                        <option value="concluido" <?php if(isset($vistoria) && $vistoria->status=="concluido") echo "selected"; ?>>Concluido</option>
                        <option value="cancelado" <?php if(isset($vistoria) && $vistoria->status=="cancelado") echo "selected"; ?>>Cancelado</option>
                    </select>
                    @error('status')
                        <label id="status-error" class="error mt-2 text-danger" for="status">{{ $message }}</label>
                    @enderror
                </div>
                  <div class="form-group">
                      <label for="vistoriador_id">Vistoriador</label>
                      <select class="form-control" id="vistoriador_id" name="vistoriador_id" required="true">
                            <option style="display: none;" value=" old('vistoriador_id', optional($vistoria)->vistoriador_id ?: '') == '' ? 'selected' : '' }}" disabled selected>Selecione um vistoriador</option>
                          @foreach ($vistoriadores as $key => $vistoriador)
                            <option value="{{ $key }}" {{ old('vistoriador_id', optional($vistoria)->vistoriador_id) == $key ? 'selected' : '' }}>
                              {{ $vistoriador }}
                            </option>
                          @endforeach
                      </select>
                      @error('vistoriador_id')
                          <label id="vistoriador_id-error" class="error mt-2 text-danger" for="vistoriador_id">{{ $message }}</label>
                      @enderror
                  </div>  
                  <div class="form-group">
                    <label for="orcamento_id">Serviço</label>
                    <select class="form-control" id="orcamento_id" name="orcamento_id" required="true">
                          <option style="display: none;" value=" old('orcamento_id', optional($vistoria)->orcamento_id ?: '') == '' ? 'selected' : '' }}" disabled selected>Selecione um serviço</option>
                          @foreach ($orcamentos as $key => $orcamento)
                            <option value="{{ $orcamento->id }}" {{ old('orcamento_id', optional($vistoria)->orcamento_id) == $orcamento->id ? 'selected' : '' }}>
                              {{ $orcamento->id }}: {{ $orcamento->nome }}
                            </option>
                          @endforeach
                    </select>
                    @error('orcamento_id')
                        <label id="orcamento_id-error" class="error mt-2 text-danger" for="orcamento_id">{{ $message }}</label>
                    @enderror
                </div>  
                  <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao" cols="30" rows="10" placeholder="Descrição" >{{ old('descricao', optional($vistoria)->descricao) }}</textarea>
                    @error('descricao')
                      <label id="descricao-error" class="error mt-2 text-danger" for="descricao">{{ $message }}</label>
                    @enderror
                  </div>
            </div>
        </div>
  </div>
  <div class="col-md-6">

      
    <div class="card">
        <div class="card-body">
              <div class="form-group">
                <h6>Datas vistoria</h6>
              </div>
              <div class="form-group ">
                <label for="show_data_agendamento_sindico">Mostrar data de agendamento da vistoria para o síndico</label>
                <button type="button" class="btn-question" data-trigger="focus" data-toggle="popover" title="Mostrar data de agendamento da vistoria para o síndico" data-content="Se você não tiver certeza desta data, mas quer deixar registrada, então você possui a opção de ocultar esta informação do síndico."><i class="fa fa-question"></i></button>
                <label>
                  <input type="checkbox" checked class="mb-4 mb-md-0" id="show_data_agendamento_sindico" name="show_data_agendamento_sindico" <?php if(isset($vistoria) && $vistoria->show_data_agendamento_sindico==1) echo "checked"; ?> />
                  - Marque a caixa para que o síndico possa ver a data da vistoria
                </label>
              </div>
              <div class="form-group ">
                <label for="data_vistoria">Data vistoria</label>
                <input class="form-control mb-4 mb-md-0" id="data_vistoria" name="data_vistoria" data-inputmask-alias="99/99/9999" value="{{ old('data_vistoria', optional($vistoria)->data_vistoria) }}" />
              </div>
              <div class="form-group">
                <label for="hora_vistoria">Horário vistoria</label>
                <input class="form-control mb-4 mb-md-0" id="hora_vistoria" name="hora_vistoria"  data-inputmask-alias="99:99" value="{{ old('hora_vistoria', optional($vistoria)->hora_vistoria) }}" />
              </div>
        </div>
    </div>


    <div class="card">
      <div class="card-body">
            <div class="form-group">
              <h5>Check-in e Check-out</h5>
            </div>
            <div class="form-group ">
              <h6 class="mb-2">Mostrar Check-in e Check-out para o síndico</h6>
              <label for="show_data_checkin_checkout_sindico">Mostrar data de check-in e check-out para o síndico</label>
              <button type="button" class="btn-question" data-trigger="focus" data-toggle="popover" title="Mostrar data de check-in e check-out para o síndico" data-content="Você possui a opção de ocultar esta informação do síndico."><i class="fa fa-question"></i></button>
              <label>
                <input type="checkbox" checked class="mb-4 mb-md-0" id="show_data_checkin_checkout_sindico" name="show_data_checkin_checkout_sindico" <?php if(isset($vistoria) && $vistoria->show_data_checkin_checkout_sindico==1) echo "checked"; ?> />
                - Marque a caixa para que o síndico possa ver as datas de check-in e check-out.
              </label>
            </div>
            <div class="form-group ">
              <h6 class="mb-2">Check-in e Check-out automáticos</h6>
              <label for="checkin_automatico">Check-in e Check-out automáticos</label>
              <button type="button" checked class="btn-question" data-trigger="focus" data-toggle="popover" title="Check-in e Check-out com horário do sistema" data-content="Marque esta opção para que o vistoriador possa realizar o check-in e check-out clicando em um botão, sem a possibilidade de inserir esta informação manualmente."><i class="fa fa-question"></i></button>
              <label>
                <input onchange="infoCheckinManual(this)" type="checkbox" class="mb-4 mb-md-0" id="checkin_automatico" name="checkin_automatico" <?php if(isset($vistoria) && $vistoria->checkin_automatico==1) echo "checked"; ?> />
                - Marque a caixa para que os horários de check-in e check-out venham do sistema.
              </label>
              <label class="badge badge-info infoCheckinManual" style="display: none;">
                Esta opção só irá funcionar, se o status da vistoria estiver Em aberto. 
                Se você desejar que o vistoriador possa alterar ou inserir manualmente a data de check-in e check-out, 
                altere o status para Em aberto e mantenha esta caixa desmarcada.
              </label>
            </div>
      </div>
  </div>


  </div>
</div>

<script>
  function infoCheckinManual(obj){
   if(obj.checked){
    $(".infoCheckinManual").hide(300);
   } else {
    $(".infoCheckinManual").show(300);
   }
  }
</script>