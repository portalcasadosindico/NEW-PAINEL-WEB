<?php

namespace App\Http\Controllers;

use App\Models\Bairro;
use App\Models\Cidade;
use App\Models\Regiao;
use App\Uteis\Formatacao;
use App\Uteis\Url;
use Exception;
use Illuminate\Http\Request;

class BairroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($cidade_id=null)
    {
        $cidades = Cidade::orderBy("nome", "asc")->get();
        if($cidade_id==null){
            $bairros = [];
        } else {
            $bairros = Bairro::where("cidade_id", $cidade_id)->orderBy("nome","asc")->get();
        }
        $url = Url::baseURL();
        return view('bairros.index', compact('bairros','cidades', 'cidade_id', 'url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cidades = Cidade::pluck('nome', 'id')->all();
        $regioes = Regiao::pluck('nome', 'id')->all();
        return view('bairros.create', compact('cidades', 'regioes'));
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
            return redirect()->route('bairros.index');
        }
        try {
            $data = $this->getData($request);
            $bairro = new Bairro();
            $bairro->nome = $data['nome'];
            $bairro->cidade_id = $data['cidade_id'];
            if($data['regiao_id']>0){
                $bairro->regiao_id = $data['regiao_id'];
            }
            $cidade = Cidade::where("id", $bairro->cidade_id)->first();
            $bairro->chave = Formatacao::chave($data['nome'].$cidade->nome.$cidade->uf);
            $bairro->save();
            return redirect()->route('bairros.index')
                ->with('success_message', 'Bairro foi adicionado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bairro  $bairro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bairro = Bairro::findOrFail($id);
        $bairro->load('cidade','regiao');
        return view('bairros.show', compact('bairro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bairro  $bairro
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bairro = Bairro::findOrFail($id);
        $cidades = Cidade::pluck('nome', 'id')->all();
        $regioes = Regiao::pluck('nome', 'id')->all();
        return view('bairros.edit', compact('bairro', 'cidades', 'regioes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bairro  $bairro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		if(isset($request['cancel'])){
            return redirect()->route('bairros.index');
        }
        try {
            $data = $this->getData($request);
            $bairro =  Bairro::findOrFail($id);
            $regiaoAntiga = $bairro->regiao_id;
            $bairro->nome = $data['nome'];
            $bairro->cidade_id = $data['cidade_id'];
            $bairro->regiao_id = $data['regiao_id'];
            $bairro->chave = Formatacao::removerCaracteresEspeciais($data['nome']);
            $bairro->update();

            // Propaga a correção pras solicitações já existentes vinculadas a esse
            // bairro, que senão ficam presas com a região antiga (ver sessão 2026-09-04).
            if ((int) $regiaoAntiga !== (int) $bairro->regiao_id) {
                app(OrcamentoController::class)->resincronizarOrcamentosDoBairroAlterado($bairro->id);
            }

            return redirect()->route('bairros.index')
                ->with('success_message', 'Bairro foi atualizado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bairro  $bairro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $bairro =  Bairro::findOrFail($id);
            $bairro->delete();
            return redirect()->route('bairros.index')
                ->with('success_message', 'Bairro foi deletado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Erro ao deletar bairro, tente mais tarde.');
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
            'cidade_id' => 'required',
            'regiao_id' => 'nullable',
        ];

        $data = $request->validate($rules);

        return $data;
    }
}
