<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogComentario;
use App\Models\BlogTag;
use App\Uteis\Formatacao;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::all();
        return view('blogs.index', compact('blogs'));
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

            $tags = str_replace("\r", "", $request['tags']);
            

            $data = $this->getData($request);
            $blog = new Blog();
            $blog->nome = $data['nome'];
            $blog->descricao = $data['descricao'];
            $blog->fonte = $data['fonte'];
            $blog->resumo = $data['resumo'];
            $blog->chave_url = Formatacao::chaveUrl($blog->nome);
            $blog->chave = Formatacao::chaveUrl($blog->nome." ".$blog->descricao);
            if($data['imagem_principal'] != null){
                $image_url = $data['imagem_principal']->store('blog/imagem_principal');
                $blog->imagem_principal = $image_url;
            }else{
                $blog->imagem_principal = $data['imagem_principal'];
            }
            $blog->status = $data['status'];
            
            $blog->save();


            $tags = explode("\n", $tags);
            foreach($tags as $tag){
                $this->addTag($tag, $blog->id);
            }

            return redirect()->route('blogs.show', $blog->id)
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
        $tags = $this->getTags($id);
        $comentarios = BlogComentario::where("blog_id", $id)->orderBy("id", "desc")->get();

        return view('blogs.show',compact('blog', 'tags', 'comentarios'));
    }

    public function alterarStatus($newStatus, $comentarioId){
        $comentario = BlogComentario::where("id", $comentarioId)->first();
        $comentario->status = $newStatus;
        $comentario->update();
        /*return redirect()->route('blogs.show', $comentario->blog_id."#coment{$comentario->id}")
                ->with('success_message', 'Post foi adicionado com sucesso.');*/
        return $comentario;
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
        $tags = $this->getTags($id);
        $tags_editar = "";
        foreach($tags as $i=> $t){
            if($i==count($tags)-1)
                $tags_editar .= $t->nome;
            else
                $tags_editar .= $t->nome."\n";
        }
        $blog->tags = $tags_editar;
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

            $data = $this->getData($request);
            $blog =  Blog::findOrFail($id);
            $blog->nome = $data['nome'];
            $blog->descricao = $data['descricao'];
            $blog->fonte = $data['fonte'];
            $blog->resumo = $data['resumo'];
            if(isset($data['imagem_principal']) && $data['imagem_principal'] != null){
                $image_url = $data['imagem_principal']->store('blog/imagem_principal');
                $blog->imagem_principal = $image_url;
            }
            $blog->status = $data['status'];
            $blog->chave_url = Formatacao::chaveUrl($blog->nome);
            $blog->chave = Formatacao::chaveUrl($blog->nome." ".$blog->descricao);

           

            if(isset($request['remover_imagem'])){
                Storage::delete($blog->imagem_principal);
                $blog->imagem_principal = null;
            }
            $blog->update();
            $tags = str_replace("\r", "", $request['tags']);
            $tags = explode("\n", $tags);
            BlogTag::where("blog_id", $blog->id)->delete();
            foreach($tags as $tag){
                $this->addTag($tag, $blog->id);
            }

            return redirect()->route('blogs.show', $blog->id)
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
            'resumo' => 'nullable|string',
            'descricao' => 'required|string',
            'status' => 'required|string',
            'fonte' => 'nullable|string',
            'tags' => 'nullable|string',
        ];

        $data = $request->validate($rules);

        return $data;
    }


    public function uploadImagemConteudoBlog(Request $request){
        if(isset($request['file']) && $request['file'] != null){
            $image_url = $request['file']->store('blog/imagens');
        }
        $res = array(
            'location' => Storage::url($image_url)
        );
        return $res;
    }

    public function addTag($tag, $blog_id){
        $blog_tag = new BlogTag();
        $blog_tag->nome = $tag;
        $blog_tag->blog_id = $blog_id;
        $blog_tag->chave = Formatacao::chave($tag);
        $blog_tag->save();
        return $blog_tag;
    }

    public function getTags($blog_id){
        return BlogTag::where("blog_id", $blog_id)->orderBy("nome","asc")->get();
    }

}
