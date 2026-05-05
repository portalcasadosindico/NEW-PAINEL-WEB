<?php use App\Models\Categoria; ?>
<div class="row">
    <div class="col-sm-6">
            
        <div class="card">
          <div class="card-body">
            <div class="form-group">
              <h4>Dados empresariais</h4>
            </div>
            <div class="form-group">
              <label for="logo">Logo</label>
              <div class="custom-file mb-3 form-group">
                <input type="file" class="custom-file-input" id="logo" name="logo">
                <label class="custom-file-label" for="logo">Escolha a Logo</label>
              </div>
              @if(optional($afiliado)->logo)
                <h6>Logo Atual</h6>
                <p title="Marque esta seleção para deixar este afiliado sem a logo após clicar em Salvar"><label><input type="checkbox" name="remover_logo"> - Remover logo</label></p>
                <img src="{{ Storage::url($afiliado->logo) }}" style="width: 130px;" >
              @endif
              @error('logo')
                <label id="logo-error" class="error mt-2 text-danger" for="logo">{{ $message }}</label>
              @enderror
            </div>
            <div class="form-group">
              <label for="cartao_cnpj">Cartão CNPJ</label>
              <div class="custom-file mb-3 form-group">
                <input type="file" class="custom-file-input" id="cartao_cnpj" name="cartao_cnpj" value="{{ old('cartao_cnpj', optional($afiliado)->cartao_cnpj) }}" autocomplete="off" placeholder="Cartão cnpj">
                <label class="custom-file-label" for="cartao_cnpj">Selecione o cartão CNPJ {{ optional($afiliado)->cartao_cnpj }}</label>
              </div>

              @if(optional($afiliado))
                @if($cartaoCNPJ)
                  <p title="Marque esta seleção para deixar este afiliado sem o Cartão CNPJ após clicar em Salvar"><label><input type="checkbox" name="remover_cartao_cnpj"> - Remover Cartão CNPJ</label></p>
                @endif
                
                @if($cartaoCNPJ && $cartaoCNPJ->status!="reprovado")
                  <p title="Marque esta seleção para deixar este afiliado com o Cartão CNPJ recusado após clicar em Salvar"><label><input type="checkbox" name="recusar_cartao_cnpj"> - RECUSAR Cartão CNPJ</label></p>
                @endif
              @endif

              @error('cartao_cnpj')
                  <label id="cartao_cnpj-error" class="error mt-2 text-danger" for="cartao_cnpj">{{ $message }}</label>
              @enderror
            </div>
            <div class="form-group">
              <label for="contrato_social">Contrato Social</label>
              <div class="custom-file mb-3 form-group">
                <input type="file" class="custom-file-input" id="contrato_social" name="contrato_social" value="{{ old('contrato_social', optional($afiliado)->contrato_social) }}" autocomplete="off" placeholder="Cartão cnpj">
                <label class="custom-file-label" for="contrato_social">Selecione o contrato social</label>
              </div>
              @if(optional($afiliado))
                @if($contratoSocial)
                  <p title="Marque esta seleção para deixar este afiliado sem o Contrato Social após clicar em Salvar"><label><input type="checkbox" name="remover_contrato_social"> - Remover Contrato Social</label></p>
                @endif
                
                @if($contratoSocial && $contratoSocial->status!="reprovado")
                  <p title="Marque esta seleção para deixar este afiliado com o Contrato Social recusado após clicar em Salvar"><label><input type="checkbox" name="recusar_contrato_social"> - RECUSAR Contrato Social</label></p>
                @endif
              @endif
              @error('contrato_social')
                  <label id="contrato_social-error" class="error mt-2 text-danger" for="contrato_social">{{ $message }}</label>
              @enderror
            </div>
            <div class="form-group">
              <label for="razao_social">Razão social <i style="color: #FF0000; ">*</i></label>
              <input type="text" class="form-control" id="razao_social" name="razao_social" required value="{{ old('razao_social', optional($afiliado)->razao_social) }}" autocomplete="off" placeholder="Razão social">
              @error('razao_social')
                  <label id="razao_social-error" class="error mt-2 text-danger" for="razao_social">{{ $message }}</label>
              @enderror
            </div>
            <div class="form-group">
              <label for="nome_fantasia">Nome fantasia <i style="color: #FF0000; ">*</i></label>
              <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia" required value="{{ old('nome_fantasia', optional($afiliado)->nome_fantasia) }}" placeholder="Nome fantasia">
              @error('nome_fantasia')
                  <label id="nome_fantasia-error" class="error mt-2 text-danger" for="nome_fantasia">{{ $message }}</label>
              @enderror
            </div>

            <div class="form-group">
              <label for="cnpj">Cnpj <i style="color: #FF0000; ">*</i></label>
              <input type="text" class="form-control" id="cnpj" data-inputmask-alias="99.999.999/9999-99" name="cnpj" value="{{ old('cnpj', optional($afiliado)->cnpj) }}" autocomplete="off" placeholder="Cnpj">
              @error('cnpj')
                  <label id="cnpj-error" class="error mt-2 text-danger" for="cnpj">{{ $message }}</label>
              @enderror
            </div>
            
            <div class="form-group">
              <label for="inscricao_estadual">Inscrição estadual</label>
              <input type="text" class="form-control" id="inscricao_estadual" name="inscricao_estadual" value="{{ old('inscricao_estadual', optional($afiliado)->inscricao_estadual) }}" autocomplete="off" placeholder="Inscrição Estadual">
              @error('inscricao_estadual')
                  <label id="inscricao_estadual-error" class="error mt-2 text-danger" for="inscricao_estadual">{{ $message }}</label>
              @enderror
            </div>
            <div class="form-group">
              <label for="inscricao_municipal">Inscrição municipal</label>
              <input type="text" class="form-control" id="inscricao_municipal" name="inscricao_municipal" value="{{ old('inscricao_municipal', optional($afiliado)->inscricao_municipal) }}" autocomplete="off" placeholder="Inscrição municipal">
              @error('inscricao_municipal')
                  <label id="inscricao_municipal-error" class="error mt-2 text-danger" for="inscricao_municipal">{{ $message }}</label>
              @enderror
            </div>
            <div class="form-group">
              <label for="rumo_atividade">Ramo atividade</label>
              <input type="text" class="form-control" id="rumo_atividade" name="rumo_atividade" value="{{ old('rumo_atividade', optional($afiliado)->rumo_atividade) }}" autocomplete="off" placeholder="Ramo atividade">
              @error('rumo_atividade')
                  <label id="rumo_atividade-error" class="error mt-2 text-danger" for="rumo_atividade">{{ $message }}</label>
              @enderror
            </div>
            <div class="form-group">
              <label for="numero_funcionarios">Número funcionários</label>
              <input type="text" class="form-control" id="numero_funcionarios" name="numero_funcionarios" value="{{ old('numero_funcionarios', optional($afiliado)->numero_funcionarios) }}" autocomplete="off" placeholder="Numero funcionarios">
              @error('numero_funcionarios')
                  <label id="numero_funcionarios-error" class="error mt-2 text-danger" for="numero_funcionarios">{{ $message }}</label>
              @enderror
            </div>
          </div>
        </div>
    </div>
    
    <div class="col-sm-6">
        <div class="card">
          <div class="card-body">
            <div class="form-group">
                <h4>Dados administrativos</h4>
            </div>
            <div class="form-group">
              <label for="email">E-mail para acesso ao App <i style="color: #FF0000; ">*</i></label>
              <input type="email" class="form-control" required id="email" name="email" value="{{ old('email', optional($afiliado)->usuarioApp ? optional($afiliado)->usuarioApp->email : null) }}" autocomplete="off" placeholder="E-mail">
              @error('email')
                  <label id="email-error" class="error mt-2 text-danger" for="email">{{ $message }}</label>
              @enderror
            </div>
            <div class="form-group">
              <label for="senha">Senha para acesso ao App <i style="color: #FF0000; ">*</i></label>
              <input type="text" class="form-control" id="senha" name="senha" value="" autocomplete="off" placeholder="Senha">
              @error('senha')
                  <label id="senha-error" class="error mt-2 text-danger" for="senha">{{ $message }}</label>
              @enderror
            </div>
            <div class="form-group">
              <label for="telefone">Telefone</label>
              <input type="text" class="form-control" data-inputmask-alias="(99) 99999-9999" id="telefone" name="telefone" value="{{ old('telefone', optional($afiliado)->telefone) }}" autocomplete="off" placeholder="Telefone">
              @error('telefone')
                  <label id="telefone-error" class="error mt-2 text-danger" for="telefone">{{ $message }}</label>
              @enderror
            </div>
          </div>
        </div>



      <div class="card">
        <div class="card-body">
            <div class="form-group">
              <h4>Endereço</h4>
            </div>
            <div class="form-group">
                <label for="cep">Cep</label>
                <input type="text" class="form-control" data-inputmask-alias="99999-999" id="cep" name="cep"  value="{{ old('cep', optional($afiliado)->cep) }}" autocomplete="off" placeholder="Cep">
                @error('cep')
                    <label id="cep-error" class="error mt-2 text-danger" for="cep">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group">
                <label for="estadof">Estado</label>
                <input type="text" class="form-control" id="estadof" name="estado" value="{{ old('estado', optional($afiliado)->estado) }}" autocomplete="off" placeholder="Estado">
                @error('estado')
                    <label id="estado-error" class="error mt-2 text-danger" for="estado">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group">
                <label for="cidadef">Cidade</label>
                <input type="text" class="form-control" id="cidadef" name="cidade" value="{{ old('cidade', optional($afiliado)->cidade) }}" autocomplete="off" placeholder="Cidade">
                @error('cidade')
                    <label id="cidade-error" class="error mt-2 text-danger" for="cidade">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group">
                <label for="bairrof">Bairro</label>
                <input type="text" class="form-control" id="bairrof" name="bairro" value="{{ old('bairro', optional($afiliado)->bairro) }}" autocomplete="off" placeholder="Bairro">
                @error('bairro')
                    <label id="bairro-error" class="error mt-2 text-danger" for="bairro">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group">
                <label for="ruaf">Rua</label>
                <input type="text" class="form-control" id="ruaf" name="rua" value="{{ old('rua', optional($afiliado)->rua) }}" autocomplete="off" placeholder="Rua">
                @error('rua')
                    <label id="rua-error" class="error mt-2 text-danger" for="rua">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group">
                <label for="numerof">Número</label>
                <input type="text" class="form-control" id="numerof" name="numero" value="{{ old('numero', optional($afiliado)->numero) }}" autocomplete="off" placeholder="Número">
                @error('rua')
                    <label id="rua-error" class="error mt-2 text-danger" for="rua">{{ $message }}</label>
                @enderror
              </div>
          </div>
        </div>
      </div>
