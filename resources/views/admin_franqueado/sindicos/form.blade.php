<div class="row">
  <div class="col-md-6">
      <div class="card">
        <div class="card-body">
        <div class="form-group">
          <h6>Dados pessoais</h6>
        </div>
        <div class="form-group">
            <label for="nome">Nome <i style="color: #FF0000; ">*</i></label>
            <input type="text" required class="form-control" id="nome" name="nome" value="{{ old('nome', optional($sindico)->nome) }}" autocomplete="off"  placeholder="Nome">
            @error('nome')
                <label id="nome-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="cpf">CPF <i style="color: #FF0000; ">*</i></label>
            <input type="text" required class="form-control" data-inputmask-alias="999.999.999-99" id="cpf" name="cpf" value="{{ old('cpf', optional($sindico)->CPF) }}" placeholder="Cpf">
            @error('cpf')
                <label id="cpf-error" class="error mt-2 text-danger" for="cpf">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="numero_documento">Número documento <i style="color: #FF0000; ">*</i></label>
            <input type="text" required class="form-control" id="numero_documento" name="numero_documento" value="{{ old('numero_documento', optional($sindico)->numero_documento) }}" autocomplete="off" placeholder="Numero documento">
            @error('numero_documento')
                <label id="numero_documento-error" class="error mt-2 text-danger" for="numero_documento">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="telefone">Telefone <i style="color: #FF0000; ">*</i></label>
            <input type="text" required class="form-control" id="telefone" data-inputmask-alias="(99) 99999-9999" name="telefone" value="{{ old('telefone', optional($sindico)->telefone) }}" autocomplete="off" placeholder="Telefone">
            @error('telefone')
                <label id="telefone-error" class="error mt-2 text-danger" for="telefone">{{ $message }}</label>
            @enderror
          </div>
        </div>
      </div>
  </div>
  <div class="col-md-6">

    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <h6>Mandato</h6>
            </div>
            <div class="form-group">
                <label for="nome">Data de inicio do mandato</label>
                <input
                    type="text"
                    class="form-control"
                    id="data_inicio_mandato"
                    name="data_inicio_mandato"
                    data-inputmask-alias="99/99/9999"
                    value="{{ old('data_inicio_mandato', optional($sindico)->usuarioApp ? optional($sindico)->data_inicio_mandato : null) }}"
                    autocomplete="off"
                    placeholder="Data de inicio"
                />
                @error('data_inicio_mandato')
                <label id="data_inicio_mandato-error" class="error mt-2 text-danger" for="data_inicio_mandato">{{ $message }}</label>
                @enderror
            </div>
            <div class="form-group">
                <label for="data_fim_mandato">Data de fim do mandato</label>
                <input type="text" class="form-control" data-inputmask-alias="99/99/9999" id="data_fim_mandato" name="data_fim_mandato" value="{{ old('data_fim_mandato', optional($sindico)->usuarioApp ? optional($sindico)->data_fim_mandato : null) }}" autocomplete="off" placeholder="Data de fim" />
                @error('data_fim_mandato')
                <label id="data_fim_mandato-error" class="error mt-2 text-danger" for="data_fim_mandato">{{ $message }}</label>
                @enderror
            </div>
        </div>
    </div>

      <div class="card">
          <div class="card-body">
          <div class="form-group">
            <h6>Credenciais de acesso pelo App</h6>
          </div>
            <div class="form-group">
              <label for="nome">E-mail para login pelo App <i style="color: #FF0000; ">*</i></label>
              <input type="email" required class="form-control" id="email" name="email" value="{{ old('email', optional($sindico)->usuarioApp ? optional($sindico)->usuarioApp->email : null) }}" autocomplete="off" placeholder="E-mail">
              @error('email')
                  <label id="email-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
              @enderror
            </div>
            <div class="form-group">
              <label for="nome">Senha para o login pelo App <i style="color: #FF0000; ">*</i></label>
              <input type="text" class="form-control" id="senha" name="senha" value="" autocomplete="off" placeholder="Senha">
              @error('senha')
                  <label id="senha-error" class="error mt-2 text-danger" for="senha">{{ $message }}</label>
              @enderror
            </div>   
          </div>
      </div> 
  </div>
</div>