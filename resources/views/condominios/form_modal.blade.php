<?php

use App\Models\FranqueadoRegiao;
?>
<style>
  .error {
    display: none;
    font-size: 12px !important;
  }
</style>
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-body">
        <div class="form-group">
          <label>Cadastrando condomínio para o síndico</label>
        </div>
        <div class="form-group">
          <h4>Dados do condomínio</h4>
        </div>
        <div class="form-group">
          <label for="nome">Nome</label>
          <input type="text" class="form-control" id="nome_condominio" name="nome_condominio" value="" autocomplete="off" placeholder="Nome do condomínio">
          <label id="nome_condominio-error" class="error mt-2 text-danger" for="nome_condominio">informe o nome do condomínio</label>
        </div>
        <div class="form-group">
          <label for="nome">Cnpj</label>
          <input type="text" class="form-control" id="cnpj" name="cnpj" value="" data-inputmask-alias="99.999.999/9999-99" autocomplete="off" placeholder="Cnpj do condomínio">
          <input type="hidden" id="condominioExistente" name="condominioExistente" autocomplete="off">
          <label id="cnpj-error" class="error mt-2 text-danger" for="cnpj">informe um cnpj válido</label>
          <label id="condominioExistente-error" class="error mt-2 text-danger" for="cnpj">Esse condomínio/CNPJ já está cadastrado</label>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-12">
    <div class="card">
      <div class="card-body">
        <div class="form-group">
          <h4>Endereço do condomínio</h4>
        </div>
        <div class="form-group">
          <label for="cep">Cep</label>
          <input type="text" class="form-control" id="cep" name="cep" data-inputmask-alias="99999-999" value="" autocomplete="off" placeholder="Cep">
          <label id="cep-error" class="error mt-2 text-danger" for="cep">informe um cep válido</label>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="estadof">Estado</label>
              <input type="text" class="form-control" id="estadof" maxlength="2" name="estado" value="" autocomplete="off" placeholder="Estado">
              <label id="estadof-error" class="error mt-2 text-danger" for="estadof">informe um estado válido</label>
            </div>
          </div>
          <div class="col-md-5">
            <div class="form-group">
              <label for="cidadef">Cidade</label>
              <input type="text" class="form-control" id="cidadef" name="cidade" value="" autocomplete="off" placeholder="Cidade">
              <label id="cidadef-error" class="error mt-2 text-danger" for="cidadef">informe uma cidade válida</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="bairrof">Bairro</label>
              <input type="text" class="form-control" id="bairrof" name="bairro" value="" autocomplete="off" placeholder="Bairro">
              <label id="bairrof-error" class="error mt-2 text-danger" for="bairrof">informe um bairro válido</label>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-8">
            <div class="form-group">
              <label for="ruaf">Rua</label>
              <input type="text" class="form-control" id="ruaf" name="rua" value="" autocomplete="off" placeholder="Rua">
              <label id="ruaf-error" class="error mt-2 text-danger" for="ruaf">informe uma rua válida</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="numerof">Número</label>
              <input type="text" class="form-control" id="numerof" name="numero" value="" autocomplete="off" placeholder="Número">
              <label id="numerof-error" class="error mt-2 text-danger" for="numerof">informe o número</label>
            </div>
          </div>
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

    $("#cnpj").blur(async () => {
      const cnpj = $("#cnpj").val()
      const sindico_id = $("#sindico_id").val();
      if (cnpj == '') return false;

      const res = await fetch(`/condominios/sindico`, {
        method: "POST",
        body: JSON.stringify({
          cnpj: cnpj,
          sindico_id: sindico_id
        }),
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": $('input[name="_token"]').val()
        },
      })

      const data = await res.json()

      $("#condominioExistente").val(data.status)
    })

    //Quando o campo cep perde o foco.
    $("#cep").blur(function() {
      //Nova variável "cep" somente com dígitos.
      var cep = $(this).val().replace(/\D/g, '');

      //Verifica se campo cep possui valor informado.
      if (cep != "") {

        //Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;
        //Valida o formato do CEP.
        if (validacep.test(cep)) {

          //Preenche os campos com "..." enquanto consulta webservice.
          $("#ruaf").val("...");
          $("#bairrof").val("...");
          $("#cidadef").val("...");
          $("#estadof").val("...");

          //Consulta o webservice viacep.com.br/
          $.getJSON("https://viacep.com.br/ws/" + cep + "/json", function(dados) {
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
@stack('custom-scripts')
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/inputmask.js') }}"></script>