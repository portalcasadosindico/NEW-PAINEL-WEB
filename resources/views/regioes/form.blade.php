
<div class="row">
    <div class="col-md-6">
          <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', optional($regiao)->nome) }}" placeholder="Nome">
            @error('nome')
                <label id="nome-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
            @enderror
          </div>
    </div>
    <div class="col-md-6">
          <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" cols="30" rows="6" placeholder="Descrição" >{{ old('descricao', optional($regiao)->descricao) }}</textarea>
            @error('descricao')
              <label id="descricao-error" class="error mt-2 text-danger" for="descricao">{{ $message }}</label>
            @enderror
          </div>
    </div>

    <div class="col-md-6">
          <div class="form-group">
            <label for="descricao">Informe faixas de CEP (Separe cada CEP com uma quebra de linha)</label>
            <textarea class="form-control" id="ceps" name="ceps" cols="30" rows="6" placeholder="Exemplo:
88735000
88136*
8812*
..." >{{isset($ceps) ? $ceps : null}}</textarea>
            @error('descricao')
              <label id="descricao-error" class="error mt-2 text-danger" for="descricao">{{ $message }}</label>
            @enderror
          </div>
    </div>
</div>

<button class="btn btn-warning" style="margin-bottom: 16px;" onclick="return false;" data-toggle="modal" data-target="#add_local">Adicionar Cidade ou Bairros</button>

<h5>Cidades escolhidas</h5>
<div class="container-locais" style="margin-bottom:20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); padding: 16px;">
      @if(isset($cidades))
          @foreach($cidades as $i=>$c)
              <label style='display: inline-block; margin-right: 12px;' class='badge badge-info cidade-<?php echo $i; ?>'>{{$c->nome}}/{{$c->uf}} - <b style='color: #990000; cursor: pointer;' onclick="removerCidade(<?php echo $i; ?>)">X</b></label>
              <input type='hidden' class='input-cidade-<?php echo $i; ?>' name='cidades[]' value="{{$c->id}}" >
          @endforeach
      @endif
</div>

<h5>Bairros escolhidos</h5>
<div class="container-locais-bairros" style="margin-bottom:20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); padding: 16px;">  
    @if(isset($bairros))
        @foreach($bairros as $cidade => $bairros)
          <div style='margin-top: 15px;' class='container-bairros-selecionados'>
            <h6>Bairros de {{$cidade}}</h6>
            <div class='bairros'>
              @foreach($bairros as $i => $b)
                  <label style='display: inline-block; margin-right: 12px;' class='badge badge-info bairro-add-<?php echo $i; ?>'>{{$b->nome}} - <b style='color: #990000; cursor: pointer;' onclick="removerBairro(<?php echo $i; ?>)">X</b></label>
                  <input type='hidden' class='input-bairro-add-<?php echo $i; ?>' name='bairrosAdd[]' value="{{$b->id}}" >
              @endforeach
            </div>
          </div>
        @endforeach
    @endif
</div>

<!-- Modal -->
<div class="modal fade" id="add_local" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabelCondominio" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabelCondominio">Selecione um local</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                    <h3>Escolha os locais</h3>

                    <div class="row">
                      <div class="col-md-5">
                        <div class="form-group">
                            <label for="estado_id">Estado</label>
                            <select class="form-control"  id="estado_id">
                                  <option value="">Selecione um estado</option>
                                @foreach ($estados as $key => $estado)
                                  <option value="{{ $key }}" {{ old('estado', optional($estado)->id) == $key ? 'selected' : '' }}>
                                    {{ $estado }}
                                  </option>
                                @endforeach
                            </select>
                        </div>
                      </div>

                      <div class="col-md-7">
                        <div class="form-group">
                          <label for="cidade_id">Cidade</label>
                          <select class="form-control" id="cidade_id" size="8">
                            <option value="">Selecione uma cidade</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                            <label for="toda_cidade">
                              <input type="checkbox" onchange="showBairros(this.checked)" id="toda_cidade" checked name="toda_cidade">
                              - Toda a cidade será parte desta região
                            </label>
                            <br><br>
                            <div class="dados_bairros" style="display: none; max-height: 360px; overflow: auto;">
                                <label for="bairro_id">Bairros</label>
                                <br>
                                <table class="dataTable">
                                  <tbody id="bairro_id">
                                    <tr><td>Selecione uma cidade</td></tr>
                                  </tbody>
                                </table>
                              </div>
                        </div>
                      </div>
                    </div>
              </div>
            </div> 

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">Cancelar</button>
                <button id="btn-cadastrar-condominio" type="button" onclick="addLocal()"
                    class="btn btn-primary">Adicionar</button>
            </div>
        </div>
    </div>
