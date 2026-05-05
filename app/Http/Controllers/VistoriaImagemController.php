<?php

namespace App\Http\Controllers;

use App\Models\VistoriaImagem;
use Exception;
use Illuminate\Http\Request;

class VistoriaImagemController extends Controller
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
     * @param  \App\Models\VistoriaImagem  $vistoriaImagem
     * @return \Illuminate\Http\Response
     */
    public function show(VistoriaImagem $vistoriaImagem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VistoriaImagem  $vistoriaImagem
     * @return \Illuminate\Http\Response
     */
    public function edit(VistoriaImagem $vistoriaImagem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VistoriaImagem  $vistoriaImagem
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        try {
            $v = VistoriaImagem::findOrFail($id);
            $v->descricao = $request['descricao'];
            $v->update();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VistoriaImagem  $vistoriaImagem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $v = VistoriaImagem::findOrFail($id);
            $v->delete();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
