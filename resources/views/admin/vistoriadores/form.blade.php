      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
                <div class="form-group">
                  <h6>Dados do vistoriador</h6>
                </div>
                  <div class="form-group">
                    <label for="nome">Nome do vistoriador</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', optional($vistoriador)->nome) }}" autocomplete="off" placeholder="Nome">
                    @error('nome')
                        <label id="nome-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="dados_acesso_condominio">Dados adicionais</label>
                    <button type="button" class="btn-question" data-trigger="focus" data-toggle="popover" title="Saiba para que pode servir" data-content="O síndico poderá utilizar para cadastrar o vistoriador na portaria. Dados como CPF, Placa do carro e etc. Esta informação irá aparecer para o síndico quando houver uma solicitação de vistoria e um vistoriador for delegado para a tarefa."><i class="fa fa-question"></i></button>
                    <textarea class="form-control" rows="6" id="dados_acesso_condominio" name="dados_acesso_condominio" autocomplete="off" placeholder="Informe dados adicionais para o síndico deixar liberado na portaria">{{ old('dados_acesso_condominio', optional($vistoriador)->dados_acesso_condominio) }}</textarea>
                    @error('dados_acesso_condominio')
                        <label id="dados_acesso_condominio-error" class="error mt-2 text-danger" for="dados_acesso_condominio">{{ $message }}</label>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="franqueado_id">Qual franqueado ele pertence</label>
                    <select class="form-control" id="franqueado_id" name="franqueado_id" >
                          <option style="display: none;" value=" old('franqueado_id', optional($vistoriador)->franqueado_id ?: '') == '' ? 'selected' : '' }}" disabled selected>Selecione um franqueado</option>
                          <option value="0">Todos os franqueados</option>
                          @foreach ($franqueados as $key => $franqueado)
                          <option value="{{ $key }}" {{ old('franqueado_id', optional($vistoriador)->franqueado_id) == $key ? 'selected' : '' }}>
                            {{ $franqueado }}
                          </option>
                        @endforeach
                    </select>
                    @error('franqueado_id')
                        <label id="franqueado_id-error" class="error mt-2 text-danger" for="franqueado_id">{{ $message }}</label>
                    @enderror
                  </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                  <div class="form-group">
                    <h6>Credenciais de acesso pelo App</h6>
                  </div>
                    <div class="form-group">
                      <label for="nome">E-mail para login pelo App</label>
                      <input type="email" class="form-control" id="email" name="email" value="{{ old('email', optional($vistoriador)->usuarioApp ? optional($vistoriador)->usuarioApp->email : null) }}"  placeholder="E-mail">
                      @error('email')
                          <label id="email-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="nome">Senha para o login pelo App</label>
                      <input type="pasword" class="form-control" id="senha" name="senha" value=""  placeholder="Senha">
                      @error('senha')
                          <label id="senha-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
                      @enderror
                    </div> 
              </div>
            </div>   
        </div>
      </div>