</div> <!-- Fim modal -->

<script type="text/javascript">
$(document).ready(function () {
  $('#estado_id').on('change',function(e) {
     if($(this).val() != ''){
        var value = $(this).val();
        var _token = $('input[name="_token"]').val();
        $('#cidade_id').empty();
        $('#cidade_id').append('<option value="">Carregando...</option>');
        $.ajax({
            url:"{{ route('regioes.fetchEstado') }}",
            method:"POST",
            data:{
                value:value,_token:_token
            },
            success:function(data){
                $('#cidade_id').empty();
                $('#cidade_id').append('<option value="">Selecione uma cidade</option>');
                $.each(data.data,function(index,cidade){
                  if(cidade.cidade_regiao){
                    if(cidade.cidade_regiao.id>0){
                      $('#cidade_id').append("<option disabled value='"+JSON.stringify(cidade)+"'>"+cidade.nome+"</option>");
                    }
                  } else {
                    $('#cidade_id').append("<option value='"+JSON.stringify(cidade)+"'>"+cidade.nome+"</option>");
                  }
                  
                  
                })
            }

            
          });
    }
  });
  $('#cidade_id').on('change',function(e) {
     if($(this).val() != ''){
      var url = window.location.href;
      var new_url = url.split('/');
        var value = JSON.parse($(this).val());
        var _token = $('input[name="_token"]').val();
        var regiao_id = new_url[5];
        $('#bairro_id').html("<tr><td>Carregando...</td></tr>");
        $.ajax({
            url:"{{ route('regioes.fetchCidade') }}",
            method:"POST",
            data:{
                value:value.id,_token:_token,regiao_id:regiao_id
            },
            success:function(data){
              $('#bairro_id').empty();
                  console.log(data);
                  $('#bairro_id').append(data);
                 /* $.each(data.data,function(index,bairro){
                    console.log(bairro.nome);
                    
                  });*/
            }
          });
    }
  });
});

var locaisCont = 0;
var locaisBairrosCont = 0;

function addLocal(){
  if(document.getElementById("toda_cidade").checked){
    var cidade = JSON.parse($("#cidade_id").val());
    $(".container-locais").append("<label style='display: inline-block; margin-right: 12px;' class='badge badge-info cidade-"+locaisCont+"'>"+cidade.nome+"/"+cidade.uf+" - <b style='color: #990000; cursor: pointer;' onclick='removerCidade("+locaisCont+")'>X</b></label>");
    $(".container-locais").append("<input type='hidden' class='input-cidade-"+locaisCont+"' name='cidades[]' value='"+cidade.id+"' >");
    locaisCont++;
  } else {
    var cidade = JSON.parse($("#cidade_id").val());
    var bairros = $("input[name='bairro[]'");

    var html = "<div style='margin-top: 15px;' class='container-bairros-selecionados cidade-"+cidade.id+"'>";
    html += "<h6>Bairros de " + cidade.nome + "</h6>";
    html += "<div class='bairros'>";

    for(var i=0; i<bairros.length; i++){
      var b = document.getElementById(bairros[i].id);
      if(b.checked && !b.disabled){
        locaisBairrosCont++;
        var bairroAdd = JSON.parse(b.value);
        html += "<label style='display: inline-block; margin-right: 12px;' class='badge badge-info bairro-add-"+locaisBairrosCont+"'>"+bairroAdd.nome+" - <b style='color: #990000; cursor: pointer;' onclick='removerBairro("+locaisBairrosCont+")'>X</b></label>";
        html += "<input type='hidden' class='input-bairro-add-"+locaisBairrosCont+"' name='bairrosAdd[]' value='"+bairroAdd.id+"' >"
      } 
      
    }

    html += "</div>";

    html += "</div>";

    $(".container-locais-bairros").append(html);
  }
  $("#add_local").modal("hide");
}

function removerCidade(index){
  $(".cidade-"+index).remove();
  $(".input-cidade-"+index).remove();
}

function removerBairro(index){
  $(".bairro-add-"+index).remove();
  $(".input-bairro-add-"+index).remove();
}

function showBairros(check){
  if(check){
    $(".dados_bairros").hide(300);
  } else {
    $(".dados_bairros").show(300);
  }
}

<?php

  if(optional($regiao)->toda_cidade){
    echo "<script>showBairros('"+optional($regiao)->toda_cidade+"')</script>";
  } else {
    
  }
?>


</script>
