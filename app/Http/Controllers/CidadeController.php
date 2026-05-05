<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\Estado;
use App\Uteis\Formatacao;
use App\Uteis\Url;
use Exception;
use Illuminate\Http\Request;

class CidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($estado_id=null)
    {
        $estados = Estado::orderBy("nome", "asc")->get();
        if($estado_id!=null){
            $cidades = Cidade::where("estado_id", $estado_id)->orderBy("nome","asc")->get();
        } else {
            $cidades = [];
        }
        $url = Url::baseURL();
        return view('cidades.index',compact('cidades', 'estados', 'url', 'estado_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $estados = Estado::pluck('nome','id')->all();
        return view('cidades.create',compact('estados'));
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
            return redirect()->route('cidades.index');
        }
        try {
            $data = $this->getData($request);
            $cidade = new Cidade();
            $cidade->nome = $data['nome'];
            $cidade->estado_id = $data['estado_id'];
            
            $estado = Estado::where("id", $data['estado_id'])->first();
            $cidade->uf = $estado->uf;

            $cidade->chave = Formatacao::chave($data['nome']."".$estado->nome."".$estado->uf);
            $cidade->save();
            return redirect()->route('cidades.index')
                ->with('success_message', 'Cidade foi adicionada com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cidade  $cidade
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cidade = Cidade::findOrFail($id);
        $cidade->load('estado');
        return view('cidades.show',compact('cidade'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cidade  $cidade
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cidade = Cidade::findOrFail($id);
        $estados = Estado::pluck('nome','id')->all();
        return view('cidades.edit',compact('cidade','estados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cidade  $cidade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		if(isset($request['cancel'])){
            return redirect()->route('cidades.index');
        }
        try {
            $data = $this->getData($request);
            $cidade = Cidade::findOrFail($id);
            $cidade->nome = $data['nome'];
            $cidade->estado_id = $data['estado_id'];

            $estado = Estado::where("id", $data['estado_id'])->first();
            $cidade->uf = $estado->uf;
            $cidade->chave = Formatacao::chave($data['nome']."".$estado->nome."".$estado->uf);

            $cidade->update();
            return redirect()->route('cidades.index')
                ->with('success_message', 'Cidade foi atualizada com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cidade  $cidade
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $cidade = Cidade::findOrFail($id);
            $cidade->delete();
            return redirect()->route('cidades.index')
                ->with('success_message', 'Cidade foi deletada com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Erro ao deletar cidade, tente mais tarde.');
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
            'estado_id' => 'required',
        ];

        $data = $request->validate($rules);

        return $data;
    }
}
