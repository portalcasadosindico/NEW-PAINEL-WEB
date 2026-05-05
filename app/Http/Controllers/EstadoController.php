<?php

namespace App\Http\Controllers;

use App\Models\Afiliado;
use App\Models\Bairro;
use App\Models\Cidade;
use App\Models\Condominio;
use App\Models\Estado;
use App\Models\Orcamento;
use App\Models\Sindico;
use App\Models\SolicitacaoOrcamentos;
use App\Models\UsuarioApp;
use App\Uteis\Formatacao;
use App\Uteis\StatusOrcamento;
use App\Uteis\Url;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function Ramsey\Uuid\v1;

class EstadoController extends Controller
{

    public static $contCidadesAdd = 0;
    public static $contEstadosAdd = 0;
    public static $contBairrosAdd = 0;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $url = Url::baseURL();
        $estados = Estado::all();
        return view('estados.index', compact('estados', 'url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('estados.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		if(isset($request['cancel'])){
            return redirect()->route('estados.index');
        }
        try {
            $data = $this->getData($request);
            $estado = new Estado();
            $estado->nome = $data['nome'];
            $estado->uf = strtoupper($data['uf']);
            $estado->chave = Formatacao::removerCaracteresEspeciais($data['nome']." ".$data['uf']);
            $estado->save();
            return redirect()->route('estados.index')
                ->with('success_message', 'Estado foi adicionado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $estado = Estado::findOrFail($id);
        return view('estados.show', compact('estado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $estado = Estado::findOrFail($id);
        return view('estados.edit', compact('estado'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		if(isset($request['cancel'])){
            return redirect()->route('estados.index');
        }
        try {
            $data = $this->getData($request);
            $estado = Estado::findOrFail($id);
            $estado->nome = $data['nome'];
            $estado->uf = strtoupper($data['uf']);
            $estado->chave = Formatacao::removerCaracteresEspeciais($data['nome']." ".$data['uf']);
            $estado->update();
            return redirect()->route('estados.index')
                ->with('success_message', 'Estado foi atualizado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $estado = Estado::findOrFail($id);
            $estado->delete();
            return redirect()->route('estados.index')
                ->with('success_message', 'Estado foi deletado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Erro ao deletar estado, tente mais tarde.');
        }
    }

    /**
     * Get the request's data from the request.
     *
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
            'nome' => 'required|string|min:1|max:80',
            'uf' => 'required|string|max:2',
        ];

        $data = $request->validate($rules);

        return $data;
    }

    public function importEstados(){
        $cont=0;
        $dados = json_decode(file_get_contents("../dados_locais/estados.json"));
        foreach($dados->data as $estado){
            $chave= Formatacao::chave($estado->Nome.$estado->Uf);
            $e = Estado::where("chave", $chave)->first();
            if(!$e){
                $e = new Estado();
                $e->nome = $estado->Nome;
                $e->uf = $estado->Uf;
                $e->chave = $chave;
                $e->save();
                $cont++;
            }
        }
        return "Adicionado $cont estados";
    }

    public function importCidades(){
        $cont=0;
        $dados = json_decode(file_get_contents("../dados_locais/municipios.json"));
        foreach($dados->data as $cidade){
            $chave= Formatacao::chave($cidade->Nome);
            $c = Cidade::where("chave", $chave)->first();
            if(!$c){
                $c = new Cidade();
                $c->nome = $cidade->Nome;
                $c->codigo_ibge = $cidade->Codigo;
                $c->chave = $chave;

                $estado = Estado::where("uf", $cidade->Uf)->first();
                $c->estado_id = $estado->id;
                $c->save();
                $cont++;
            }
        }
        return "Adicionado $cont cidades";
    }

    public function importBairros(){
        set_time_limit(0);
        $cont=0;
        $dados = json_decode(file_get_contents("../dados_locais/bairros.json"));
        foreach($dados->data as $bairro){
            $chave = Formatacao::chave($bairro->Nome);
            $b = Bairro::where("chave", $chave)->first();
            if(!$b){
                $b = new Bairro();
                
                $nomes = explode(" - ", $bairro->Nome);
                if(count($nomes)==2){
                    $bairro_nome = $nomes[0];
                    $nome_cidade = $nomes[1];
                } else {
                    $nome_cidade = $nomes[count($nomes)-1];
                    array_pop(($nomes));
                    $bairro_nome = implode("-", $nomes);
                }
                $cidade = Cidade::where("chave", Formatacao::chave($nome_cidade))->first();
                $b->nome = trim($bairro_nome);
                $b->chave = $chave;
                $b->cidade_id = $cidade->id;
                
                $b->save();
                $cont++;
            }
        }
        return "Adicionado $cont bairros";
    }

    public function importCeps2016(){

        $INDEX_CEP = 0;
        $INDEX_UF = 1;
        $INDEX_CIDADE = 2;
        $INDEX_BAIRRO = 3;
        $INDEX_RUA = 4;

        set_time_limit(0);
        $cont=0;
        $dados = file_get_contents("../dados_locais/ceps2016.txt");
        $dados = explode("\n", $dados);
        
        EstadoController::$contBairrosAdd = 0;
        EstadoController::$contCidadesAdd = 0;
        EstadoController::$contBairrosAdd = 0;


        foreach($dados as $linha1){
            $dados = explode("\t", $linha1);
            
            $local['cep'] = isset($dados[$INDEX_CEP]) ? $dados[$INDEX_CEP] : null;
            $local['nome_bairro'] = isset($dados[$INDEX_BAIRRO]) ? $dados[$INDEX_BAIRRO] : null;
            $local['nome_cidade'] = isset($dados[$INDEX_CIDADE]) ? $dados[$INDEX_CIDADE] : null;
            $local['uf'] = isset($dados[$INDEX_UF]) ? $dados[$INDEX_UF] : null;
            $local['nome_rua'] = isset($dados[$INDEX_RUA]) ? $dados[$INDEX_RUA] : null;
            $estado = $this->getEstado($local['uf']);
            $cidade = $this->getCidade($local['nome_cidade'], $estado);
            $bairro = $this->getBairro($local['nome_bairro'], $cidade);
        }
        
        return  EstadoController::$contBairrosAdd . " bairros<br>".
                EstadoController::$contCidadesAdd . " cidades<br>".
                EstadoController::$contEstadosAdd . " estados vasculhados<br>";
    }

    public function importCeps2018(){

        $INDEX_CEP = 0;
        $INDEX_CIDADE_UF = 1;
        $INDEX_BAIRRO = 2;
        $INDEX_RUA = 3;

        set_time_limit(0);
        $cont=0;
        $dados = file_get_contents("../dados_locais/ceps2018.txt");
        $dados = explode("\n", $dados);
        
        EstadoController::$contBairrosAdd = 0;
        EstadoController::$contCidadesAdd = 0;
        EstadoController::$contBairrosAdd = 0;


        foreach($dados as $linha1){
            $dados = explode("\t", $linha1);
            $local['cep'] = isset($dados[$INDEX_CEP]) ? $dados[$INDEX_CEP] : null;
            $local['nome_bairro'] = isset($dados[$INDEX_BAIRRO]) ? $dados[$INDEX_BAIRRO] : null;

            $cidade_uf = explode("/", $dados[$INDEX_CIDADE_UF]);
            if(count($cidade_uf) ==2){
                $local['nome_cidade'] = $cidade_uf[0];
                $local['uf'] = $cidade_uf[1];;
            } else {
                $local['nome_cidade'] = "Sem cidade";
                $local['uf'] = "Sem estado";
            }

           
            $local['nome_rua'] = isset($dados[$INDEX_RUA]) ? $dados[$INDEX_RUA] : null;

            $estado = $this->getEstado($local['uf']);
            $cidade = $this->getCidade($local['nome_cidade'], $estado);
            $bairro = $this->getBairro($local['nome_bairro'], $cidade);
        }
        
        return  EstadoController::$contBairrosAdd . " bairros<br>".
                EstadoController::$contCidadesAdd . " cidades<br>".
                EstadoController::$contEstadosAdd . " estados vasculhados<br>";
    }


    public function getEstado($uf){
        EstadoController::$contEstadosAdd++;
        return Estado::where("uf", $uf)->first();
    }

    public function getCidade($nome_cidade, $estado){
        $cidade = Cidade::where("chave", Formatacao::chave($nome_cidade.$estado->chave))->first();
        if(!$cidade){
            $cidade = new Cidade();
            $cidade->nome = $nome_cidade;
            $cidade->chave = Formatacao::chave($nome_cidade);
            $cidade->estado_id = isset($estado->id) ? $estado->id : 28;
            $cidade->save();
            EstadoController::$contCidadesAdd++;
        }
        return $cidade;
    }

    public function getBairro($nome_bairro, $cidade){
        $chave = Formatacao::chave($nome_bairro.$cidade->chave);
        $b = Bairro::where("chave", $chave)->first();
        if(!$b){
            $b = new Bairro();
            $b->nome = $nome_bairro;
            $b->chave = $chave;
            $b->cidade_id = $cidade->id;
            $b->save();
            EstadoController::$contBairrosAdd;
        }
        return $b;
    }

    public function transporSolicitacoes(){
        set_time_limit(0);
        $solicitacoes = SolicitacaoOrcamentos::all();
        
        $cont=0;
        foreach($solicitacoes as $solicitacao){
            $email_sindico = $solicitacao->emailSolicitante;
            $usuarioApp = UsuarioApp::where("email", $email_sindico)->where("tipo", "sindico")->first();
            if(!$usuarioApp){
                
                $usuarioApp = new UsuarioApp();
                $usuarioApp->email = $email_sindico;
                $usuarioApp->tipo = "sindico";
                $usuarioApp->senha = Hash::make("123456");
                $usuarioApp->save();

                $sindico = new Sindico();
                $sindico->nome = $solicitacao->nomeSolicitante;
                $sindico->telefone = $solicitacao->telefoneSolicitante;
                $sindico->usuario_app_id = $usuarioApp->id;
                $sindico->save();
            } else {
                $sindico = Sindico::where("usuario_app_id", $usuarioApp->id)->first();
            }
            
            $condominio = Condominio::where("chave", Formatacao::chave($solicitacao->nomeCondominio))->first();
            if(!$condominio){
                $condominio = new Condominio();
                $condominio->nome = $solicitacao->nomeCondominio;
                $condominio->chave = Formatacao::chave($solicitacao->nomeCondominio);
                $condominio->cep = $solicitacao->cep;
                $condominio->endereco = $solicitacao->endereco;
                $condominio->numero = $solicitacao->numero;
                $condominio->bairro = $solicitacao->bairro;
                $bId = Bairro::where("chave", Formatacao::chave($solicitacao->bairro))->first();
                $condominio->bairro_id = isset($bId->id) ? $bId->id : null;
                $condominio->cidade = $solicitacao->idCidade;
                $condominio->estado = $solicitacao->idEstado;
                $condominio->complemento = $solicitacao->complemento;
                $condominio->sindico_id = $sindico->id;
                $condominio->save();
            }
            


            $email_afiliado = $solicitacao->emailEmpresa;
            $razao_social_afiliado = $solicitacao->nomeEmpresa;
            $responsavel_afiliado = $solicitacao->nomeResponsavelEmpresa;

            $usuarioApp = UsuarioApp::where("email", $email_afiliado)->where("tipo", "afiliado")->first();
            if(!$usuarioApp){
                $afiliado = Afiliado::where("email", $email_afiliado)->first();
                if($afiliado){
                    $usuarioApp = new UsuarioApp();
                    $usuarioApp->email = $email_afiliado;
                    $usuarioApp->tipo = "afiliado";
                    $usuarioApp->senha = Hash::make("123456");
                    $usuarioApp->save();
                    $afiliado->usuario_app_id = $usuarioApp->id;
                    $afiliado->update();
                } else {
                    $usuarioApp = new UsuarioApp();
                    $usuarioApp->email = $email_afiliado;
                    $usuarioApp->tipo = "afiliado";
                    $usuarioApp->senha = Hash::make("123456");
                    $usuarioApp->save();
                    
                    $afiliado = new Afiliado();
                    $afiliado->razao_social = $razao_social_afiliado;
                    $afiliado->email = $email_afiliado;
                    $afiliado->usuario_app_id = $usuarioApp->id;
                    $afiliado->save();
                    //dd("3");
                }
                
            } else {
                $afiliado = Afiliado::where("usuario_app_id", $usuarioApp->id)->first();
                if(!$afiliado){
                    $afiliado = new Afiliado();
                    $afiliado->razao_social = $razao_social_afiliado;
                    $afiliado->email = $email_afiliado;
                    $afiliado->usuario_app_id = $usuarioApp->id;
                    $afiliado->save();
                }
                //dd("4");
            }
            
            $orcamento = new Orcamento();
            $orcamento->id = $solicitacao->id;
            $orcamento->condominio_id = $condominio->id;
            $orcamento->categoria_id = $solicitacao->idCategoria;
            $orcamento->afiliado_id = $afiliado->id;
            $orcamento->nome = "";
            $orcamento->descricao = $solicitacao->detalhesSolicitacao;
            $orcamento->status = StatusOrcamento::$FINALIZADO;
            $orcamento->status_sindico = StatusOrcamento::$FINALIZADO;
            $orcamento->status_afiliado = StatusOrcamento::$FINALIZADO;
            $orcamento->data_cadastro = $solicitacao->dataCriacao;
            $orcamento->formato_contrato_atual = 2;
            $orcamento->save();
            $cont++;
        }

        return $cont;
    }
}
