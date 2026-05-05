
<div class="form-group {{ $errors->has('titulo') ? 'has-error' : '' }}">
    <label for="titulo" class="col-md-2 control-label">Titulo</label>
    <div class="col-md-10">
        <input class="form-control" name="titulo" type="text" id="titulo" value="{{ old('titulo', optional($termoUso)->titulo) }}" minlength="1" maxlength="45" required="true" placeholder="Enter titulo here...">
        {!! $errors->first('titulo', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('texto') ? 'has-error' : '' }}">
    <label for="texto" class="col-md-2 control-label">Texto</label>
    <div class="col-md-10">
        <textarea class="form-control tinymceExample" name="texto" rows="30">{{ old('texto', optional($termoUso)->texto) }}</textarea>
        {!! $errors->first('texto', '<p class="help-block">:message</p>') !!}
    </div>
</div>
