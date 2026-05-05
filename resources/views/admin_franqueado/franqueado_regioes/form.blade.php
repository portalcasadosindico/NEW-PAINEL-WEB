<?php

use Illuminate\Support\Facades\Auth;

$id = Auth::guard('franqueados')->user()->id;
?>
<div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required="true">
                  <option style="display: none;" value=" old('status', optional($franqueado_regiao)->status ?: '') == '' ? 'selected' : '' }}" disabled selected>Selecione um status</option>
                  <option value="inativo" {{ old('status', optional($franqueado_regiao)->status) == 'inativo' ? 'selected' : '' }}>Inativo</option>
                  <option value="ativo" {{ old('status', optional($franqueado_regiao)->status) == 'ativo' ? 'selected' : '' }}>Ativo</option>
            </select>
            @error('status')
                <label id="status-error" class="error mt-2 text-danger" for="status">{{ $message }}</label>
            @enderror
        </div>
          <div class="form-group">
            <label for="regiao_id">Regiao</label>
            <select class="form-control" id="regiao_id" name="regiao_id" required="true">
                  <option style="display: none;" value=" old('regiao_id', optional($franqueado_regiao)->regiao_id ?: '') == '' ? 'selected' : '' }}" disabled selected>Selecione uma regiao</option>
                @foreach ($regioes as $key => $regiao)
                  <option value="{{ $key }}" {{ old('regiao_id', optional($franqueado_regiao)->regiao_id) == $key ? 'selected' : '' }}>
                    {{ $regiao }}
                  </option>
                @endforeach
            </select>
            @error('regiao_id')
                <label id="regiao_id-error" class="error mt-2 text-danger" for="regiao_id">{{ $message }}</label>
            @enderror
        </div>
        <input type="hidden" name="franqueado_id" value="{{ $id }}">
        <input type="hidden" name="usuario_sistema_admin_id" value="{{ $id }}">
