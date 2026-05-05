<?php

namespace App\Http\Controllers;

use App\Models\Afiliado;
use App\Models\Bairro;
use App\Models\Cidade;
use App\Models\Condominio;
use App\Models\Estado;
use App\Models\FranqueadoRegiao;
use App\Models\RegiaoFaixaCep;
use App\Uteis\Formatacao;
use App\Uteis\Validacao;
use Exception;
use Illuminate\Http\Request;

class CondominioController extends Controller
{
  protected $url;
  public function __construct(Request $request)
  {
  }

  public function verificarUrl()
  {
    if (isset($this->user_franqueado->id) && $this->user_franqueado->id > 0) {
      $this->url = 'admin_franqueado';
    } else {
      $this->url = 'admin';
    }
    return $this->url;
  }

  public function fetchCondominiosBySindico($sindico_id)
  {
    if ($this->verificarUrl() == 'admin') {
      $condominios = Condominio::where("sindico_id", $sindico_id)->get();
    } else {
      $condominiosLista = Condominio::where("sindico_id", $sindico_id)->get();
      $regioesFranqueado = FranqueadoRegiao::where("franqueado_id", $this->user_franqueado->id)->where("status", "ativo")->get();
      $condominios = [];
      foreach ($regioesFranqueado as $regiaoFranqueado) {
        foreach ($condominiosLista as $condlinha) {
          $bairro = Bairro::where("id", $condlinha->bairro_id)->first();
          if ($bairro && $bairro->regiao_id == $regiaoFranqueado->regiao_id) {
            $condominios[] = $condlinha;
          }
        }
      }
    }


    foreach ($condominios as $i => $c) {
      if ($c->bairro_id > 0) {
        $bairro = Bairro::where("id", $c->bairro_id)->first();
        $condominios[$i]->bairro_name = $bairro->nome;
        $condominios[$i]->endereco = $c->cep . ". " . $c->endereco . ". " . $bairro->nome . ", " . $bairro->cidade->nome . " - " . $bairro->cidade->estado->uf;

        if ($bairro->regiao != null) {
          $regiaoFranquiado = FranqueadoRegiao::where("regiao_id", $bairro->regiao->id)->orderBy("id", "desc")->first();
          $condominios[$i]->franqueado_name = $regiaoFranquiado->franqueado->nome;
          $condominios[$i]->regiao_name = $bairro->regiao->nome;
          $condominios[$i]->regiao_status = 1;
        } else {

          $faixaCep = RegiaoFaixaCep::where("cidade_id", $bairro->cidade->id)->orderBy("id", "desc")->first();

          if ($faixaCep) {
            $regiaoFranquiado = FranqueadoRegiao::where("regiao_id", $faixaCep->regiao_id)->orderBy("id", "desc")->first();
            $condominios[$i]->franqueado_name = $regiaoFranquiado->franqueado->nome;
            $condominios[$i]->regiao_name = $regiaoFranquiado->regiao->nome;
            $condominios[$i]->regiao_status = 1;
          } else {
            $condominios[$i]->franqueado_name = "<span class='badge badge-danger'>Bairro " . $bairro->nome . " sem franquia</span>";
            $condominios[$i]->regiao_name = "<span class='badge badge-danger'>Bairro " . $bairro->nome . " sem franquia</span>";
            $condominios[$i]->regiao_status = 1;
          }
        }
      } else {
        $condominios[$i]->bairro_name = $c->bairro;
        $condominios[$i]->endereco = $c->endereco;
      }
    }

    return json_encode(array(
      "dados" => $condominios,
      "sindico_id" => $sindico_id,
    ));
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($sindico_id = null)
  {
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  public function storeModal(Request $request)
  {
    try {
      $validacao = new Validacao();
      $validacao->validarCnpjGeral("cnpj", $request['cnpj'], "CNPJ");
      if ($validacao->getErros()) {
        return json_encode(["errors" => $validacao->getErros()]);
      }


      $condominio = new Condominio();
      $condominio->nome = $request['nome_condominio'];
      $condominio->cep = $request['cep'];
      $condominio->bairro = $request['bairro'];
      $condominio->endereco = $request['rua'];
      $condominio->numero = $request['numero'];
      $condominio->complemento = $request['nome_condominio'];
      $condominio->estado = $request['estado'];
      $condominio->cidade = $request['cidade'];
      $condominio->sindico_id = $request['sindico_id'];
      $condominio->cnpj = $request['cnpj'];




      $bairros = Bairro::where("chave", "LIKE", "%" . Formatacao::chave($condominio->bairro) . "%")->orderBy("id", "asc")->get();

      $encontrouBairro = false;
      foreach ($bairros as $bairroLinha) {
        $cid = Cidade::where("id", $bairroLinha->cidade_id)->first();
        $estado = Estado::where("id", $cid->estado_id)->first();
        if ((strtoupper($estado->uf) == strtoupper($condominio->estado) || Formatacao::chave($estado->nome) == Formatacao::chave($condominio->estado)) && Formatacao::chave($cid->nome) == Formatacao::chave($condominio->cidade)) {
          $condominio->estado = $estado->uf;
          $condominio->cidade = $cid->nome;
          $condominio->bairro = $bairroLinha->nome;
          $encontrouBairro = true;
          break;
        }
      }

      if ($encontrouBairro == false) {
        $cidadeReq = Cidade::where("chave", "LIKE", "%" . Formatacao::chave($condominio->cidade) . "%")->where("uf", "LIKE", $condominio->estado)->first();

        if (!$cidadeReq) {
          return $this->errorResponse([array("error_code" => "invalid-cidade", "error_message" => "Não encontramos sua cidade. Fale com a administração.")], 403);
          $est = Estado::where("uf", "like", $condominio->estado, "or")->where("nome", "like", $condominio->estado, "or")->first();
          if (!$est) {
            return $this->errorResponse([array("error_code" => "invalid-uf", "error_message" => "Estado não encontrado")], 403);
          }
          $cidadeReq = new Cidade();
          $cidadeReq->nome = $condominio->cidade;
          $cidadeReq->uf = $condominio->estado;
          $cidadeReq->estado_id = $est->id;
          $cidadeReq->save();
        }

        $bairro = new Bairro();
        $bairro->nome = $condominio->bairro;
        $bairro->cidade_id = $cidadeReq->id;
        $bairro->chave = Formatacao::chave($bairro->nome);
        $bairro->save();
        $condominio->bairro_id = $bairro->id;
      } else {
        $condominio->bairro_id = $bairroLinha->id;
      }

      $condominio->save();

      $bairro = Bairro::where("id", $condominio->bairro_id)->first();
      $condominio->bairro_name = $bairro->nome;
      $condominio->endereco = $condominio->endereco . ". " . $bairro->nome . ", " . $bairro->cidade->nome . " - " . $bairro->cidade->estado->uf;

      if ($bairro->regiao != null) {
        $regiaoFranquiado = FranqueadoRegiao::where("regiao_id", $bairro->regiao->id)->orderBy("id", "desc")->first();
        $condominio->franqueado_name = $regiaoFranquiado->franqueado->nome;
        $condominio->regiao_name = $bairro->regiao->nome;
        $condominio->regiao_status = 1;
      } else {
        $condominio->franqueado_name = "<span class='badge badge-danger'>Bairro " . $bairro->nome . " sem franquia</span>";
        $condominio->regiao_name = "<span class='badge badge-danger'>Bairro " . $bairro->nome . " sem franquia</span>";
        $condominio->regiao_status = 0;
      }

      return json_encode($condominio);
    } catch (Exception $e) {
      return $e;
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Condominio  $condominio
   * @return \Illuminate\Http\Response
   */
  public function show(Condominio $condominio)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Condominio  $condominio
   * @return \Illuminate\Http\Response
   */
  public function edit(Condominio $condominio)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Condominio  $condominio
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Condominio $condominio)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Condominio  $condominio
   * @return \Illuminate\Http\Response
   */
  public function condominioDestroy(Request $request)
  {
    $condominio = Condominio::where("id", $request->condominio_id)->first();
    if ($condominio) {
      $condominio->status = $request->payload;
      $condominio->update();
      return response()->json(["success" => true]);
    } else {
      return response()->json(["success" => false]);
    }
  }

  /**
   * Validate condominio by sindico and CNPJ.
   *
   * @return \Illuminate\Http\Response
   */
  public function validateBySindico(Request $request)
  {
    $condominio = Condominio::where("cnpj", $request->cnpj)->where("sindico_id", $request->sindico_id)->first();
    if ($condominio) {
      return  response()->json(["status" => true]);
    } else {
      return response()->json(["status" => false]);
    }
  }
}
