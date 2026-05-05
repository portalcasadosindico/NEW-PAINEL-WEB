<?php

namespace App\Http\Controllers;

use App\Models\FranqueadoRegiao;
use App\Models\FranqueadoRegiaoPlanoDisponibilizado;
use App\Models\PlanoDisponivelFranqueado;
use App\Models\Regiao;
use App\Models\UsuarioSistemaAdmin;
use App\Uteis\Asaas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanoDisponivelFranqueadoController extends Controller
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
    public function index()
    {
        if ($this->url == 'admin') {
            $franqueado_id = null;
            $planos_disponiveis_franqueado = PlanoDisponivelFranqueado::orderBy("tipo", "asc")->orderBy("nome", "asc")->get();
        } else {
            $franqueado_id = $this->user_franqueado->id;
            //  SELECT `plano_disponivel_franqueado`.* FROM `plano_disponivel_franqueado` left join `franqueado_regiao`on `franqueado_regiao`.`id` = `plano_disponivel_franqueado`.`regiao_id` and `franqueado_regiao`.`franqueado_id` = 1 where statusPlano = 1
            $planos_disponiveis_franqueado = PlanoDisponivelFranqueado::leftJoin('franqueado_regiao', 'franqueado_regiao.id', 'plano_disponivel_franqueado.regiao_id', 'franqueado_regiao.franqueado_id', Auth::guard('franqueados')->user()->id)->where('plano_disponivel_franqueado.statusPlano', 1)->select('plano_disponivel_franqueado.*')->get();
            foreach ($planos_disponiveis_franqueado as $key => $plano) {
                //SELECT `franqueado_regiao_plano_disponibilizado`.* FROM `franqueado_regiao_plano_disponibilizado` INNER JOIN `franqueado_regiao` ON `franqueado_regiao`.`id` = `franqueado_regiao_plano_disponibilizado`.`franqueado_regiao_id` INNER JOIN `plano_disponivel_franqueado` ON `plano_disponivel_franqueado`.`regiao_id` = `franqueado_regiao_plano_disponibilizado`.`franqueado_regiao_id` where `plano_disponivel_franqueado_id` = 1
                $plano->disponivel = FranqueadoRegiaoPlanoDisponibilizado::join('franqueado_regiao', 'franqueado_regiao.id', 'franqueado_regiao_plano_disponibilizado.franqueado_regiao_id')->join('plano_disponivel_franqueado', 'plano_disponivel_franqueado.regiao_id', 'franqueado_regiao_plano_disponibilizado.franqueado_regiao_id')->where('plano_disponivel_franqueado_id', $plano->id)->select('franqueado_regiao_plano_disponibilizado.id')->get();
            }
        }

        return view($this->url . '.planos_disponiveis_franqueado.index', compact('planos_disponiveis_franqueado', 'franqueado_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regioes = Regiao::pluck('nome', 'id')->all();
        $usuariosSistemaAdmin = UsuarioSistemaAdmin::pluck('nome', 'id')->all();
        return view($this->url . '.planos_disponiveis_franqueado.create', compact('regioes', 'usuariosSistemaAdmin'));
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
            return redirect()->route($this->url . '.planos_disponiveis_franqueado.index');
        }
        try {
            $data = $this->getData($request);
            $plano_disponivel_franqueado = new PlanoDisponivelFranqueado();
            $plano_disponivel_franqueado->nome = $data['nome'];
            $plano_disponivel_franqueado->descricao = $data['descricao'];
            $plano_disponivel_franqueado->valor = $data['valor'];
            $plano_disponivel_franqueado->valor_comissao = $data['valor_comissao'];
            $plano_disponivel_franqueado->is_public = $data['is_public'];
            $plano_disponivel_franqueado->statusPlano = $data['statusPlano'];
            $plano_disponivel_franqueado->quantidade_meses_vigencia = Asaas::getMesesCiclo($data['ciclo']);
            $plano_disponivel_franqueado->dias_trial = $data['dias_trial'];
            $plano_disponivel_franqueado->tipo = $data['tipo'];
            $plano_disponivel_franqueado->desconto = $data['desconto'];
            $plano_disponivel_franqueado->ciclo = $data['ciclo'];
            $plano_disponivel_franqueado->isTerceirizada = isset($data['isTerceirizada']) ? 1 : 0;

            $plano_disponivel_franqueado->usuario_sistema_admin_id = Auth::guard('admins')->user()->id;
            if ($data['regiao_id'] > 0) {
                $plano_disponivel_franqueado->regiao_id = $data['regiao_id'];
            }

            $plano_disponivel_franqueado->save();
            return redirect()->route($this->url . '.planos_disponiveis_franqueado.index')
                ->with('success_message', 'Plano foi adicionado com sucesso.');
        } catch (Exception $e) {
            dd($e);
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PlanoDisponivelFranqueado  $planoDisponivelFranqueado
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $plano_disponivel_franqueado = PlanoDisponivelFranqueado::findOrFail($id);
        $plano_disponivel_franqueado->load('regiao', 'usuarioSistemaAdmin');
        $franqueado_id = $this->user_franqueado->id;
        return view($this->url . '.planos_disponiveis_franqueado.show', compact('plano_disponivel_franqueado', 'franqueado_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PlanoDisponivelFranqueado  $planoDisponivelFranqueado
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $plano_disponivel_franqueado = PlanoDisponivelFranqueado::findOrFail($id);
        $regioes = Regiao::pluck('nome', 'id')->all();
        $usuariosSistemaAdmin = UsuarioSistemaAdmin::pluck('nome', 'id')->all();
        return view($this->url . '.planos_disponiveis_franqueado.edit', compact('plano_disponivel_franqueado', 'regioes', 'usuariosSistemaAdmin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PlanoDisponivelFranqueado  $planoDisponivelFranqueado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (isset($request['cancel'])) {
            return redirect()->route($this->url . '.planos_disponiveis_franqueado.index');
        }
        try {
            $data = $this->getData($request);
            $plano_disponivel_franqueado = PlanoDisponivelFranqueado::findOrFail($id);
            $plano_disponivel_franqueado->nome = $data['nome'];
            $plano_disponivel_franqueado->descricao = $data['descricao'];
            $plano_disponivel_franqueado->is_public = $data['is_public'];
            $plano_disponivel_franqueado->valor = $data['valor'];
            $plano_disponivel_franqueado->valor_comissao = $data['valor_comissao'];
            $plano_disponivel_franqueado->statusPlano = $data['statusPlano'];
            $plano_disponivel_franqueado->dias_trial = $data['dias_trial'];
            $plano_disponivel_franqueado->quantidade_meses_vigencia = Asaas::getMesesCiclo($data['ciclo']);
            $plano_disponivel_franqueado->usuario_sistema_admin_id = Auth::guard('admins')->user()->id;
            $plano_disponivel_franqueado->tipo = $data['tipo'];
            $plano_disponivel_franqueado->ciclo = $data['ciclo'];

            $plano_disponivel_franqueado->desconto = $data['desconto'];
            $plano_disponivel_franqueado->isTerceirizada = isset($data['isTerceirizada']) ? 1 : 0;
            if ($data['regiao_id'] > 0) {
                $plano_disponivel_franqueado->regiao_id = $data['regiao_id'];
            } else {
                $plano_disponivel_franqueado->regiao_id = null;
            }
            $plano_disponivel_franqueado->update();
            return redirect()->route($this->url . '.planos_disponiveis_franqueado.index')
                ->with('success_message', 'Plano foi atualizado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PlanoDisponivelFranqueado  $planoDisponivelFranqueado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $plano_disponivel_franqueado = PlanoDisponivelFranqueado::findOrFail($id);
            $plano_disponivel_franqueado->delete();
            return redirect()->route($this->url . '.planos_disponiveis_franqueado.index')
                ->with('success_message', 'Plano foi deletado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Erro ao deletar plano, tente mais tarde.');
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
            'descricao' => 'nullable|string',
            'valor' => 'required|numeric',
            'valor_comissao' => 'nullable|numeric',
            'statusPlano' => 'required',
            'tipo' => 'required|numeric',
            'quantidade_meses_vigencia' => 'nullable|int',
            'dias_trial' => 'required|int',
            'regiao_id' => 'nullable',
            'desconto' => 'nullable|numeric',
            'isTerceirizada' => 'nullable',
            'ciclo' => 'required|string|min:1|max:45',
            'is_public' => 'nullable|numeric'
        ];

        $data = $request->validate($rules);

        return $data;
    }

    public function status(Request $request, $plano_disponivel_franqueado_id)
    {

        try {
            $newStatus = $request['newStatus'];

            $planoDisponivelFranqueado = PlanoDisponivelFranqueado::where("id", $plano_disponivel_franqueado_id)->first();
            $franqueado_regioes_id = FranqueadoRegiao::where('franqueado_id', Auth::guard('franqueados')->user()->id)->where("status", "ativo")->get();

            if ($newStatus == "ativo") {
                foreach ($franqueado_regioes_id as $franqueado_regiao_id) {
                    if ($planoDisponivelFranqueado->regiao_id == null || $planoDisponivelFranqueado->regiao_id == $franqueado_regiao_id->regiao_id) {
                        $franqueado_regiao_plano_disponibilizado = new FranqueadoRegiaoPlanoDisponibilizado();
                        $franqueado_regiao_plano_disponibilizado->franqueado_regiao_id = $franqueado_regiao_id->id;
                        $franqueado_regiao_plano_disponibilizado->plano_disponivel_franqueado_id = $plano_disponivel_franqueado_id;
                        $franqueado_regiao_plano_disponibilizado->save();
                    }
                }
            } elseif ($newStatus == "inativo") {
                foreach ($franqueado_regioes_id as $franqueado_regiao_id) {
                    if ($planoDisponivelFranqueado->regiao_id == null || $planoDisponivelFranqueado->regiao_id == $franqueado_regiao_id->regiao_id) {
                        $franqueado_regiao_plano_disponibilizado = FranqueadoRegiaoPlanoDisponibilizado::where("franqueado_regiao_id", $franqueado_regiao_id->id)->where("plano_disponivel_franqueado_id", $plano_disponivel_franqueado_id);
                        $franqueado_regiao_plano_disponibilizado->delete();
                    }
                }
            }

            /*foreach($franqueado_regioes_id as $franqueado_regiao_id){
                $franqueado_regiao_plano_disponibilizado = FranqueadoRegiaoPlanoDisponibilizado::where("regiao_id", $franqueado_regiao_id->id)->where('plano_disponivel_franqueado_id', $id)->first();
                if($planoDisponivelFranqueado->regiao_id==null || $planoDisponivelFranqueado->regiao_id==$franqueado_regiao_id->regiao_id){
                    $franqueado_regiao_plano_disponibilizado->delete();
                }
            }*/


            /*if ($franqueado_regiao_plano_disponibilizado) {
                $franqueado_regiao_plano_disponibilizado->delete();
            } else {*/

            // }
            return redirect()->route('admin_franqueado.planos_disponiveis_franqueado.index')
                ->with('success_message', 'Status com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Error');
        }
    }
}
