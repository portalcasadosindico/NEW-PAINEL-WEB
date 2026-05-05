<?php

namespace App\Http\Controllers;

use App\Models\FranqueadoRegiao;
use App\Models\Regiao;
use Exception;
use Illuminate\Http\Request;

class FranqueadoRegiaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $franqueado_regioes = FranqueadoRegiao::where("franqueado_id", $this->user_franqueado->id)->where("status", "ativo")->get();
        return view('admin_franqueado.franqueado_regioes.index',compact('franqueado_regioes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regioes = Regiao::pluck('nome','id');
        return view('admin_franqueado.franqueado_regioes.create',compact('regioes'));
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
            return redirect()->route('admin_franqueado.franqueado_regioes.index');
        }
        try{
            $data = $this->getData($request);
            $franqueado_regiao = new FranqueadoRegiao();
            $franqueado_regiao->status = $data['status'];
            $franqueado_regiao->franqueado_id = $data['franqueado_id'];
            $franqueado_regiao->regiao_id = $data['regiao_id'];
            $franqueado_regiao->usuario_sistema_admin_id = $data['usuario_sistema_admin_id'];
            $franqueado_regiao->save();
            return redirect()->route('admin_franqueado.franqueado_regioes.index')
            ->with('success_message', 'Franqueado regiao foi adicionado com sucesso.');
        }catch(Exception $e){
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FranqueadoRegiao  $franqueadoRegiao
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $franqueado_regiao = FranqueadoRegiao::findOrFail($id);
        $franqueado_regiao->load('franqueado','regiao');
        return view('admin_franqueado.franqueado_regioes.show',compact('franqueado_regiao'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FranqueadoRegiao  $franqueadoRegiao
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $franqueado_regiao = FranqueadoRegiao::findOrFail($id);
        $regioes = Regiao::pluck('nome','id');
        return view('admin_franqueado.franqueado_regioes.edit',compact('franqueado_regiao','regioes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FranqueadoRegiao  $franqueadoRegiao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		if(isset($request['cancel'])){
            return redirect()->route('admin_franqueado.franqueado_regioes.index');
        }
        try{
            $data = $this->getData($request);
            $franqueado_regiao =  FranqueadoRegiao::findOrFail($id);
            $franqueado_regiao->status = $data['status'];
            $franqueado_regiao->franqueado_id = $data['franqueado_id'];
            $franqueado_regiao->regiao_id = $data['regiao_id'];
            $franqueado_regiao->usuario_sistema_admin_id = $data['usuario_sistema_admin_id'];
            $franqueado_regiao->update();
            return redirect()->route('admin_franqueado.franqueado_regioes.index')
                ->with('success_message', 'Franqueado regiao deletada com sucesso.');
        }catch(Exception $e){
            return back()->withInput()
            ->withErrors('Erro ao deletar fraqueado regiao, tente mais tarde.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FranqueadoRegiao  $franqueadoRegiao
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $franqueado_regiao = FranqueadoRegiao::findOrFail($id);
            $franqueado_regiao->delete();
            return redirect()->route('admin_franqueado.franqueado_regioes.index')
                ->with('success_message', 'Franqueado regiao deletada com sucesso.');
        }catch(Exception $e){
            return back()->withInput()
            ->withErrors('Erro ao deletar fraqueado regiao, tente mais tarde.');
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
            'status' => 'required|string',
            'franqueado_id' => 'required',
            'regiao_id' => 'required',
            'usuario_sistema_admin_id' => 'required'
        ];

        $data = $request->validate($rules);

        return $data;
    }
}