</div>

<div class="card">
    <div class="card-body">
      <div class="row">
          <div class="col-md-12">
            <div class="form-group">
                <h4>Categorias</h4>
            </div>
          </div>
          @foreach($categorias as $categoria)
              <?php
                  $subcategorias = Categoria::where("categoria_pai_id", $categoria->id)->orderBy("nome","asc")->get();
              ?>
              <div class="row" style="width: 100%; margin: 0px 21px 21px 21px; padding-bottom: 10px; border-bottom: 1px solid #ccc;">
                <div class="col-md-12">
                  <h5 style="margin-bottom: 10px;">{{$categoria->nome}}</h5>
                </div>
                @if(count($subcategorias)>0)
                  @foreach($subcategorias as $sub)
                    <?php $checked = ""; if(isset($categorias_afiliado)) foreach($categorias_afiliado as $cat_afil){ ?>
                        <?php 
                          if($sub->id==$cat_afil->categoria_id){
                              $checked = "checked";
                              break;
                          } else {
                            $checked = "";
                          }
                        ?>
                    <?php } ?>
                    <div class="col-md-3">
                      <label>
                          <input type="checkbox" {{$checked}} value="{{$sub->id}}" name="categorias[]">
                          - {{$sub->nome}}
                      </label>
                    </div>
                  @endforeach
                @else
                    <div class="col-md-2">
                        <label>Sem subcategoria</label>
                    </div>
                @endif
              </div>
          @endforeach
      </div>
    </div>
