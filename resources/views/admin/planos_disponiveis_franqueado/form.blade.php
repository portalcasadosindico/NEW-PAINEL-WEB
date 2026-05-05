  <div class="row">
    <div class="col-md-6">
        <div class="form-group">
          <label for="tipo">Tipo</label>
          <select name="tipo" class="form-control">
            <option value="0" <?php if(isset($plano_disponivel_franqueado->tipo) && $plano_disponivel_franqueado->tipo==0) echo "selected"; ?>>Disponibilizar para Afiliados de franquias</option>
            <option value="1" <?php if(isset($plano_disponivel_franqueado->tipo) && $plano_disponivel_franqueado->tipo==1) echo "selected"; ?>>Disponibilizar para parceiros</option>
          </select>
          @error('tipo')
            <label id="tipo-error" class="error mt-2 text-danger" for="tipo">{{ $message }}</label>
          @enderror
        </div>
        <div class="form-group">
          <label for="is_public">Tipo de visualização</label>
          <select name="is_public" class="form-control">
            <option value="1" <?php if(isset($plano_disponivel_franqueado->is_public) && $plano_disponivel_franqueado->is_public==1) echo "selected"; ?>>Público - Aparece para os afiliados no app</option>
            <option value="0" <?php if(isset($plano_disponivel_franqueado->is_public) && $plano_disponivel_franqueado->is_public==0) echo "selected"; ?>>Privado - Aparece somente para os admins</option>
          </select>
          @error('is_public')
            <label id="is_public-error" class="error mt-2 text-danger" for="is_public">{{ $message }}</label>
          @enderror
        </div>
        <div class="form-group">
          <label for="nome">Nome</label>
          <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', optional($plano_disponivel_franqueado)->nome) }}" placeholder="Nome">
          @error('nome')
            <label id="nome-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
          @enderror
        </div>
        <div class="form-group">
          <label for="descricao">Descrição</label>
          <textarea class="form-control" id="descricao" name="descricao" cols="30" rows="10" placeholder="Descrição" >{{ old('descricao', optional($plano_disponivel_franqueado)->descricao) }}</textarea>
          @error('descricao')
            <label id="descricao-error" class="error mt-2 text-danger" for="descricao">{{ $message }}</label>
          @enderror
        </div>
        <div class="form-group">
          <label for="valor">Valor</label>
          <input type="text" class="form-control" id="valor" name="valor" value="{{ old('valor', optional($plano_disponivel_franqueado)->valor) }}" autocomplete="off" placeholder="Valor">
          @error('valor')
            <label id="valor-error" class="error mt-2 text-danger" for="valor">{{ $message }}</label>
          @enderror
        </div>
        <div class="form-group">
          <label for="valor_comissao">Valor comissão (%)</label>
          <button type="button"  class="btn-question" data-trigger="focus" data-toggle="popover" title="Saiba onde o sistema utiliza" data-content="O sistema utiliza para geração automática dos contratos. Quando um afiliado assinar um plano com valor de comissão maior do que 0%, o contrato será gerado no modelo Comissionado, caso contrário, será gerado com o modelo Não Comissionado, isso se, a caixa Terceirizada não estiver marcada."><i class="fa fa-question"></i></button>
          <input type="text" class="form-control" id="valor_comissao" name="valor_comissao" value="{{ old('valor_comissao', optional($plano_disponivel_franqueado)->valor_comissao) }}" autocomplete="off" placeholder="Valor comissao">
          @error('valor_comissao')
            <label id="valor_comissao-error" class="error mt-2 text-danger" for="valor_comissao">{{ $message }}</label>
          @enderror
        </div>
        <div class="form-group">
          <label for="desconto">Valor desconto (%)</label>
          <input type="text" class="form-control" id="desconto" name="desconto" value="{{ old('desconto', optional($plano_disponivel_franqueado)->desconto) }}" autocomplete="off" placeholder="Valor do desconto até o vencimento">
          @error('desconto')
            <label id="desconto-error" class="error mt-2 text-danger" for="desconto">{{ $message }}</label>
          @enderror
        </div>

    </div>
    <div class="col-md-6">
        <div class="form-group">
          <label for="statusPlano">Status</label>
          <select class="form-control" id="statusPlano" name="statusPlano" required="true">
                <option value="1" {{ old('status', optional($plano_disponivel_franqueado)->status) == 1 ? 'selected' : '' }}>Ativo</option>
                <option value="0" {{ old('status', optional($plano_disponivel_franqueado)->status) === "0" ? 'selected' : '' }}>Inativo</option>
          </select>
          @error('statusPlano')
              <label id="statusPlano-error" class="error mt-2 text-danger" for="statusPlano">{{ $message }}</label>
          @enderror
        </div>
        <div class="form-group">
          <label for="ciclo">Ciclo de pagamento</label>
          <select name="ciclo" id="ciclo">
            <option value="MONTHLY" <?php if(isset($plano_disponivel_franqueado) && $plano_disponivel_franqueado->ciclo=="MONTHLY") echo "selected"; ?>>Mensal</option>
            <option value="QUARTERLY" <?php if(isset($plano_disponivel_franqueado) && $plano_disponivel_franqueado->ciclo=="QUARTERLY") echo "selected"; ?>>Trimestral</option>
            <option value="SEMIANNUALLY" <?php if(isset($plano_disponivel_franqueado) && $plano_disponivel_franqueado->ciclo=="SEMIANNUALLY") echo "selected"; ?>>Semestral</option>
            <option value="YEARLY" <?php if(isset($plano_disponivel_franqueado) && $plano_disponivel_franqueado->ciclo=="YEARLY") echo "selected"; ?>>Anual</option>
          </select>
        </div>
        <div class="form-group" style="display: none;">
          <label for="dias_trial">Dias de teste</label>
          <input type="text" class="form-control" id="dias_trial" name="dias_trial" value="0" autocomplete="off" placeholder="Dias de teste">
          @error('dias_trial')
            <label id="dias_trial-error" class="error mt-2 text-danger" for="dias_trial">{{ $message }}</label>
          @enderror
        </div>
        <div class="form-group">
          <label for="regiao_id">Regiao</label>
          <select class="form-control" id="regiao_id" name="regiao_id">
                <option value="0" selected>Todas as regiões</option>
              @foreach ($regioes as $key => $regiao)
                <option value="{{ $key }}" {{ old('regiao_id', optional($plano_disponivel_franqueado)->regiao_id) == $key ? 'selected' : '' }}>
                  {{ $regiao }}
                </option>
              @endforeach
          </select>
          @error('regiao_id')
              <label id="regiao_id-error" class="error mt-2 text-danger" for="regiao_id">{{ $message }}</label>
          @enderror
      </div>

      <div class="card">
        <div class="card-body">
          <div class="form-group">
            <label>Plano terceirizada</label>
            <button type="button"  class="btn-question" data-trigger="focus" data-toggle="popover" title="Saiba onde o sistema utiliza" data-content="O sistema utiliza para geração automática dos contratos. Quando um afiliado assinar um plano que esteja marcado como terceirizada, o contrato será gerado no modelo Terceirizada, independente se há comissão ou não."><i class="fa fa-question"></i></button>
            <div class="row">
              <div class="col-3 text-left">
                <input type="checkbox" class="form-control" <?php if(isset($plano_disponivel_franqueado) && $plano_disponivel_franqueado->isTerceirizada=="1") echo "checked" ?>  name="isTerceirizada" id="isTerceirizada">
              </div>
              <div class="col-9">
                <label for="isTerceirizada" style="position: relative; ">Se este for um plano para terceirizada, marque esta caixa.</label>
              </div>
            </div>
          </div>  
        </div>
      </div>

    </div>
  </div>