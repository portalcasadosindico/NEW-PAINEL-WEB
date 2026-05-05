<?php

namespace App\Http\Controllers;

use App\Models\Franqueado;
use App\Models\UsuarioApp;
use App\Models\Vistoria;
use App\Models\Vistoriador;
use App\Uteis\Url;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VistoriadorController extends Controller
{
    protected $url;
    public function __construct(Request $request)
    {
        parent::__construct($request);
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

        if ($this->url == "admin_franqueado") {
            $franqueado_id = $this->user_franqueado->id;
        }


        $franqueados = Franqueado::all(["id", "nome"]);
        if ($franqueado_id != null) {
            $vistoriadores = Vistoriador::where("franqueado_id", $franqueado_id)->get();
        } else {
            $vistoriadores = Vistoriador::orderBy("franqueado_id", "asc")->get();
        }
        $url = Url::baseURL();
        return view($this->url . '.vistoriadores.index', compact('vistoriadores', 'franqueados', 'url', 'franqueado_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $franqueados = Franqueado::pluck('nome', 'id')->all();
        return view($this->url . '.vistoriadores.create', compact('franqueados'));
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
            return redirect()->route($this->url . '.vistoriadores.index');
        }
        try {

            $this->getData($request);

            #CADASTRO USUARIO APP
            $usuario_app = new UsuarioApp();
            $usuario_app->email = isset($request['email']) ? (string) $request['email'] : "";
            $usuario_app->senha = isset($request['senha']) ? (string) $request['senha'] : "";
            $usuario_app->tipo = "vistoriador";
            $usuario_app->senha = Hash::make($request['senha']);
            $usuario_app->save();
            #FIM CADASTRO USUARIO

            $vistoriador = new Vistoriador();
            $vistoriador->nome = $request['nome'];
            $vistoriador->dados_acesso_condominio = $request['dados_acesso_condominio'];
            $vistoriador->usuario_app_id = $usuario_app->id;
            if ($request['franqueado_id'] > 0) {
                $vistoriador->franqueado_id = $request['franqueado_id'];
            }

            if ($this->url == "admin_franqueado") {
                $vistoriador->franqueado_id = $this->user_franqueado->id;
            }

            $vistoriador->save();
            return redirect()->route($this->url . '.vistoriadores.index')
                ->with('success_message', 'Vistoriador criado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vistoriador  $vistoriador
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vistoriador = Vistoriador::findOrFail($id);
        $vistorias = Vistoria::where("vistoriador_id", $id)->orderBy("data_vistoria")->get();
        $vistoriador->load('usuarioApp');
        return view($this->url . '.vistoriadores.show', compact('vistoriador', 'vistorias'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vistoriador  $vistoriador
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vistoriador = Vistoriador::findOrFail($id);
        $usuariosApp = UsuarioApp::pluck('email', 'id')->all();
        $franqueados = Franqueado::pluck('nome', 'id')->all();
        return view($this->url . '.vistoriadores.edit', compact('vistoriador', 'usuariosApp', 'franqueados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vistoriador  $vistoriador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (isset($request['cancel'])) {
            return redirect()->route($this->url . '.vistoriadores.index');
        }
        try {
            $vistoriador = Vistoriador::findOrFail($id);
            $request['usuario_app_id'] = $vistoriador->usuarioApp->id;
            $this->getDataUpdate($request);
            #CADASTRO USUARIO APP



            $vistoriador->usuarioApp->email = isset($request['email']) ? (string) $request['email'] : "";
            if ($request['senha'] != "" && $request['senha'] != null) {
                $vistoriador->usuarioApp->senha = Hash::make($request['senha']);
            }
            $vistoriador->usuarioApp->update();
            #FIM CADASTRO USUARIO


            $vistoriador->nome = $request['nome'];
            $vistoriador->dados_acesso_condominio = $request['dados_acesso_condominio'];

            $vistoriador->franqueado_id = $request['franqueado_id'];
            if ($request['franqueado_id'] > 0) {
                $vistoriador->franqueado_id = $request['franqueado_id'];
            } else {
                $vistoriador->franqueado_id = null;
            }

            $vistoriador->update();
            return redirect()->route($this->url . '.vistoriadores.index')
                ->with('success_message', 'Vistoriador foi atualizado com sucesso.');
        } catch (Exception $e) {

            return back()->withInput()
                ->withErrors($this->getDataUpdate($request));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vistoriador  $vistoriador
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $vistoriador = Vistoriador::findOrFail($id);
            $vistoriador->delete();
            $vistoriador->usuarioApp->delete();
            return redirect()->route($this->url . '.vistoriadores.index')
                ->with('success_message', 'Vistoriador foi deletado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Erro ao deletar vistoriador, tente mais tarde.');
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
            'nome' => 'required|string|min:1|max:45',
            'email' => 'required|unique:usuario_app,email,null,id,deleted_at,NULL,tipo,"vistoriador"',
            'senha' => 'required',
        ];

        $data = $request->validate($rules);

        return $data;
    }

    /**
     * Get the request's data from the request.
     *
     * @return array
     */
    protected function getDataUpdate(Request $request)
    {
        $rules = [
            'nome' => 'required|string|min:1|max:45',
            'email' => 'required|unique:usuario_app,email,' . $request['usuario_app_id'] . ',id,deleted_at,NULL,tipo,"vistoriador"',
        ];

        $data = $request->validate($rules);

        return $data;
    }
}
