<div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', optional($usuario_sistema_admin)->nome) }}" autocomplete="off" placeholder="Nome">
            @error('nome')
                <label id="nome-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', optional($usuario_sistema_admin)->email) }}" autocomplete="off" placeholder="Email">
            @error('email')
                <label id="email-error" class="error mt-2 text-danger" for="email">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" class="form-control" id="senha" name="senha" autocomplete="off" placeholder="Nova senha">
            @error('senha')
                <label id="senha-error" class="error mt-2 text-danger" for="senha">{{ $message }}</label>
            @enderror
          </div>
        