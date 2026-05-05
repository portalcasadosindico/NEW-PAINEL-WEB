<div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
    <label for="nome" class="col-md-2 control-label">Nome</label>
    <div class="col-md-10">
        <input class="form-control" name="nome" type="text" id="nome" value="{{ old('nome', optional($responsavelPolitica)->nome) }}" minlength="1" maxlength="45" required="true" placeholder="Enter nome here...">
        {!! $errors->first('nome', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
    <label for="email" class="col-md-2 control-label">Email</label>
    <div class="col-md-10">
        <input class="form-control" name="email" type="text" id="email" value="{{ old('email', optional($responsavelPolitica)->email) }}" minlength="1" maxlength="45" required="true" placeholder="Enter email here...">
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('telefone') ? 'has-error' : '' }}">
    <label for="telefone" class="col-md-2 control-label">Telefone</label>
    <div class="col-md-10">
        <input class="form-control" name="telefone" type="text" id="telefone" value="{{ old('telefone', optional($responsavelPolitica)->telefone) }}" minlength="1" maxlength="45" required="true" placeholder="Enter telefone here...">
        {!! $errors->first('telefone', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('cpf') ? 'has-error' : '' }}">
    <label for="cpf" class="col-md-2 control-label">Cpf</label>
    <div class="col-md-10">
        <input class="form-control" name="cpf" type="text" id="cpf" value="{{ old('cpf', optional($responsavelPolitica)->cpf) }}" minlength="1" maxlength="45" required="true" placeholder="Enter cpf here...">
        {!! $errors->first('cpf', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('politica_privacidade_id') ? 'has-error' : '' }}">
    <label for="politica_privacidade_id" class="col-md-2 control-label">Politica Privacidade</label>
    <div class="col-md-10">
        <select class="form-control" id="politica_privacidade_id" name="politica_privacidade_id" required="true">
            <option value="" style="display: none;" {{ old('politica_privacidade_id', optional($responsavelPolitica)->politica_privacidade_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select politica privacidade</option>
            @foreach ($PoliticaPrivacidades as $key => $PoliticaPrivacidade)
            <option value="{{ $key }}" {{ old('politica_privacidade_id', optional($responsavelPolitica)->politica_privacidade_id) == $key ? 'selected' : '' }}>
                {{ $PoliticaPrivacidade }}
            </option>
            @endforeach
        </select>

        {!! $errors->first('politica_privacidade_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
