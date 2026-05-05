<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Uteis\Formatacao;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::orderBy("categoria_pai_id", "asc")->orderBy("nome", "asc")->get();
        return view('categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::where("categoria_pai_id", null)->orderBy("nome", "asc")->get();
        return view('categorias.create', compact('categorias'));
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
            return redirect()->route('categorias.index');
        }
        try {
            $data = $this->getData($request);
            
            if(!empty($data)){
                $categoria = new Categoria();
                $categoria->nome = $data['nome'];
                $categoria->descricao = $data['descricao'];
                $categoria->status = $data['status'];
                $categoria->show_afiliado = $data['show_afiliado'];
                $categoria->categoria_pai_id = $data['categoria_pai_id'];
                $categoria->chave_url = Formatacao::removerCaracteresEspeciais($categoria->nome." ".$categoria->descricao);
                if(isset($data['imagem'])) {
                    $image_url = $data['imagem']->store('categoria/imagem');
                    $categoria->imagem = $image_url;
                }
                $categoria->save();
                return redirect()->route('categorias.show', [$categoria->id])
                    ->with('success_message', 'Categoria foi adicionada com sucesso.');
            }
            
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('categorias.show', compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categorias = Categoria::where("categoria_pai_id", null)->orderBy("nome", "asc")->get();
        $categoria = Categoria::findOrFail($id);
        return view('categorias.edit', compact('categorias', 'categoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(isset($request['cancel'])){
            return redirect()->route('categorias.index');
        }
        try {

            $data = $this->getData($request);
            $categoria = Categoria::findOrFail($id);
            $categoria->nome = $data['nome'];
            $categoria->descricao = $data['descricao'];
            $categoria->status = $data['status'];
            $categoria->show_afiliado = $data['show_afiliado'];
            $categoria->chave_url = Formatacao::removerCaracteresEspeciais($categoria->nome." ".$categoria->descricao);
            $categoria->categoria_pai_id = $data['categoria_pai_id'];
            
            if(isset($data['imagem'])) {
                Storage::delete($categoria->imagem);
                $image_url = $data['imagem']->store('categoria/imagem');
                $categoria->imagem = $image_url;
            }

            if(isset($request['remover_imagem'])){
                Storage::delete($categoria->imagem);
                $categoria->imagem = null;
            }
            $categoria->update();
            return redirect()->route('categorias.show', [$categoria->id])
                ->with('success_message', 'Categoria foi atualizada com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->delete();
            return redirect()->route('categorias.index')
                ->with('success_message', 'Categoria foi deletada com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Erro ao deletar categoria, tente mais tarde.');
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
            'categoria_pai_id' => 'nullable|string|min:1|max:80',
            'nome' => 'required|string|min:1|max:80',
            'descricao' => 'nullable|string|max:255',
            'imagem' => 'nullable|image',
            'status' => 'required',
            'show_afiliado' => 'required'
        ];

        $data = $request->validate($rules);

        return $data;
    }
}
