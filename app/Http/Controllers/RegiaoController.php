<?php

namespace App\Http\Controllers;

use App\Models\Bairro;
use App\Models\Cidade;
use App\Models\Estado;
use App\Models\FranqueadoRegiao;
use App\Models\Regiao;
use App\Models\RegiaoFaixaCep;
use Exception;
use Illuminate\Http\Request;

class RegiaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $regioesDeletadas = Regiao::onlyTrashed()->get();
        // foreach($regioesDeletadas as $regiaoDeleteda){
        //     if($regiaoDeleteda->deleted_at!=null){
        //         $bairros = Bairro::where("regiao_id", $regiaoDeleteda->id)->get();
        //         foreach($bairros as $bairro){
        //             $bairro->regiao_id = null;
        //             $bairro->save();
        //         }
        
        //         $regioesFaixaCep = RegiaoFaixaCep::where("regiao_id", $regiaoDeleteda->id)->get();
        //         foreach($regioesFaixaCep as $regiaoFaixaCep){
        //             $regiaoFaixaCep->delete();
        //         }
        //     }
        // }

        $regioes = Regiao::get();

        

        return view('regioes.index', compact('regioes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $estados = Estado::orderBy("nome","asc")->pluck('nome', 'id')->all();
        $cidades = [];
        $bairros = [];
        return view('regioes.create', compact('estados', 'cidades', 'bairros'));
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
            return redirect()->route('regioes.index');
        }

        try {
			$this->getData($request);
            $regiao = new Regiao();
            $regiao->nome = $request['nome'];
            $regiao->descricao = $request['descricao'];
            $regiao->save();

            $ceps = explode("\n", str_replace("\r", "", $request['ceps']));
            
            foreach($ceps as $cep){
                if($cep){
                    $faixa_cep = new RegiaoFaixaCep();
                    $faixa_cep->cep = $cep;
                    $faixa_cep->regiao_id = $regiao->id;
                    $faixa_cep->save();
                }
            }

            $cidades = $request['cidades'];
            if($cidades){
                foreach($cidades as $cidade_id){
                    $faixa_cep = new RegiaoFaixaCep();
                    $faixa_cep->cidade_id = $cidade_id;
                    $faixa_cep->regiao_id = $regiao->id;
                    $faixa_cep->save();
                }
            }
            

            $bairros = $request['bairrosAdd'];
            if($bairros){
                foreach($bairros as $bairro_id){
                    $faixa_cep = new RegiaoFaixaCep();
                    $faixa_cep->bairro_id = $bairro_id;
                    $faixa_cep->regiao_id = $regiao->id;
                    $faixa_cep->save();
                }
            }
            

            if ($request['bairrosAdd']) {
                foreach ($request['bairrosAdd'] as $key => $b) {
                    $bairro = Bairro::findOrFail($b);
                    $bairro->regiao_id = $regiao->id;
                    $bairro->update();
                }
            }

            return redirect()->route('regioes.index')
                ->with('success_message', 'Regiao foi adicionada com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Regiao  $regiao
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $regiao = Regiao::findOrFail($id);
        return view('regioes.show', compact('regiao'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Regiao  $regiao
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $regiao = Regiao::findOrFail($id);
        $estados = Estado::orderBy("nome","asc")->pluck('nome', 'id')->all();
        $cidades = [];
        $bairros = [];

        $regiaoCeps = RegiaoFaixaCep::where("regiao_id", $id)->get();

        $ceps = [];
        $cidades = [];
        $bairros = [];
        foreach($regiaoCeps as $regiaoCep){
            if($regiaoCep->cep){
                $ceps[] = $regiaoCep->cep;
            }

            if($regiaoCep->cidade_id){
                $cidades[] = Cidade::where("id", $regiaoCep->cidade_id)->first();
            }

            if($regiaoCep->bairro_id){
                $ba = Bairro::where("id", $regiaoCep->bairro_id)->first();
                $bairros[$ba->cidade->nome][] = $ba;
            }
        }
        
        $ceps = implode("\n", $ceps);
        return view('regioes.edit', compact('cidades', 'bairros', 'ceps', 'regiao', 'estados', 'cidades', 'bairros'));
    }

    /**
     * Update the specified resource i  n storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Regiao  $regiao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		if(isset($request['cancel'])){
            return redirect()->route('regioes.index');
        }
        
        try {
            $data = $this->getData($request);
            $regiao = Regiao::findOrFail($id);
            $regiao->nome = $data['nome'];
            $regiao->descricao = $data['descricao'];
            $regiao->update();
            
            $bairros = Bairro::where('regiao_id', $regiao->id)->get();
            foreach($bairros as $b){
                $b->regiao_id = null;
                $b->update();
            }

            $regiaoCeps = RegiaoFaixaCep::where('regiao_id', $regiao->id)->get();
            foreach($regiaoCeps as $rc){
                $rc->delete();
            }


            $ceps = explode("\n", str_replace("\r", "", $request['ceps']));
            
            foreach($ceps as $cep){
                if($cep){
                    $faixa_cep = new RegiaoFaixaCep();
                    $faixa_cep->cep = $cep;
                    $faixa_cep->regiao_id = $regiao->id;
                    $faixa_cep->save();
                }
            }

            $cidades = $request['cidades'];
            if($cidades){
                foreach($cidades as $cidade_id){
                    $faixa_cep = new RegiaoFaixaCep();
                    $faixa_cep->cidade_id = $cidade_id;
                    $faixa_cep->regiao_id = $regiao->id;
                    $faixa_cep->save();
                }
            }
            

            $bairros = $request['bairrosAdd'];
            if($bairros){
                foreach($bairros as $bairro_id){
                    $faixa_cep = new RegiaoFaixaCep();
                    $faixa_cep->bairro_id = $bairro_id;
                    $faixa_cep->regiao_id = $regiao->id;
                    $faixa_cep->save();
                }
            }
            

            if ($request['bairrosAdd']) {
                foreach ($request['bairrosAdd'] as $key => $b) {
                    $bairro = Bairro::findOrFail($b);
                    $bairro->regiao_id = $regiao->id;
                    $bairro->update();
                }
            }



            return redirect()->route('regioes.index')
                ->with('success_message', 'Regiao foi atualizada com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($e);
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Regiao  $regiao
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $regiao = Regiao::findOrFail($id);

            $bairros = Bairro::where("regiao_id", $id)->get();
            foreach($bairros as $bairro){
                $bairro->regiao_id = null;
                $bairro->save();
            }
    
            $regioesFaixaCep = RegiaoFaixaCep::where("regiao_id", $id)->get();
            foreach($regioesFaixaCep as $regiaoFaixaCep){
                $regiaoFaixaCep->delete();
            }
            
            $regiao->delete();
            return redirect()->route('regioes.index')
                ->with('success_message', 'Regiao foi deletada com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Erro ao deletar regiao, tente mais tarde.');
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
            'descricao' => 'nullable|string|max:255',
        ];

        $data = $request->validate($rules);

        return $data;
    }

    public function fetchEstado(Request $request)
    {

        $value = $request->get('value');
        $data = Cidade::where('estado_id', $value)->where("nome", "<>", "")->where("nome", "<>", null)->orderBy("nome","asc")->get();
        foreach($data as $key => $cidade){
            $data[$key]["cidade_regiao"] = RegiaoFaixaCep::where("cidade_id", $cidade->id)->first();
            if($data[$key]["cidade_regiao"]){
                $regiao = Regiao::where("id", $data[$key]["cidade_regiao"]->regiao_id)->first();
                if($regiao==null){
                    //$data[$key]['cidade_regiao']["id"] = 0;
                } else {
                    $data[$key]['nome'] .= " (REGIÃO ". $regiao->nome . ")"; 
                }
            }
        }
        
        return response()->json([
            'data' => $data,
        ]);
    }
    public function fetchCidade(Request $request)
    {
        try{
            $value = $request->get('value');
            $regiao_id = $request->get('regiao_id');
            $bairros = Bairro::where('cidade_id', $value)->orderBy("nome", "asc")->get();
            //$regiao = Regiao::find($request->get('regiao_id'));
            $data = '';
            foreach ($bairros as $bairro) {
                if($bairro->regiao_id > 0){
                    $state = "checked";
                    if($bairro->regiao_id != $regiao_id){
                        $state = "checked disabled";
                    }
                    $title = "(Região ".($bairro->regiao ? $bairro->regiao->nome : "sem região").")";
                } else {
                    $title = "(Selecionar)";
                    $state = "";
                }
                
                /*$state .= $regiao != null && $bairro->regiao_id != $regiao->id ? "disabled " : " ";
                $state .= $regiao == null ? "disabled " : " ";*/

                $data .= "  <tr>
                                <td>
                                <div class=''>
                                    <label class='' title='".$title."'>
                                    <input type='checkbox' class='' name='bairro[]'
                                        id='bairro-".$bairro->id."' value='".json_encode($bairro)."'
                                        " . $state . "
                                        >
                                        " . $bairro->nome . " " . $title . "
                                    </label>
                                </div>
                                </td>
                            </tr>";
            };
            return $data;
        } catch (Exception $e) {
            return back()->withErrors('erro.');
        }
    }
}
