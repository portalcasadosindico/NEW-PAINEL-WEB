<?php

namespace App\Http\Controllers;

use App\Models\FranqueadoRegiao;
use App\Models\Orcamento;
use App\Models\Vistoria;
use App\Models\Vistoriador;
use App\Models\VistoriaImagem;
use App\Uteis\Formatacao;
use App\Uteis\StatusOrcamento;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class VistoriaController extends Controller
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
    public function index()
    {
        $vistoriasResource = Vistoria::get();

        $vistorias = [];
        foreach ($vistoriasResource as $v) {
            $o = Orcamento::where("id", $v->orcamento_id)->first();
            if ($o) {
                if ($this->url == "admin_franqueado") {
                    $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $o->regiao_id)->where("franqueado_id", $this->user_franqueado->id)->where("status", "ativo")->orderBy("id", "desc")->first();
                    if ($franqueadoRegiao) {
                        $vistorias[] = $v;
                    }
                } else {
                    $vistorias[] = $v;
                }
            }
        }

        if ($this->url == "admin_franqueado")
            return view($this->url . '.vistorias.index', compact('vistorias'));
        else
            return view('vistorias.index', compact('vistorias'));
    }

    public function agenda()
    {
        $vistorias = Vistoria::paginate(25);
        return view('vistorias.calendar', compact('vistorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if ($this->url == "admin")
            $vistoriadores = Vistoriador::pluck('nome', 'id')->all();
        else
            $vistoriadores = Vistoriador::where("franqueado_id", $this->user_franqueado->id)->pluck('nome', 'id')->all();


        $orcamentos = Orcamento::where("status", StatusOrcamento::$EM_EXECUCAO)->get();

        if ($this->url == "admin_franqueado")
            return view('admin_franqueado.vistorias.create', compact('vistoriadores', 'orcamentos'));
        else
            return view('vistorias.create', compact('vistoriadores', 'orcamentos'));
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
            return redirect()->route($this->url . '.vistorias.index');
        }
        try {

            //  $this->getData($request);
            $vistoria = new Vistoria();
            $vistoria->descricao = $request['descricao'];
            $vistoria->data_vistoria = Formatacao::data($request['data_vistoria']);
            $vistoria->hora_vistoria = $request['hora_vistoria'];
            $vistoria->vistoriador_id = $request['vistoriador_id'];
            $vistoria->orcamento_id = $request['orcamento_id'];
            $vistoria->status = $request['status'];
            $vistoria->show_data_agendamento_sindico = isset($request['show_data_agendamento_sindico']) ? "1" : "0";
            $vistoria->checkin_automatico = isset($request['checkin_automatico']) ? "1" : "0";

            $vistoria->forma_cadastro = "Via Painel Administrativo";

            $vistoria->save();

            $orcamento = Orcamento::where("id", $vistoria->orcamento_id)->first();
            $orcamento->data_atualizacao = Carbon::now();
            $orcamento->update();

            //Notificar vistoriador

            //Notificar síndico

            return redirect()->route($this->url . '.vistorias.index')
                ->with('success_message', 'Vistoria foi adicionada com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vistoria  $vistoria
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vistoria = Vistoria::findOrFail($id);
        $vistoria->load('vistoriador', 'orcamento');

        $imagens = VistoriaImagem::where("vistoria_id", $id)->get();

        if ($this->url == "admin_franqueado")
            return view('admin_franqueado.vistorias.show', compact('vistoria', 'imagens'));
        else
            return view('vistorias.show', compact('vistoria', 'imagens'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vistoria  $vistoria
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vistoria = Vistoria::findOrFail($id);
        $vistoria->data_vistoria = Formatacao::data($vistoria->data_vistoria);
        $vistoria->hora_vistoria = Formatacao::hora($vistoria->hora_vistoria, true, true, false);

        if ($this->url == "admin")
            $vistoriadores = Vistoriador::pluck('nome', 'id')->all();
        else
            $vistoriadores = Vistoriador::where("franqueado_id", $this->user_franqueado->id)->pluck('nome', 'id')->all();


        $orcamentos = Orcamento::where("status", StatusOrcamento::$EM_EXECUCAO)->orWhere("id", $vistoria->orcamento_id)->get();

        if ($this->url == "admin_franqueado")
            return view('admin_franqueado.vistorias.edit', compact('vistoria', 'vistoriadores', 'orcamentos'));
        else
            return view('vistorias.edit', compact('vistoria', 'vistoriadores', 'orcamentos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vistoria  $vistoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (isset($request['cancel'])) {
            return redirect()->route($this->url . '.vistorias.index');
        }
        try {
            $this->getData($request);
            $vistoria = Vistoria::findOrFail($id);
            $vistoria->descricao = $request['descricao'];
            $vistoria->data_vistoria = Formatacao::data($request['data_vistoria']);
            $vistoria->hora_vistoria = $request['hora_vistoria'];
            $vistoria->status = $request['status'];
            $vistoria->vistoriador_id = $request['vistoriador_id'];
            $vistoria->orcamento_id = $request['orcamento_id'];
            $vistoria->checkin_automatico = isset($request['checkin_automatico']) ? "1" : "0";
            $vistoria->show_data_agendamento_sindico = isset($request['show_data_agendamento_sindico']) ? "1" : "0";
            $vistoria->update();

            $orcamento = Orcamento::where("id", $vistoria->orcamento_id)->first();
            $orcamento->data_atualizacao = Carbon::now();
            $orcamento->update();

            //Notificar vistoriador

            //Notificar síndico

            return redirect()->route($this->url . '.vistorias.index')
                ->with('success_message', 'Vistoria foi atualizada com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vistoria  $vistoria
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $vistoria = Vistoria::findOrFail($id);
            $vistoria->delete();
            return redirect()->route($this->url . '.vistorias.index')
                ->with('success_message', 'Vistoria foi deletada com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Erro ao deletar vistoria, tente mais tarde.');
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
            'descricao' => 'nullable|string',
            'vistoriador_id' => 'nullable',
            'orcamento_id' => 'required',
        ];

        $data = $request->validate($rules);

        return $data;
    }
}
