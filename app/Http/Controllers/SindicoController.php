<?php

namespace App\Http\Controllers;

use App\Models\Bairro;
use App\Models\Condominio;
use App\Models\Franqueado;
use App\Models\FranqueadoRegiao;
use App\Models\Notificacao;
use App\Models\Orcamento;
use App\Models\RegiaoFaixaCep;
use App\Models\Sindico;
use App\Models\UsuarioApp;
use App\Uteis\Formatacao;
use App\Uteis\Url;
use App\Uteis\Validacao;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SindicoController extends Controller
{

    protected $url;
    public function __construct(Request $request)
    {
        if ($request->is('admin_franqueado/*')) {
            $this->url = 'admin_franqueado';
        } else {
            $this->url = 'admin';
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($franqueado_id = null)
    {
        $franqueados = Franqueado::all(["id", "nome"]);
        $sindicos = [];
        if ($this->url == 'admin_franqueado') {
            $sindicosAll = Sindico::orWhere("franqueado_id", null)->orWhere("franqueado_id", $this->user_franqueado->id)->get();
            foreach ($sindicosAll as $sindico) {
                $condominios = Condominio::where("sindico_id", $sindico->id)->get();
                $addSindico = false;
                foreach ($condominios  as $c) {
                    $bairro = $c->bairro()->first();
                    $regiao_id = -1;
                    if ($bairro && $bairro->regiao_id) {
                        $regiao_id = $bairro->regiao_id;
                    } elseif ($bairro) {
                        $faixaCep = RegiaoFaixaCep::where("cidade_id", $bairro->cidade_id)->first();
                        if ($faixaCep)
                            $regiao_id = $faixaCep->regiao_id;
                    }

                    $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $regiao_id)->where("franqueado_id", $this->user_franqueado->id)->where("status", "ativo")->orderBy("id", "desc")->first();
                    if ($franqueadoRegiao) {
                        $addSindico = true;
                    }
                }
                if ($addSindico || $sindico->franqueado_id == $this->user_franqueado->id) {
                    $sindicos[] = $sindico;
                }
            }
        } else {
            if ($franqueado_id != null && $franqueado_id > 0) {
                $sindicos = Sindico::where("franqueado_id", $franqueado_id)->get();
            } elseif ($franqueado_id == -1) {
                $sindicos = Sindico::where("franqueado_id", null)->get();
            } else {
                $sindicos = Sindico::orderBy("franqueado_id", "asc")->get();
            }
        }
        $url = Url::baseURL();
        return view($this->url . '.sindicos.index', compact('sindicos', 'franqueados', 'url', 'franqueado_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usuariosApp = UsuarioApp::pluck('email', 'id')->all();
        $franqueados = Franqueado::pluck('nome', 'id')->all();
        return view($this->url . '.sindicos.create', compact('usuariosApp', 'franqueados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($request['cancel'])) {
            return redirect()->route($this->url . '.sindicos.index');
        }
        try {
            $this->getData($request);

            #CADASTRO USUARIO APP
            $usuario_app = new UsuarioApp();
            $usuario_app->email = isset($request['email']) ? (string) $request['email'] : "";
            $usuario_app->senha = isset($request['senha']) ? (string) $request['senha'] : "";
            $usuario_app->tipo = "sindico";
            $usuario_app->senha = Hash::make($request['senha']);
            $usuario_app->save();
            #FIM CADASTRO USUARIO

            $sindico = new Sindico();
            $sindico->nome = $request['nome'];
            $sindico->CPF = $request['cpf'];
            $sindico->numero_documento = $request['numero_documento'];
            $sindico->telefone = $request['telefone'];
            if ($request['data_inicio_mandato'])
                $sindico->data_inicio_mandato = Formatacao::data($request['data_inicio_mandato']);

            if ($request['data_fim_mandato'])
                $sindico->data_fim_mandato = Formatacao::data($request['data_fim_mandato']);

            $sindico->usuario_app_id = $usuario_app['id'];
            if (isset($this->user_franqueado->id)) {
                $sindico->franqueado_id = $this->user_franqueado->id;
                $sindico->forma_cadastro = "Franquia " . $this->user_franqueado->nome;
            } else {
                $sindico->forma_cadastro = "Super Admin";
            }
            $sindico->save();

            Notificacao::painelNotificarUsuarioBoasVindas($sindico->nome, "sindico", $usuario_app);

            return redirect()->route($this->url . '.sindicos.show', $sindico->id)
                ->with('success_message', 'Sindico foi atualizado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    public function store_modal(Request $request)
    {
        try {

            $validacao = new Validacao();
            $validacao->validarCpfGeral("cpf", $request['cpf'], "CPF");
            if ($validacao->getErros()) {
                return json_encode(["errors" => $validacao->getErros()]);
            }

            $validacao->email("email", $request['email'], "E-mail");
            if ($validacao->getErros()) {
                return json_encode(["errors" => $validacao->getErros()]);
            }

            $usuarioApp = UsuarioApp::where("email", $request['email'])->where("tipo", "sindico")->first();
            if ($usuarioApp) {
                return json_encode(["errors" => [0 => ["error_message" => "Este e-mail já está em uso", "error_code" => "exists-email"]]]);
            }

            $sindicoUser = Sindico::where("cpf", $request['cpf'])->first();
            if ($sindicoUser) {
                return json_encode(["errors" => [0 => ["error_message" => "Este CPF já está em uso", "error_code" => "exists-cpf"]]]);
            }


            #CADASTRO USUARIO APP
            $usuario_app = new UsuarioApp();
            $usuario_app->email = isset($request['email']) ? (string) $request['email'] : "";
            $usuario_app->senha = isset($request['senha']) ? (string) $request['senha'] : "";
            $usuario_app->tipo = "sindico";
            $usuario_app->senha = Hash::make($request['senha']);
            $usuario_app->save();
            #FIM CADASTRO USUARIO
            $sindico = new Sindico();
            $sindico->nome = $request['nome_sindico'];
            $sindico->CPF = $request['cpf'];
            $sindico->numero_documento = $request['numero_documento'];
            $sindico->telefone = $request['telefone'];
            $sindico->usuario_app_id = $usuario_app['id'];

            if ($request['data_inicio_mandato'])
                $sindico->data_inicio_mandato = Formatacao::data($request['data_inicio_mandato']);

            if ($request['data_fim_mandato'])
                $sindico->data_fim_mandato = Formatacao::data($request['data_fim_mandato']);

            if (isset($this->user_franqueado->id)) {
                $sindico->franqueado_id = $this->user_franqueado->id;
                $sindico->forma_cadastro = "Franquia " . $this->user_franqueado->nome;
            }

            $sindico->save();
            $sindico->usuarioApp = UsuarioApp::where("id", $sindico->usuario_app_id);
            Notificacao::painelNotificarUsuarioBoasVindas($sindico->nome, "sindico", $usuario_app);
            return json_encode($sindico);
        } catch (Exception $e) {
            return json_encode($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sindico  $sindico
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sindico = Sindico::findOrFail($id);
        $sindico->load('usuarioApp');
        $sindico->condominios = Condominio::where("sindico_id", $sindico->id)->get();
        foreach ($sindico->condominios as $i => $cond) {
            $sindico->condominios[$i]->orcamentos = Orcamento::where("condominio_id", $cond->id)->count();
            $sindico->condominios[$i]->bairroFK = Bairro::where("id", $cond->bairro_id)->first();
        }

        $sindico->data_inicio_mandato = Formatacao::data($sindico->data_inicio_mandato, false, false);
        $sindico->data_fim_mandato = Formatacao::data($sindico->data_fim_mandato, false, false);

        if ($this->user_franqueado)
            $franqueado_id = $this->user_franqueado->id;
        else
            $franqueado_id = null;

        return view($this->url . '.sindicos.show', compact('sindico', 'id', 'franqueado_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sindico  $sindico
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sindico = Sindico::findOrFail($id);
        $usuariosApp = UsuarioApp::pluck('email', 'id')->all();
        $franqueados = Franqueado::pluck('nome', 'id')->all();

        $sindico->data_inicio_mandato = Formatacao::data($sindico->data_inicio_mandato, false, false);
        $sindico->data_fim_mandato = Formatacao::data($sindico->data_fim_mandato, false, false);
        return view($this->url . '.sindicos.edit', compact('sindico', 'usuariosApp', 'franqueados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sindico  $sindico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (isset($request['cancel'])) {
            return redirect()->route($this->url . '.sindicos.index');
        }
        try {
            #CADASTRO USUARIO APP
            $sindico = Sindico::findOrFail($id);
            $request['usuario_app_id'] = $sindico->usuario_app_id;
            $this->getDataUpdate($request, $id);

            $usuario_app = UsuarioApp::findOrFail($sindico->usuario_app_id);
            $usuario_app->email = isset($request['email']) ? (string) $request['email'] : "";
            if ($request['senha'] != "" && $request['senha'] != null) {
                $usuario_app->senha = Hash::make($request['senha']);
            }
            $usuario_app->update();

            #FIM CADASTRO USUARIO
            $sindico->nome = $request['nome'];
            $sindico->CPF = $request['cpf'];
            $sindico->numero_documento = $request['numero_documento'];
            $sindico->telefone = $request['telefone'];
            if ($request['data_inicio_mandato'])
                $sindico->data_inicio_mandato = Formatacao::data($request['data_inicio_mandato']);

            if ($request['data_fim_mandato'])
                $sindico->data_fim_mandato = Formatacao::data($request['data_fim_mandato']);

            $sindico->update();
            return redirect()->route($this->url . '.sindicos.show', $sindico->id)
                ->with('success_message', 'Sindico foi atualizado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getDataUpdate($request, $id));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sindico  $sindico
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $sindico = Sindico::findOrFail($id);
            $user = UsuarioApp::where('id', $sindico->usuario_app_id)->first();
            if($user){
                if (isset($this->user_franqueado->id)) {
                    $user->removido_por = $this->url . " - Removido por Franquia #" . $this->user_franqueado->id . " - ". $this->user_franqueado->nome;
                } else {
                    $user->removido_por = $this->url . " - Removido por Super Admin";
                }

                $user->token_notification = null;
                $user->tokens()->delete();
                $user->update();
                $user->delete();
            }
            $sindico->delete();
            DB::commit();
            return redirect()->route($this->url . '.sindicos.index')
                ->with('success_message', 'Sindico foi deletado com sucesso.');
        } catch (Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->withErrors('Erro ao deletar sindico, tente mais tarde.');
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
            'nome' => 'required|string|min:1|max:255',
            'cpf' => 'required|cpf|string|min:11|max:60|unique:sindico,cpf,' . $request['cpf'] . ',id,deleted_at,NULL',
            'email' => 'required|email|unique:usuario_app,email,null,id,deleted_at,NULL,tipo,"sindico"',
            "numero_documento" => 'required|string',
            'telefone' => 'required|string|min:6|max:45',
            'franqueado_id' => 'nullable',
            'data_inicio_mandato' => 'nullable',
            'data_fim_mandato' => 'nullable',
            'senha' => 'required'
        ];

        $data = $request->validate($rules);

        return $data;
    }

    /**
     * Get the request's data from the request.
     *
     * @return array
     */
    protected function getDataUpdate(Request $request, $id)
    {
        $rules = [
            'nome' => 'required|string|min:1|max:255',
            'cpf' => 'required|cpf|string|min:11|max:60|unique:sindico,cpf,' . $id . ',id,deleted_at,NULL',
            'email' => 'required|unique:usuario_app,email,' . $request['usuario_app_id'] . ',id,deleted_at,NULL,tipo,"sindico"',
            "numero_documento" => 'required|string',
            'telefone' => 'required|string|min:6|max:45',
            'franqueado_id' => 'nullable',
            'data_inicio_mandato' => 'nullable',
            'data_fim_mandato' => 'nullable'
        ];

        $data = $request->validate($rules);

        return $data;
    }

    public function condominios($id)
    {
        $condominios = Condominio::where('sindico_id', $id)->with('regiao')->get();
        return view('admin_franqueado.sindicos.condominios.index', compact('condominios'));
    }
    public function orcamentos($id)
    {
        // SELECT * from `orcamento` INNER JOIN `condominio` on `condominio`.`id` = `orcamento`.`condominio_id` inner JOIN `sindico` on `sindico`.`id` = `condominio`.`sindico_id` where `sindico`.`id` = 1
        $orcamentos = Orcamento::join('condominio', 'condominio.id', 'orcamento.condominio_id')->join('sindico', 'sindico.id', 'condominio.sindico_id')->where('sindico.id', $id)->select('orcamento.*')->get();
        return view('admin_franqueado.orcamentos.index', compact('orcamentos'));
    }
    public function condominioOrcamentos($id)
    {
        // SELECT * from `orcamento` INNER JOIN `condominio` on `condominio`.`id` = `orcamento`.`condominio_id` where condominio.id = 1
        $orcamentos = Orcamento::join('condominio', 'condominio.id', 'orcamento.condominio_id')->where('condominio.id', $id)->select('orcamento.*')->get();
        return view('admin_franqueado.orcamentos.index', compact('orcamentos'));
    }
    public function condominiosDestroy($id)
    {
        try {
            $condominio = Condominio::findOrFail($id);
            $condominio->delete();
            return redirect()->route('admin_franqueado.sindicos.condominios.index')
                ->with('success_message', 'Condominio foi deletado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Erro ao deletar condominio, tente mais tarde.');
        }
    }
}
