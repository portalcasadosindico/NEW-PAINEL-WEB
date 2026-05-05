  <div class="row">
    <div class="col-md-6">

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
          <input type="text" class="form-control" id="valor_comissao" name="valor_comissao" value="{{ old('valor_comissao', optional($plano_disponivel_franqueado)->valor_comissao) }}" autocomplete="off" placeholder="Valor comissao">
          @error('valor_comissao')
            <label id="valor_comissao-error" class="error mt-2 text-danger" for="valor_comissao">{{ $message }}</label>
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
          <label for="ciclo">Ciclo {{$plano_disponivel_franqueado->ciclo}}</label>
          <select name="ciclo" id="ciclo">
            <option value="MONTHLY" <?php if(isset($plano_disponivel_franqueado) && $plano_disponivel_franqueado->ciclo=="MONTHLY") echo "selected"; ?>>Mensal</option>
            <option value="QUARTERLY" <?php if(isset($plano_disponivel_franqueado) && $plano_disponivel_franqueado->ciclo=="QUARTERLY") echo "selected"; ?>>Trimestral</option>
            <option value="SEMIANNUALLY" <?php if(isset($plano_disponivel_franqueado) && $plano_disponivel_franqueado->ciclo=="SEMIANNUALLY") echo "selected"; ?>>Semestral</option>
            <option value="YEARLY" <?php if(isset($plano_disponivel_franqueado) && $plano_disponivel_franqueado->ciclo=="YEARLY") echo "selected"; ?>>Anual</option>
          </select>
        </div>
        <div class="form-group">
          <label for="quantidade_meses_vigencia">Quantidade meses vigência</label>
          <input type="text" class="form-control" id="quantidade_meses_vigencia" name="quantidade_meses_vigencia" value="{{ old('quantidade_meses_vigencia', optional($plano_disponivel_franqueado)->quantidade_meses_vigencia) }}" autocomplete="off" placeholder="Quantidade meses vigência">
          @error('quantidade_meses_vigencia')
            <label id="quantidade_meses_vigencia-error" class="error mt-2 text-danger" for="quantidade_meses_vigencia">{{ $message }}</label>
          @enderror
        </div>
        <div class="form-group">
          <label for="dias_trial">Dias de teste</label>
          <input type="text" class="form-control" id="dias_trial" name="dias_trial" value="{{ old('dias_trial', optional($plano_disponivel_franqueado)->dias_trial) }}" autocomplete="off" placeholder="Dias de teste">
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
    </div>
  </div>