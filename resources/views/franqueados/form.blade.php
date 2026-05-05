<?php
  use App\Models\FranqueadoRegiao;
?>
<div class="row">
    <div class="col-sm-6">
        <div class="card">
        <div class="card-body">
          <div class="form-group">
            <h4>Dados da franquia</h4>
          </div>
          <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', optional($franqueado)->nome) }}" autocomplete="off" placeholder="Nome">
            @error('nome')
                <label id="nome-error" class="error mt-2 text-danger" for="nome">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="razao_social">Razão Social</label>
            <input type="text" required class="form-control" id="razao_social" name="razao_social" value="{{ old('razao_social', optional($franqueado)->razao_social) }}" autocomplete="off" placeholder="Razão Social">
            @error('razao_social')
                <label id="nome-error" class="error mt-2 text-danger" for="razao_social">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="email">E-mail login</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', optional($franqueado)->email) }}" autocomplete="off" placeholder="E-mail login">
            @error('email')
                <label id="email-error" class="error mt-2 text-danger" for="email">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="email_autentique">E-mail Assinatura Autentique</label>
            <input type="email" class="form-control" id="email_autentique" name="email_autentique" value="{{ old('email_autentique', optional($franqueado)->email_autentique) }}" autocomplete="off" placeholder="E-mail autentique">
            @error('email')
                <label id="email_autentique-error" class="error mt-2 text-danger" for="email_autentique">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" class="form-control" id="senha" name="senha" value="" autocomplete="off" placeholder="Senha">
            @error('senha')
                <label id="senha-error" class="error mt-2 text-danger" for="senha">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="cnpj">CNPJ</label>
            <input type="text" class="form-control"  data-inputmask-alias="99.999.999/9999-99" id="cnpj" name="cnpj" value="{{ old('cnpj', optional($franqueado)->cnpj) }}" autocomplete="off" placeholder="Cnpj">
            @error('cnpj')
                <label id="cnpj-error" class="error mt-2 text-danger" for="cnpj">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="inscricao_estadual">Inscrição estadual</label>
            <input type="text" class="form-control" id="inscricao_estadual" name="inscricao_estadual" value="{{ old('inscricao_estadual', optional($franqueado)->inscricao_estadual) }}" autocomplete="off" placeholder="Inscrição estadual">
            @error('inscricao_estadual')
                <label id="inscricao_estadual-error" class="error mt-2 text-danger" for="inscricao_estadual">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="inscricao_municipal">Inscrição municipal</label>
            <input type="text" class="form-control" id="inscricao_municipal" name="inscricao_municipal" value="{{ old('inscricao_municipal', optional($franqueado)->inscricao_municipal) }}" autocomplete="off" placeholder="Inscrição municipal">
            @error('inscricao_municipal')
                <label id="inscricao_municipal-error" class="error mt-2 text-danger" for="inscricao_municipal">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="cartao_cnpj">Cartão CNPJ</label>
            <div class="custom-file mb-3 form-group">
              <input type="file" class="custom-file-input" id="cartao_cnpj" name="cartao_cnpj" value="{{ old('cartao_cnpj', optional($franqueado)->cartao_cnpj) }}" autocomplete="off" placeholder="Cartão cnpj">
              <label class="custom-file-label" for="cartao_cnpj">Selecione o cartão CNPJ</label>
            </div>
            @error('cartao_cnpj')
                <label id="cartao_cnpj-error" class="error mt-2 text-danger" for="cartao_cnpj">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="contrato_social">Contrato Social</label>
            <div class="custom-file mb-3 form-group">
              <input type="file" class="custom-file-input" id="contrato_social" name="contrato_social" value="{{ old('contrato_social', optional($franqueado)->contrato_social) }}" autocomplete="off" placeholder="Cartão cnpj">
              <label class="custom-file-label" for="contrato_social">Selecione o contrato social</label>
            </div>
            @error('contrato_social')
                <label id="contrato_social-error" class="error mt-2 text-danger" for="contrato_social">{{ $message }}</label>
            @enderror
          </div>
        </div>
        </div>
        <div class="card">
          <div class="card-body">
              <div class="form-group">
                <h4>Token ASAAS e AUTENTIQUE</h4>
              </div>
              <div class="form-group">
                <label for="token_asaas_producao">Token ASAAS</label>
                <i class="fa fa-question" data-toggle="popover" title="Saiba para que serve" data-content="Esse é o código para que os afiliados possam inscever-se em suas regiões, integrando diretamete no seu ASAAS."></i>
                <input type="text" class="form-control" id="token_asaas_producao" name="token_asaas_producao" value="{{ old('token_asaas_producao', optional($franqueado)->token_asaas_producao) }}" autocomplete="off" placeholder="Token asaas">
                <a href="javascript:void(0)" data-toggle="modal" data-target="#tutorial_asaas">Saiba como obter</a>
                <!-- Modal -->
                <div class="modal fade" id="tutorial_asaas" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Obtendo Token da API do Asaas</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Acesse sua conta no <a href="https://www.asaas.com/" target="_blank">ASAAS</a>. 
                                <br>No menu Principal acesse, <b>Minha Conta</b> e em seguida vá até a aba <b>Integrações</b> e clique no botão <b>Gerar API Key</b>
                                <br>
                                <img style="width: 100%; border: 1px solid #cdcdcd; padding: 3px; background-color: #fff; border-radius: 3px;" src="{{asset('assets/images/token-asaas.png')}}" >
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                @error('token_asaas_producao')
                    <label id="token_asaas_producao-error" class="error mt-2 text-danger" for="token_asaas_producao">{{ $message }}</label>
                @enderror
              </div>

              <div class="form-group">
                <label for="token_asaas_debug">Token ASAAS para Testes</label>
                <button type="button" class="btn-question" data-trigger="focus" data-toggle="popover" title="Saiba para que serve" data-content="Esse é o código para que os afiliados possam inscever-se em suas regiões, integrando diretamete no seu ASAAS, de maneira que as cobranças serão apenas fictícias."><i class="fa fa-question"></i></button>
                <input type="text" class="form-control" id="token_asaas_debug" name="token_asaas_debug" value="{{ old('token_asaas_debug', optional($franqueado)->token_asaas_debug) }}" autocomplete="off" placeholder="Token asaas de testes">
                <a href="javascript:void(0)" data-toggle="modal" data-target="#tutorial_asaas_debug">Saiba como obter</a>
                <!-- Modal -->
                <div class="modal fade" id="tutorial_asaas_debug" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Obtendo Token de testes da API do Asaas</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Crie uma conta em <a href="https://sandbox.asaas.com/" target="_blank">SandBox ASAAS</a>. 
                                <br>No menu Principal acesse, <b>Minha Conta</b> e em seguida vá até a aba <b>Integrações</b> e clique no botão <b>Gerar API Key</b>
                                <br>
                                <img style="width: 100%; border: 1px solid #cdcdcd; padding: 3px; background-color: #fff; border-radius: 3px;" src="{{asset('assets/images/token-asaas.png')}}" >
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
                @error('token_asaas_debug')
                    <label id="token_asaas_debug-error" class="error mt-2 text-danger" for="token_asaas_debug">{{ $message }}</label>
                @enderror
              </div>

              <div class="form-group">
                <label for="token_autentique">Token AUTENTIQUE</label>
                <i class="fa fa-question" data-toggle="popover" title="Saiba para que serve" data-content="Esse é o código para que o sistema possa coletar as assinaturas entre afiliado e franqueado e assinaturas dos contratos de serviços, utilizando o seu perfil da plataforma Autentique."></i>
                <input type="text" class="form-control" id="token_autentique" name="token_autentique" value="{{ old('token_autentique', optional($franqueado)->token_autentique) }}" autocomplete="off" placeholder="Token autentique">
                <a href="javascript:void(0)" data-toggle="modal" data-target="#tutorial_autentique">Saiba como obter</a>
                <!-- Modal -->
                <div class="modal fade" id="tutorial_autentique" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Obtendo Token da API do Autentique</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Acesse sua conta no <a href="https://www.autentique.com.br/" target="_blank">AUTENTIQUE</a>. 
                                <br>No menu Principal acesse, <b>Configurações</b> e em seguida vá até o menu <b>Acesso a API</b> e clique no botão <b>Gerar token</b>
                                <br>
                                <img style="width: 100%; border: 1px solid #cdcdcd; padding: 3px; background-color: #fff; border-radius: 3px;" src="{{asset('assets/images/token-autentique.png')}}" >
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
                @error('token_autentique')
                    <label id="token_autentique-error" class="error mt-2 text-danger" for="token_autentique">{{ $message }}</label>
                @enderror
              </div>


          </div>
        </div>
        
        <div class="card">
          <div class="card-body">
              <div class="form-group">
                <h4>Regiões</h4>
              </div>
              <div class="form-group">
                <table>
                    <tbody>
                        @foreach ($regioes as $key => $regiao)
                          <?php
                              $regiaoFranqueado = FranqueadoRegiao::where("regiao_id", $regiao->id)->where("status", "ativo")->orderBy("id", "desc")->first();
                              if($regiaoFranqueado==null){
                                  $title = "(Selecionar)";
                                  $state = "";
                              } else {
                                  if(isset($franqueado)){
                                    if($franqueado->id == $regiaoFranqueado->franqueado_id){
                                      $state = "checked";
                                      $title = "(Franqueado {$regiaoFranqueado->franqueado->nome})"; 
                                    } else {
                                      $state = "checked disabled";
                                      $title = "(Franqueado {$regiaoFranqueado->franqueado->nome})";
                                    }
                                  } else {
                                    $state = "checked disabled";
                                    $title = "(Franqueado {$regiaoFranqueado->franqueado->nome})";
                                  }
                              }
                          ?>
                          <tr>
                              <td>
                                  <div class="">
                                      <label <?php if($state && $state!="checked") echo "style='text-decoration: line-through'"; ?> class="" title="{{$title}}">
                                          <input {{$state}} type="checkbox" class="" name="regiao[]" value="{{$regiao->id}}">
                                          {{$regiao->nome}} {{$title}}
                                      </label>
                                  </div>
                              </td>
                          </tr>
                        @endforeach
                    </tbody>
                </table>                
              </div>
          </div>
        </div>

    </div>
    <div class="col-sm-6">
    <div class="card">
        <div class="card-body">
          <div class="form-group">
            <h4>Dados do Responsável</h4>
          </div>
          <div class="form-group">
            <label for="nome_responsavel">Nome do responsável</label>
            <input type="text" class="form-control" id="nome_responsavel" name="nome_responsavel" value="{{ old('nome_responsavel', optional($franqueado)->nome_responsavel) }}" autocomplete="off" placeholder="Nome do responsavel">
            @error('nome_responsavel')
                <label id="nome_responsavel-error" class="error mt-2 text-danger" for="nome_responsavel">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="profissao_responsavel">Profissão responsável</label>
            <input type="text" class="form-control" id="profissao_responsavel" name="profissao_responsavel" value="{{ old('profissao_responsavel', optional($franqueado)->profissao_responsavel) }}" autocomplete="off" placeholder="Profissão responsavel">
            @error('profissao_responsavel')
                <label id="profissao_responsavel-error" class="error mt-2 text-danger" for="profissao_responsavel">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="cpf_responsavel">Cpf responsável</label>
            <input type="text" class="form-control"  data-inputmask-alias="999.999.999-99" id="cpf_responsavel" name="cpf_responsavel" value="{{ old('cpf_responsavel', optional($franqueado)->cpf_responsavel) }}" autocomplete="off" placeholder="Cpf responsavel">
            @error('cpf_responsavel')
                <label id="cpf_responsavel-error" class="error mt-2 text-danger" for="cpf_responsavel">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="rg_responsavel">Rg responsável</label>
            <input type="text" class="form-control" id="rg_responsavel" name="rg_responsavel" value="{{ old('rg_responsavel', optional($franqueado)->rg_responsavel) }}" autocomplete="off" placeholder="Rg responsavel">
            @error('rg_responsavel')
                <label id="rg_responsavel-error" class="error mt-2 text-danger" for="rg_responsavel">{{ $message }}</label>
            @enderror
          </div>
          <div class="form-group">
            <label for="telefone_responsavel">Telefone responsável</label>
            <input type="text" class="form-control"  data-inputmask-alias="(99) 99999-9999" id="telefone_responsavel" name="telefone_responsavel" value="{{ old('telefone_responsavel', optional($franqueado)->telefone_responsavel) }}" autocomplete="off" placeholder="Telefone responsavel">
            @error('telefone_responsavel')
                <label id="telefone_responsavel-error" class="error mt-2 text-danger" for="telefone_responsavel">{{ $message }}</label>
            @enderror
          </div>
        </div>
    </div>
          <div class="card">
            <div class="card-body">
              <div class="form-group">
                <h4>Endereço da franquia</h4>
              </div>
              <div class="form-group">
                <label for="cep">CEP</label>
                <input type="text" class="form-control"  data-inputmask-alias="99999-999" id="cep" name="cep" value="{{ old('cep', optional($franqueado)->cep) }}" autocomplete="off" placeholder="Cep">
                @error('cep')
                    <label id="cep-error" class="error mt-2 text-danger" for="cep">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group">
                <label for="estadof">Estado</label>
                <input type="text" class="form-control" id="estadof" name="estado" value="{{ old('estado', optional($franqueado)->estado) }}" autocomplete="off" placeholder="Estado">
                @error('estado')
                    <label id="estado-error" class="error mt-2 text-danger" for="estado">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group">
                <label for="cidadef">Cidade</label>
                <input type="text" class="form-control" id="cidadef" name="cidade" value="{{ old('cidade', optional($franqueado)->cidade) }}" autocomplete="off" placeholder="Cidade">
                @error('cidade')
                    <label id="cidade-error" class="error mt-2 text-danger" for="cidade">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group">
                <label for="bairrof">Bairro</label>
                <input type="text" class="form-control" id="bairrof" name="bairro" value="{{ old('bairro', optional($franqueado)->bairro) }}" autocomplete="off" placeholder="Bairro">
                @error('bairro')
                    <label id="bairro-error" class="error mt-2 text-danger" for="bairro">{{ $message }}</label>
                @enderror
              </div>
              <div class="form-group">
                <label for="ruaf">Rua</label>
                <input type="text" class="form-control" id="ruaf" name="rua" value="{{ old('rua', optional($franqueado)->rua) }}" autocomplete="off" placeholder="Rua">
                @error('rua')
                    <label id="rua-error" class="error mt-2 text-danger" for="rua">{{ $message }}</label>
                @enderror
              </div>
            </div>
          </div>
    </div>
  </div>

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