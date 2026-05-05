          <div class="form-group">
            <label for="plano_disponivel_franqueado_id">Plano disponivel</label>
            <select class="form-control" id="plano_disponivel_franqueado_id" name="plano_disponivel_franqueado_id" required="true">
                  <option style="display: none;" value=" old('plano_disponivel_franqueado_id', optional($franqueado_regiao_plano_disponibilizado)->plano_disponivel_franqueado_id ?: '') == '' ? 'selected' : '' }}" disabled selected>Selecione uma regiao</option>
                @foreach ($planos_disponiveis_franqueado as $key => $plano_disponivel_franqueado)
                  <option value="{{ $key }}" {{ old('plano_disponivel_franqueado_id', optional($franqueado_regiao_plano_disponibilizado)->plano_disponivel_franqueado_id) == $key ? 'selected' : '' }}>
                    {{ $plano_disponivel_franqueado }}
                  </option>
                @endforeach
            </select>
            @error('plano_disponivel_franqueado_id')
                <label id="plano_disponivel_franqueado_id-error" class="error mt-2 text-danger" for="plano_disponivel_franqueado_id">{{ $message }}</label>
            @enderror
        </div>
          <div class="form-group">
            <label for="franqueado_regiao_id">Franqueado regiao</label>
            <select class="form-control" id="franqueado_regiao_id" name="franqueado_regiao_id" required="true">
                  <option style="display: none;" value=" old('franqueado_regiao_id', optional($franqueado_regiao_plano_disponibilizado)->franqueado_regiao_id ?: '') == '' ? 'selected' : '' }}" disabled selected>Selecione uma regiao</option>
                @foreach ($franqueado_regioes as $key => $franqueado_regiao)
                  <option value="{{ $key }}" {{ old('franqueado_regiao_id', optional($franqueado_regiao_plano_disponibilizado)->franqueado_regiao_id) == $key ? 'selected' : '' }}>
                    {{ $franqueado_regiao }}
                  </option>
                @endforeach
            </select>
            @error('franqueado_regiao_id')
                <label id="franqueado_regiao_id-error" class="error mt-2 text-danger" for="franqueado_regiao_id">{{ $message }}</label>
            @enderror
        </div>
