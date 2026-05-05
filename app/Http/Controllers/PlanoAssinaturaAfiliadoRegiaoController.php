<?php

namespace App\Http\Controllers;

use App\Models\AfiliadoRegiao;
use App\Models\ContratoAssinatura;
use App\Models\FranqueadoRegiao;
use App\Models\PlanoAssinaturaAfiliadoRegiao;
use App\Uteis\Asaas;
use App\Uteis\autentique\DocumentosAutentique;
use App\Uteis\StatusAssinaturaPlano;
use App\Uteis\StatusPlano;
use App\Uteis\Util;
use Exception;
use Illuminate\Http\Request;

class PlanoAssinaturaAfiliadoRegiaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PlanoAssinaturaAfiliadoRegiao  $planoAssinaturaAfiliadoRegiao
     * @return \Illuminate\Http\Response
     */
    public function show(PlanoAssinaturaAfiliadoRegiao $planoAssinaturaAfiliadoRegiao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PlanoAssinaturaAfiliadoRegiao  $planoAssinaturaAfiliadoRegiao
     * @return \Illuminate\Http\Response
     */
    public function edit(PlanoAssinaturaAfiliadoRegiao $planoAssinaturaAfiliadoRegiao)
    {
        //
    }

    public function alterarStatus(Request $request, $id)
    {
        try{
            $plano_regiao = PlanoAssinaturaAfiliadoRegiao::where("id", $id)->first();
            $plano_regiao->statusPlano = $request["newStatus"];
            $plano_regiao->update();
            return $plano_regiao;
        } catch(Exception $e){
            return $e->getCode();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PlanoAssinaturaAfiliadoRegiao  $planoAssinaturaAfiliadoRegiao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PlanoAssinaturaAfiliadoRegiao $planoAssinaturaAfiliadoRegiao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PlanoAssinaturaAfiliadoRegiao  $planoAssinaturaAfiliadoRegiao
     * @return \Illuminate\Http\Response
     */
    public function destroy(PlanoAssinaturaAfiliadoRegiao $planoAssinaturaAfiliadoRegiao)
    {
        //
    }




    



}
