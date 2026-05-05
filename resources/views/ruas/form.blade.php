          <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', optional($rua)->nome) }}" placeholder="Nome">
            @error('nome')
                <label id="nome-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="cep">Cep</label>
            <input type="text" class="form-control" id="cep" name="cep" value="{{ old('cep', optional($rua)->cep) }}" autocomplete="off" placeholder="Cep">
            @error('cep')
                <label id="cep-error" class="error mt-2 text-danger" for="cep">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="bairro_id">Bairro</label>
            <select class="form-control" id="bairro_id" name="bairro_id" required="true">
                  <option style="display: none;" value=" old('bairro_id', optional($rua)->bairro_id ?: '') == '' ? 'selected' : '' }}" disabled selected>Selecione um bairro</option>
                @foreach ($bairros as $key => $bairro)
                  <option value="{{ $key }}" {{ old('bairro_id', optional($rua)->bairro_id) == $key ? 'selected' : '' }}>
                    {{ $bairro }}
                  </option>
                @endforeach
            </select>
            @error('bairro_id')
                <label id="bairro_id-error" class="error mt-2 text-danger" for="bairro_id">{{ $message }}</label>
            @enderror
        </div>  