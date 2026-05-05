
<div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
    <label for="nome" class="col-md-2 control-label">Nome</label>
    <div class="col-md-10">
        <input class="form-control" name="nome" type="text" id="nome" value="{{ old('nome', optional($canalAtendimento)->nome) }}" minlength="1" maxlength="45" required="true" placeholder="Nome do canal">
        {!! $errors->first('nome', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
    <label for="email" class="col-md-2 control-label">Email</label>
    <div class="col-md-10">
        <input class="form-control" name="email" type="text" id="email" value="{{ old('email', optional($canalAtendimento)->email) }}" minlength="1" maxlength="255" placeholder="E-mail">
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('telefone') ? 'has-error' : '' }}">
    <label for="telefone" class="col-md-2 control-label">Telefone</label>
    <div class="col-md-10">
        <input class="form-control" name="telefone" type="text" id="telefone" value="{{ old('telefone', optional($canalAtendimento)->telefone) }}" minlength="1" maxlength="255" placeholder="Telefone">
        {!! $errors->first('telefone', '<p class="help-block">:message</p>') !!}
    </div>
</div>