</div>



<div class="card">
    <div class="card-body">
      <div class="row">
          <div class="col-md-12">
            <div class="form-group">
                <h4>Dados do responsávels</h4>
            </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  <h6>Dados pessoais</h6>
              </div>
              <div class="form-group">
                <label for="nome_responsavel">Nome <i style="color: #FF0000; ">*</i></label>
                <input type="text" class="form-control" required id="nome_responsavel" name="nome_responsavel" value="{{ old('nome_responsavel', optional($afiliado)->responsavel ? optional($afiliado)->responsavel->nome : null) }}" autocomplete="off" placeholder="Nome">
                @error('nome_responsavel')
                    <label id="email-error" class="error mt-2 text-danger" for="nome_responsavel">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group">
                <label for="numero_documento">Número documento</label>
                <input type="text" class="form-control" id="numero_documento" name="numero_documento" value="{{ old('numero_documento', optional($afiliado)->responsavel ? optional($afiliado)->responsavel->numero_documento : null) }}" autocomplete="off" placeholder="Número do documento">
                @error('numero_documento')
                    <label id="telefone-error" class="error mt-2 text-danger" for="numero_documento">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group">
                <label for="cpf">CPF <i style="color: #FF0000; ">*</i></label>
                <input type="text" class="form-control" id="cpf" required name="cpf"  data-inputmask-alias="999.999.999-99" value="{{ old('cpf', optional($afiliado)->responsavel ? optional($afiliado)->responsavel->CPF : null) }}" autocomplete="off" placeholder="CPF">
                @error('cpf')
                    <label id="telefone-error" class="error mt-2 text-danger" for="telefone">{{ $message }}</label>
                @enderror
              </div>
          </div>

          <div class="col-md-6">
              <div class="form-group">
                  <h6>Dados profissão e contato</h6>
              </div>
              <div class="form-group">
                <label for="cargo">Cargo</label>
                <input type="text" class="form-control" id="cargo" name="cargo" value="{{ old('cargo', optional($afiliado)->responsavel ? optional($afiliado)->responsavel->cargo : null) }}" autocomplete="off" placeholder="Cargo">
                @error('cargo')
                    <label id="cargo-error" class="error mt-2 text-danger" for="cargo">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group">
                <label for="email_responsavel">E-mail</label>
                <input type="email_responsavel" class="form-control" id="email_responsavel" name="email_responsavel" value="{{ old('email_responsavel', optional($afiliado)->responsavel ? optional($afiliado)->responsavel->email : null) }}" autocomplete="off" placeholder="E-mail">
                @error('email_responsavel')
                    <label id="email_responsavel-error" class="error mt-2 text-danger" for="email_responsavel">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group">
                <label for="telefone_responsavel">Telefone</label>
                <input type="text" class="form-control" data-inputmask-alias="(99) 99999-9999" id="telefone_responsavel" name="telefone_responsavel" value="{{ old('telefone_responsavel', optional($afiliado)->responsavel ? optional($afiliado)->responsavel->telefone : null) }}" autocomplete="off" placeholder="Telefone">
                @error('telefone_responsavel')
                    <label id="telefone_responsavel-error" class="error mt-2 text-danger" for="telefone_responsavel">{{ $message }}</label>
                @enderror
              </div>
          </div>
      </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">

  $(document).ready(function() {

      function limpa_formulario_cep() {
          // Limpa valores do formulário de cep.
          $("#ruaf").val("");
          $("#bairrof").val("");
          $("#cidadef").val("");
          $("#estadof").val("");
      }

      //Quando o campo cep perde o foco.
      $("#cep").blur(function() {
          //Nova variável "cep" somente com dígitos.
          var cep = $(this).val().replace(/\D/g, '');

          //Verifica se campo cep possui valor informado.
          if (cep != "") {

              //Expressão regular para validar o CEP.
              var validacep = /^[0-9]{8}$/;
              //Valida o formato do CEP.
              if(validacep.test(cep)) {
                  
                  //Preenche os campos com "..." enquanto consulta webservice.
                  $("#ruaf").val("...");
                  $("#bairrof").val("...");
                  $("#cidadef").val("...");
                  $("#estadof").val("...");

                  //Consulta o webservice viacep.com.br/
                  $.getJSON("https://viacep.com.br/ws/"+ cep +"/json", function(dados) {
                      if (!("erro" in dados)) {
                        console.log("1");
                          //Atualiza os campos com os valores da consulta.
                          $("#ruaf").val(dados.logradouro);
                          $("#bairrof").val(dados.bairro);
                          $("#cidadef").val(dados.localidade);
                          $("#estadof").val(dados.uf);
                      } //end if.
                      else {
                        console.log("2");
                          //CEP pesquisado não foi encontrado.
                          limpa_formulario_cep();
                          alert("CEP não encontrado.");
                      }
                  });
              } //end if.
              else {
                  //cep é inválido.
                  limpa_formulario_cep();
                  alert("Formato de CEP inválido.");
              }
          } //end if.
          else {
              //cep sem valor, limpa formulário.
              limpa_formulario_cep();
          }
      });
      });

</script>