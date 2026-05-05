<div class="form-group">
    <label for="status">Status</label>
    <select class="form-control" id="status" name="status" >
        <option value="" disabled selected>Selecione um status</option>
        <option value="publicado" {{ old('status', optional($blog)->status) == 'publicado' ? 'selected' : '' }}>Publicar</option>
        <option value="rascunho" {{ old('status', optional($blog)->status) == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
        ]<option value="inativo" {{ old('status', optional($blog)->status) == 'inativo' ? 'selected' : '' }}>Inativo</option>
    </select>
    @error('status')
    <label id="status-error" class="error mt-2 text-danger" for="status">{{ $message }}</label>
    @enderror
</div>
<div class="form-group">
    <label for="nome">Nome</label>
    <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', optional($blog)->nome) }}" placeholder="Nome">
    @error('nome')
    <label id="nome-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
    @enderror
</div>
<div class="form-group">
    <label for="imagem_principal">Imagem principal</label>
    <div class="custom-file mb-3 form-group">
        <input type="file" class="custom-file-input" id="imagem_principal" name="imagem_principal">
        <label class="custom-file-label" for="imagem_principal">Escolha um arquivo</label>
    </div>
    @if(isset($blog->imagem_principal) && $blog->imagem_principal)
        <h6>Imagem principal</h6>
        <label><input type="checkbox" name="remover_imagem"> - Remover imagem</label><br>
        <img src="{{ Storage::url($blog->imagem_principal) }}" style="max-width: 180px;" alt="logo">
    @endif
    @error('imagem_principal')
    <label id="imagem_principal-error" class="error mt-2 text-danger" for="imagem_principal">{{ $message }}</label>
    @enderror
</div>
<div class="form-group">
    <label for="resumo">Resumo</label>
    <textarea class="form-control" name="resumo" rows="10">{{ old('resumo', optional($blog)->resumo) }}</textarea>
    @error('resumo')
    <label id="resumo-error" class="error mt-2 text-danger" for="resumo">{{ $message }}</label>
    @enderror
</div>
<div class="form-group">
    <label for="descricao">Descrição</label>
    <textarea class="form-control tinymceExample" name="descricao" rows="30">{{ old('descricao', optional($blog)->descricao) }}</textarea>
    @error('descricao')
    <label id="descricao-error" class="error mt-2 text-danger" for="descricao">{{ $message }}</label>
    @enderror
</div>

<div class="form-group">
    <label for="fonte">Fonte/Autores</label>
    <textarea placeholder="Fonte" class="form-control" name="fonte" rows="4">{{ old('fonte', optional($blog)->fonte) }}</textarea>
    @error('fonte')
    <label id="fonte-error" class="error mt-2 text-danger" for="fonte">{{ $message }}</label>
    @enderror
</div>

<div class="form-group">
    <label for="tags">TAG's (Separe as TAGS por linha)</label>
    <textarea class="form-control" placeholder="Separe as TAG's por linha" name="tags" rows="4">{{ old('tags', optional($blog)->tags) }}</textarea>
    @error('tags')
    <label id="tags-error" class="error mt-2 text-danger" for="tags">{{ $message }}</label>
    @enderror
</div>