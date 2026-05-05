<?php

namespace App\Http\Controllers;

use App\Models\Bairro;
use App\Models\Rua;
use Exception;
use Illuminate\Http\Request;

class RuaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ruas = Rua::paginate(25);
        return view('ruas.index',compact('ruas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bairros = Bairro::pluck('nome','id')->all();
        return view('ruas.create',compact('bairros'));
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
            return redirect()->route('ruas.index');
        }
        try {
            $data = $this->getData($request);
            $rua = new Rua();
            $rua->nome = $data['nome'];
            $rua->cep = $data['cep'];
            $rua->bairro_id = $data['bairro_id'];
            $rua->chave = '';
            $rua->save();
            return redirect()->route('ruas.index')
                ->with('success_message', 'Rua foi adicionada com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rua  $rua
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rua = Rua::findOrFail($id);
        $rua->load('bairro');
        return view('ruas.show',compact('rua'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rua  $rua
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rua = Rua::findOrFail($id);
        $bairros = Bairro::pluck('nome','id')->all();
        return view('ruas.edit',compact('rua','bairros'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rua  $rua
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		if(isset($request['cancel'])){
            return redirect()->route('ruas.index');
        }
        try {
            $data = $this->getData($request);
            $rua =  Rua::findOrfail($id);
            $rua->nome = $data['nome'];
            $rua->cep = $data['cep'];
            $rua->bairro_id = $data['bairro_id'];
            $rua->chave = '';
            $rua->update();
            return redirect()->route('ruas.index')
                ->with('success_message', 'Rua foi atualizada com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rua  $rua
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $rua =  Rua::findOrfail($id);
            $rua->delete();
            return redirect()->route('ruas.index')
                ->with('success_message', 'Rua foi deletada com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Erro ao deletar rua, tente mais tarde.');
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
            'cep' => 'required|string|max:8',
            'bairro_id' => 'required',
        ];

        $data = $request->validate($rules);

        return $data;
    }
}
