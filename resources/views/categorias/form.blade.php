<div class="row">
  <div class="col-md-8">
<div class="form-group">
           <label>Escolha uma super categoria</label>
           <select name="categoria_pai_id" class="form-control" >
              <option value="">Fazer desta uma super categoria</option>
              @foreach($categorias as $c)
                <option <?php if(isset($categoria->categoria_pai_id) && $categoria->categoria_pai_id==$c->id) echo "selected"; ?> value="{{$c->id}}">{{$c->nome}}</option>
              @endforeach

           </select>
         </div>

         <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status" id="status">
                <option value="1" <?php if(isset($categoria->status) && $categoria->status==1) echo "selected"; ?>>Ativo</option>
                <option value="-1" <?php if(isset($categoria->status) && $categoria->status==-1) echo "selected"; ?>>Inativo</option>
            </select>
            @error('status')
              <label id="status-error" class="error mt-2 text-danger" for="status">{{ $message }}</label>
            @enderror
          </div>

         <div class="form-group">
            <label for="show_afiliado">Afiliado pode solicitar esta categoria pelo App?</label>
            <select class="form-control" name="show_afiliado" id="show_afiliado">
                <option value="1" <?php if(isset($categoria->show_afiliado) && $categoria->show_afiliado==1) echo "selected"; ?>>Sim</option>
                <option value="0" <?php if(isset($categoria->show_afiliado) && $categoria->show_afiliado==0) echo "selected"; ?>>Não</option>
            </select>
            @error('show_afiliado')
              <label id="show_afiliado-error" class="error mt-2 text-danger" for="show_afiliado">{{ $message }}</label>
            @enderror
          </div>

         <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', optional($categoria)->nome) }}" placeholder="Nome">
            @error('nome')
              <label id="nome-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" cols="30" rows="10" placeholder="Descrição" >{{ old('descricao', optional($categoria)->descricao) }}</textarea>
            @error('descricao')
              <label id="descricao-error" class="error mt-2 text-danger" for="descricao">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="imagem">Imagem</label>
            <div class="custom-file mb-3 form-group">
              <input type="file" class="custom-file-input" id="imagem" name="imagem">
              <label class="custom-file-label" for="imagem">Escolha um arquivo</label>
            </div>
            @if(isset($categoria->imagem) && $categoria->imagem)
                <h6>Imagem atual</h6>
                <label><input type="checkbox" name="remover_imagem"> - Remover imagem</label>
                <p><img src="{{ Storage::url($categoria->imagem) }}" alt="--"></p>
              @endif
            @error('imagem')
              <label id="imagem-error" class="error mt-2 text-danger" for="imagem">{{ $message }}</label>
            @enderror
          </div>
  </div>
</div>