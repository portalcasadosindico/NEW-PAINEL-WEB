          <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', optional($cidade)->nome) }}" placeholder="Nome">
            @error('nome')
              <label id="nome-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="estado_id">Estado</label>
            <select class="form-control" id="estado_id" name="estado_id" required="true">
                  <option style="display: none;" value=" old('estado_id', optional($cidade)->estado_id ?: '') == '' ? 'selected' : '' }}" disabled selected>Selecione um estado</option>
                @foreach ($estados as $key => $estado)
                  <option value="{{ $key }}" {{ old('estado_id', optional($cidade)->estado_id) == $key ? 'selected' : '' }}>
                    {{ $estado }}
                  </option>
                @endforeach
            </select>
            @error('estado_id')
                <label id="estado_id-error" class="error mt-2 text-danger" for="estado_id">{{ $message }}</label>
            @enderror
        </div>  