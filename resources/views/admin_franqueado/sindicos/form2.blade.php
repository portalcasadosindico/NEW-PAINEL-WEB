<div class="row">
  <div class="col-md-12">
      <div class="card">
        <div class="card-body">
        <div class="form-group">
          <h6>Dados pessoais</h6>
        </div>
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome_sindico" name="nome_sindico" value="{{ old('nome', optional($sindico)->nome) }}" autocomplete="off"  placeholder="Nome">
            <label id="nome_sindico-error" class="error mt-2 text-danger" for="nome_sindico">informe um nome</label>
          </div>
          <div class="form-group">
            <label for="cpf">CPF</label>
            <input type="text" class="form-control" id="cpf_sindico" data-inputmask-alias="999.999.999-99" name="cpf" value="{{ old('cpf', optional($sindico)->CPF) }}" placeholder="Cpf">
            <label id="cpf_sindico-error" class="error mt-2 text-danger" for="cpf">informe um CPF válido</label>
            <label id="exists-cpf-error" class="error mt-2 text-danger" for="email">Este CPF já está em uso</label>
          </div>
          <div class="form-group">
            <label for="numero_documento">Número documento</label>
            <input type="text" class="form-control" id="numero_documento_sindico" name="numero_documento" value="{{ old('numero_documento', optional($sindico)->numero_documento) }}" autocomplete="off" placeholder="Numero documento">
            <label id="numero_documento_sindico-error" class="error mt-2 text-danger" for="numero_documento">informe o número do documento</label>
          </div>
          <div class="form-group">
            <label for="telefone">Telefone</label>
            <input type="text" class="form-control" id="telefone_sindico" data-inputmask-alias="(99) 99999-9999" name="telefone" value="{{ old('telefone', optional($sindico)->telefone) }}" autocomplete="off" placeholder="Telefone">
            <label id="telefone_sindico-error" class="error mt-2 text-danger" for="telefone">Informe o telefone</label>
          </div>
          <div class="form-group">
                    <label for="data_inicio_mandato">Data de inicio do mandato</label>
                    <input
                        type="text"
                        class="form-control"
                        id="data_inicio_mandato"
                        name="data_inicio_mandato"
                        value="{{ old('data_inicio_mandato', optional($sindico)->usuarioApp ? optional($sindico)->data_inicio_mandato : null) }}"
                        autocomplete="off"
                        placeholder="Data de inicio"
                        data-inputmask-alias="99/99/9999"
                    />
                    @error('data_inicio_mandato')
                    <label id="data_inicio_mandato-error" class="error mt-2 text-danger" for="data_inicio_mandato">{{ $message }}</label>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="data_fim_mandato">Data de fim do mandato</label>
                    <input type="text" class="form-control" data-inputmask-alias="99/99/9999" id="data_fim_mandato" name="data_fim_mandato" value="" autocomplete="off" placeholder="Data de fim" />
                    @error('data_fim_mandato')
                    <label id="data_fim_mandato-error" class="error mt-2 text-danger" for="data_fim_mandato">{{ $message }}</label>
                    @enderror
                </div>
            </div>
        </div>
      </div>
  
  <div class="col-md-12">
      <div class="card">
          <div class="card-body">
          <div class="form-group">
            <h6>Credenciais de acesso pelo App</h6>
          </div>
            <div class="form-group">
              <label for="nome">E-mail para login pelo App</label>
              <input type="email" class="form-control" id="email_sindico" name="email" value="{{ old('email', optional($sindico)->usuarioApp ? optional($sindico)->usuarioApp->email : null) }}" autocomplete="off" placeholder="E-mail">
              <label id="email_sindico-error" class="error mt-2 text-danger" for="email">Informe um e-mail</label>
              <label id="exists-email-error" class="error mt-2 text-danger" for="email">Este e-mail já está em uso</label>
            </div>
            <div class="form-group">
              <label for="nome">Senha para o login pelo App</label>
              <input type="pasword" class="form-control" id="senha_sindico" name="senha" value="" autocomplete="off" placeholder="Senha">
              <label id="senha_sindico-error" class="error mt-2 text-danger" for="senha">Informe uma senha</label>
            </div>   
          </div>
      </div> 
  </div>
</div>