          <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', optional($bairro)->nome) }}" placeholder="Nome">
            @error('nome')
                <label id="nome-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="cidade_id">Cidade</label>
            <select class="form-control" id="cidade_id" name="cidade_id" required="true">
                  <option style="display: none;" value=" old('cidade_id', optional($bairro)->cidade_id ?: '') == '' ? 'selected' : '' }}" disabled selected>Selecione uma cidade</option>
                @foreach ($cidades as $key => $cidade)
                  <option value="{{ $key }}" {{ old('cidade_id', optional($bairro)->cidade_id) == $key ? 'selected' : '' }}>
                    {{ $cidade }}
                  </option>
                @endforeach
            </select>
            @error('cidade_id')
                <label id="cidade_id-error" class="error mt-2 text-danger" for="cidade_id">{{ $message }}</label>
            @enderror
        </div>
          <div class="form-group">
            <label for="regiao_id">Região</label>
            <select class="form-control" id="regiao_id" name="regiao_id">
                  <option value="0" selected>Selecione uma região</option>
                @foreach ($regioes as $key => $regiao)
                  <option value="{{ $key }}" {{ old('regiao_id', optional($bairro)->regiao_id) == $key ? 'selected' : '' }}>
                    {{ $regiao }}
                  </option>
                @endforeach
            </select>
            @error('regiao_id')
                <label id="regiao_id-error" class="error mt-2 text-danger" for="regiao_id">{{ $message }}</label>
            @enderror
        </div>