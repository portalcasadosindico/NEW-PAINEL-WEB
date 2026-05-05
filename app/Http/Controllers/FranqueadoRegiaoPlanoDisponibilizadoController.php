<?php

namespace App\Http\Controllers;

use App\Models\FranqueadoRegiao;
use App\Models\FranqueadoRegiaoPlanoDisponibilizado;
use App\Models\PlanoDisponivelFranqueado;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FranqueadoRegiaoPlanoDisponibilizadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $franqueado_regiao_planos_disponibilizados = FranqueadoRegiaoPlanoDisponibilizado::join('franqueado_regiao', 'franqueado_regiao.id', 'franqueado_regiao_plano_disponibilizado.franqueado_regiao_id')->where('franqueado_regiao.franqueado_id', Auth::guard('franqueados')->user()->id)->select('franqueado_regiao_plano_disponibilizado.*')->get();
        $franqueado_regiao_planos_disponibilizados->load('franqueadoRegiao', 'planoDisponivelFranqueado');
        return view('admin_franqueado.franqueado_regiao_planos_disponibilizados.index', compact('franqueado_regiao_planos_disponibilizados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $planos_disponiveis_franqueado = PlanoDisponivelFranqueado::pluck('nome', 'id')->all();
        $franqueado_regioes = FranqueadoRegiao::pluck('franqueado_id', 'id')->all();
        return view('admin_franqueado.franqueado_regiao_planos_disponibilizados.create', compact('planos_disponiveis_franqueado', 'franqueado_regioes'));
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
            return redirect()->route('admin_franqueado.franqueado_regiao_planos_disponibilizados.index');
        }
        try {
            $franqueado_regiao_planos_disponibilizados = new FranqueadoRegiaoPlanoDisponibilizado();
            $franqueado_regiao_planos_disponibilizados->franqueado_regiao_id = $request['franqueado_regiao_id'];
            $franqueado_regiao_planos_disponibilizados->plano_disponivel_franqueado_id = $request['plano_disponivel_franqueado_id'];
            $franqueado_regiao_planos_disponibilizados->save();
            return redirect()->route('admin_franqueado.franqueado_regiao_planos_disponibilizados.index')
                ->with('success_message', 'Plano disponibilizado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FranqueadoRegiaoPlanoDisponibilizado  $franqueadoRegiaoPlanoDisponibilizado
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $franqueado_regiao_plano_disponibilizado = FranqueadoRegiaoPlanoDisponibilizado::findOrFail($id);
        $franqueado_regiao_plano_disponibilizado->load('franqueadoRegiao', 'planoDisponivelFranqueado');
        return view('admin_franqueado.franqueado_regiao_planos_disponibilizados.show', compact('franqueado_regiao_plano_disponibilizado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FranqueadoRegiaoPlanoDisponibilizado  $franqueadoRegiaoPlanoDisponibilizado
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $franqueado_regiao_plano_disponibilizado = FranqueadoRegiaoPlanoDisponibilizado::findOrFail($id);
        $planos_disponiveis_franqueado = PlanoDisponivelFranqueado::pluck('nome', 'id')->all();
        $franqueado_regioes = FranqueadoRegiao::pluck('franqueado_id', 'id')->all();
        return view('admin_franqueado.franqueado_regiao_planos_disponibilizados.edit', compact('franqueado_regiao_plano_disponibilizado', 'planos_disponiveis_franqueado', 'franqueado_regioes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FranqueadoRegiaoPlanoDisponibilizado  $franqueadoRegiaoPlanoDisponibilizado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		if(isset($request['cancel'])){
            return redirect()->route('admin_franqueado.franqueado_regiao_planos_disponibilizados.index');
        }
        try {
            $franqueado_regiao_planos_disponibilizados = FranqueadoRegiaoPlanoDisponibilizado::findOrFail($id);
            $franqueado_regiao_planos_disponibilizados->franqueado_regiao_id = $request['franqueado_regiao_id'];
            $franqueado_regiao_planos_disponibilizados->plano_disponivel_franqueado_id = $request['plano_disponivel_franqueado_id'];
            $franqueado_regiao_planos_disponibilizados->update();
            return redirect()->route('admin_franqueado.franqueado_regiao_planos_disponibilizados.index')
                ->with('success_message', 'Plano atualizado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Error');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FranqueadoRegiaoPlanoDisponibilizado  $franqueadoRegiaoPlanoDisponibilizado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $franqueado_regiao_planos_disponibilizados = FranqueadoRegiaoPlanoDisponibilizado::findOrFail($id);
            $franqueado_regiao_planos_disponibilizados->delete();
            return redirect()->route('admin_franqueado.franqueado_regiao_planos_disponibilizados.index')
                ->with('success_message', 'Plano deletado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Error');
        }
    }

}
