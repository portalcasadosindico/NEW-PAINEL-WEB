          <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', optional($estado)->nome) }}" placeholder="Nome">
            @error('nome')
              <label id="nome-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="uf">Uf</label>
            <input type="text" class="form-control" id="uf" name="uf" value="{{ old('uf', optional($estado)->uf) }}" autocomplete="off" placeholder="Uf">
            @error('uf')
              <label id="uf-error" class="error mt-2 text-danger" for="uf">{{ $message }}</label>
            @enderror
          </div>