<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Exception;
use Illuminate\Http\Request;
use App\Uteis\RequestCpanel;
class CpanelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function emails()
    {
        $req = new RequestCpanel("sh-pro28.hostgator.com.br", "2083", "appbra17", "Rps!192021");
        dd($req->build("listpopswithdisk"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blogs.create');
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
            return redirect()->route('blogs.index');
        }
        try {
            if(!$request->has('imagem_principal')){
                $request['imagem_principal'] = null;
            }
            $data = $this->getData($request);
            $blog = new Blog();
            $blog->nome = $data['nome'];
            $blog->descricao = $data['descricao'];
            if($data['imagem_principal'] != null){
                $image_url = $data['imagem_principal']->store('blog/imagem_principal');
                $blog->imagem_principal = $image_url;
            }else{
                $blog->imagem_principal = $data['imagem_principal'];
            }
            $blog->status = $data['status'];
            $blog->save();
            return redirect()->route('blogs.index')
                ->with('success_message', 'Post foi adicionado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        return view('blogs.show',compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		if(isset($request['cancel'])){
            return redirect()->route('blogs.index');
        }
        try {
            if(!$request->has('imagem_principal')){
                $request['imagem_principal'] = null;
            }
            $data = $this->getData($request);
            $blog =  Blog::findOrFail($id);
            $blog->nome = $data['nome'];
            $blog->descricao = $data['descricao'];
            if($data['imagem_principal'] != null){
                $image_url = $data['imagem_principal']->store('blog/imagem_principal');
                $blog->imagem_principal = $image_url;
            }else{
                $blog->imagem_principal = $data['imagem_principal'];
            }
            $blog->status = $data['status'];
            $blog->save();
            return redirect()->route('blogs.index')
                ->with('success_message', 'Post foi adicionado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            $blog->delete();
            return redirect()->route('blogs.index')
                ->with('success_message', 'Post foi deletado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Erro ao deletar plano, tente mais tarde.');
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
            'imagem_principal' => 'nullable|image',
            'descricao' => 'nullable|string',
            'status' => 'required|string',
        ];

        $data = $request->validate($rules);

        return $data;
    }
}
