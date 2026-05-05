<div class="row">
    <div class="col-sm-6">
        <div class="card">
          <div class="card-body">
              <div class="form-group">
                  <h6>Dados da empresa</h6>
              </div>
              <div class="form-group required">
                <label for="plano_id">Plano</label>
                <select id="plano_id" class="form-control" name="plano_id" required>
                    @foreach($planos as $plano)
                        <option <?php if(isset($parceiro->plano_id) && $parceiro->plano_id==$plano->id) echo "selected"; ?> value="{{$plano->id}}">{{$plano->nome}} - R${{$plano->valor}}</option>
                    @endforeach
                </select>
                <!--<br>
                <label>
                  <input type="checkbox" name="integrar_asaas" checked>
                  - Integrar ao ASAAS
                </label>-->
              </div>

              <div class="form-group required">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', optional($parceiro)->nome) }}" placeholder="Nome" required>
                <label id="nome-error" class="error mt-2 text-danger" style="display: none;" for="nome">Campo obrigatório</label>
                @error('nome')
                    <label id="nome-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group <?php if(!isset($parceiro->logo)) echo "required"; ?>">
                <label for="logo">Logo</label>
                <div class="custom-file mb-3 form-group">
                  <input type="file" class="custom-file-input form-control" id="logo" name="logo">
                  <label class="custom-file-label" for="logo">Escolha um arquivo</label>
                </div>
                <label id="nome-error" class="error mt-2 text-danger" style="display: none;" for="nome">Campo obrigatório</label>
                @error('logo')
                  <label id="logo-error" class="error mt-2 text-danger" for="logo">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group">
                <label for="link">Link para o site (Link completo com http:// ou https://)</label>
                <input type="url" validar="url" class="form-control" id="link" name="link" placeholder="Link completo. http://site.com" value="{{ old('link', optional($parceiro)->link) }}" autocomplete="off" placeholder="Link">
                  @error('link')
                    <label id="link-error" class="error mt-2 text-danger" for="link">{{ $message }}</label>
                @enderror
              </div>
              
              <div class="form-group required">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required="true">
                      <option value="">Selecione um status</option>
                      <option value="ativo" {{ old('status', optional($parceiro)->status) == 'ativo' ? 'selected' : '' }} >Ativo</option>
                      <option value="pendente"{{ old('status', optional($parceiro)->status) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                      <option value="inativo" {{ old('status', optional($parceiro)->status) == 'inativo' ? 'selected' : '' }}>Inativo</option>
                </select>
                <label id="nome-error" class="error mt-2 text-danger" style="display: none;" for="nome">Campo obrigatório</label>
                @error('status')
                    <label id="status-error" class="error mt-2 text-danger" for="status">{{ $message }}</label>
                @enderror
          </div>
        </div>
    </div>
    </div>
    <div class="col-sm-6">
        <div class="card">
          <div class="card-body">
              <div class="form-group">
                  <h6>Dados contato</h6>
              </div>
              <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" value="{{ old('telefone', optional($parceiro)->telefone) }}" autocomplete="off" placeholder="Telefone">
                @error('telefone')
                    <label id="telefone-error" class="error mt-2 text-danger" for="telefone">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', optional($parceiro)->email) }}" autocomplete="off" placeholder="Email">
                @error('email')
                    <label id="email-error" class="error mt-2 text-danger" for="email">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group">
                <label for="nome_responsavel">Nome responsável</label>
                <input type="text" class="form-control" id="nome_responsavel" name="nome_responsavel" value="{{ old('nome_responsavel', optional($parceiro)->nome_responsavel) }}" autocomplete="off" placeholder="Nome responsavel">
                @error('nome_responsavel')
                    <label id="nome_responsavel-error" class="error mt-2 text-danger" for="nome_responsavel">{{ $message }}</label>
                @enderror
              </div>
            </div> 
          </div>
        </div>
</div> 