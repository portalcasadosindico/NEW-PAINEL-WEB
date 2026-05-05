<?php

namespace App\Http\Controllers;

use App\Models\Franqueado;
use App\Models\FranqueadoRegiao;
use App\Models\Regiao;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FranqueadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $franqueados = Franqueado::all();
        return view('franqueados.index', compact('franqueados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regioes = Regiao::all('nome','id');
        return view('franqueados.create', compact('regioes'));
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
            return redirect()->route('franqueados.index');
        }

        DB::beginTransaction();
        try {
            $data = $this->getData($request);
            $franqueado = new Franqueado();
            $franqueado->nome = $data['nome'];
            $franqueado->nome_responsavel = $data['nome_responsavel'];
            $franqueado->email = $data['email'];
            $franqueado->senha = Hash::make($data['senha']);
            $franqueado->cnpj = $data['cnpj'];
            $franqueado->inscricao_estadual = $data['inscricao_estadual'];
            $franqueado->razao_social = $data['razao_social'];
            $franqueado->inscricao_municipal = $data['inscricao_municipal'];
            $franqueado->cpf_responsavel = $data['cpf_responsavel'];
            $franqueado->rg_responsavel = $data['rg_responsavel'];
            $franqueado->profissao_responsavel = $data['profissao_responsavel'];
            $franqueado->telefone_responsavel = $data['telefone_responsavel'];
            $franqueado->cep = $data['cep'];
            $franqueado->estado = $data['estado'];
            $franqueado->cidade = $data['cidade'];
            $franqueado->bairro = $data['bairro'];
            $franqueado->rua = $data['rua'];

            $franqueado->email_autentique = $data['email_autentique'];
            
            
            $franqueado->token_asaas_producao = $data['token_asaas_producao'];
            $franqueado->token_asaas_debug = $data['token_asaas_debug'];
            $franqueado->token_autentique = $data['token_autentique'];

            if (isset($request['cartao_cnpj'])) {
                $cartao_cnpj = $request['cartao_cnpj']->store('franqueado/cartaocnpj');
                $franqueado->cartao_cnpj = $cartao_cnpj;
            } else {
                $franqueado->cartao_cnpj = '';
            }

            if (isset($request['contrato_social'])) {
                $contrato_social = $request['contrato_social']->store('franqueado/contratosocial');
                $franqueado->contrato_social = $contrato_social;
            } else {
                $franqueado->contrato_social = '';
            }

            $franqueado->save();
            
            if ($request->regiao) {
                
                foreach ($request->regiao as $key => $b) {
                    $franqueadoRegiao = new FranqueadoRegiao();
                    $franqueadoRegiao->regiao_id = $b;
                    $franqueadoRegiao->franqueado_id = $franqueado->id;
                    $franqueadoRegiao->usuario_sistema_admin_id = Auth::guard('admins')->user()->id;
                    $franqueadoRegiao->save();
                }
            }
            
            DB::commit();
            return redirect()->route('franqueados.index')
                ->with('success_message', 'Franqueado foi adicionado com sucesso.');
        // [HUBBOX FIX] Retorno seguro de erro do sistema no cadastro de franqueado
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['erro_critico' => 'Falha interna ao salvar: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Franqueado  $franqueado
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $franqueado = Franqueado::findOrFail($id);
        return view('franqueados.show', compact('franqueado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Franqueado  $franqueado
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $regioes = Regiao::all('nome','id');
        $franqueado = Franqueado::findOrFail($id);
        return view('franqueados.edit', compact('franqueado', 'regioes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Franqueado  $franqueado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		if(isset($request['cancel'])){
            return redirect()->route('franqueados.index');
        }

        DB::beginTransaction();
        $request['franqueado_id'] = $id;
        try {
            $data = $this->getDataUpdate($request, $id);
            $franqueado = Franqueado::findOrFail($id);
            $franqueado->nome = $data['nome'];
            $franqueado->nome_responsavel = $data['nome_responsavel'];
            $franqueado->email = $data['email'];
            $franqueado->cnpj = $data['cnpj'];
            $franqueado->inscricao_estadual = $data['inscricao_estadual'];
            $franqueado->inscricao_municipal = $data['inscricao_municipal'];
            $franqueado->razao_social = $data['razao_social'];
            $franqueado->cpf_responsavel = $data['cpf_responsavel'];
            $franqueado->rg_responsavel = $data['rg_responsavel'];
            $franqueado->profissao_responsavel = $data['profissao_responsavel'];
            $franqueado->telefone_responsavel = $data['telefone_responsavel'];
            $franqueado->cep = $data['cep'];
            $franqueado->estado = $data['estado'];
            $franqueado->cidade = $data['cidade'];
            $franqueado->bairro = $data['bairro'];
            $franqueado->rua = $data['rua'];
            $franqueado->email_autentique = $data['email_autentique'];
            $franqueado->token_asaas_producao = $data['token_asaas_producao'];

            $franqueado->token_autentique = $data['token_autentique'];

            $franqueado->token_asaas_debug = $data['token_asaas_debug'];

            if (isset($request['cartao_cnpj'])) {
                $cartao_cnpj = $request['cartao_cnpj']->store('franqueado/cartaocnpj');
                $franqueado->cartao_cnpj = $cartao_cnpj;
            }

            if (isset($request['contrato_social'])) {
                $contrato_social = $request['contrato_social']->store('franqueado/contratosocial');
                $franqueado->contrato_social = $contrato_social;
            }

            if($request['senha']!="" && $request['senha']!=null)
                $franqueado->senha = Hash::make($request['senha']);

            $franqueado->update();

            $franqueadoRegioes = FranqueadoRegiao::where("franqueado_id", $franqueado->id)->where("status", "ativo")->get();
            foreach($franqueadoRegioes as $f){
                $remover = true;
                foreach ($request->regiao as $key => $regiao_id) {
                    if($regiao_id==$f->regiao_id){
                        $remover = false;
                    }
                }
                if($remover==true){
                    $f->status = "inativo";
                    $f->data_fim_atividade = date("Y-m-d");
                    $f->update();
                }
            }


            $franqueadoRegioes = FranqueadoRegiao::where("franqueado_id", $franqueado->id)->where("status", "ativo")->get();
            if ($request->regiao) {
                foreach ($request->regiao as $key => $regiao_id) {
                    $adicionar = true;
                    foreach ($franqueadoRegioes as $key => $f) {
                        if($regiao_id==$f->regiao_id){
                            $adicionar = false;
                        }
                    }

                    if($adicionar==true){
                        $franqueadoRegiao = new FranqueadoRegiao();
                        $franqueadoRegiao->regiao_id = $regiao_id;
                        $franqueadoRegiao->franqueado_id = $franqueado->id;
                        $franqueadoRegiao->usuario_sistema_admin_id = Auth::guard('admins')->user()->id;
                        $franqueadoRegiao->save();
                    }
                }
            }

            DB::commit();
            return redirect()->route('franqueados.show', $franqueado->id)->with('success_message', 'Franqueado foi atualizado com sucesso.');
        // [HUBBOX FIX] Retorno seguro de erro do sistema na atualização de franqueado
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['erro_critico' => 'Falha interna ao atualizar: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Franqueado  $franqueado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $franqueado = Franqueado::findOrFail($id);
            $franqueadoRegioes = FranqueadoRegiao::where("franqueado_id", $franqueado->id)->where("status", "ativo")->get();
            foreach($franqueadoRegioes as $f){
                $f->status = "inativo";
                $f->data_fim_atividade = date("Y-m-d");
                $f->update();
            }
            $franqueado->delete();
            DB::commit();
            return redirect()->route('franqueados.index')->with('success_message', 'Franqueado deletado com sucesso.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors('Erro ao deletear franqueado, tente mais tarde.');
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
            'nome' => 'required|string|min:1|max:200',
            'nome_responsavel' => 'nullable|string|min:1|max:200',
            'email' => 'required|unique:franqueado,email,null,id,deleted_at,NULL',
			'senha' => 'nullable|string|max:255',
            'cnpj' => 'required|cnpj|string|min:1|max:45',
            'inscricao_estadual' => 'nullable|string|max:45',
            'inscricao_municipal' => 'nullable|string|max:45',
            'razao_social' => 'nullable|string|max:255',
            'cpf_responsavel' => 'nullable|cpf| string|max:45',
            'rg_responsavel' => 'nullable|string|max:45',
            'profissao_responsavel' => 'nullable|string|max:255',
            'telefone_responsavel' => 'nullable|string|max:255',
            'cep' => 'nullable|string|max:45',
            'estado' => 'nullable|string|max:45',
            'cidade' => 'nullable|string|max:45',
            'bairro' => 'nullable|string|max:45',
            'rua' => 'nullable|string|max:255',
            'token_asaas_producao' => 'nullable|string',
            'token_asaas_debug' => 'nullable|string',
            'token_autentique' => 'nullable|string',
            'email_autentique' => 'required|email|min:1|max:255'
        ];

        $data = $request->validate($rules);

        return $data;
    }

    protected function getDataUpdate(Request $request, $franqueado_id)
    {
        $rules = [
            'nome' => 'required|string|min:1|max:200',
            'nome_responsavel' => 'nullable|string|min:1|max:200',
            'email' => 'required|unique:franqueado,email,'.$franqueado_id.',id,deleted_at,NULL',
			'senha' => 'nullable|string|max:255',
            'cnpj' => 'required|cnpj|string|min:1|max:45',
            'inscricao_estadual' => 'nullable|string|max:45',
            'inscricao_municipal' => 'nullable|string|max:45',
            'razao_social' => 'nullable|string|max:255',
            'cpf_responsavel' => 'nullable|cpf| string|max:45',
            'rg_responsavel' => 'nullable|string|max:45',
            'profissao_responsavel' => 'nullable|string|max:255',
            'telefone_responsavel' => 'nullable|string|max:255',
            'cep' => 'nullable|string|max:45',
            'estado' => 'nullable|string|max:45',
            'cidade' => 'nullable|string|max:45',
            'bairro' => 'nullable|string|max:45',
            'rua' => 'nullable|string|max:255',
            'token_asaas_producao' => 'nullable|string',
            'token_asaas_debug' => 'nullable|string',
            'token_autentique' => 'nullable|string',
            'email_autentique' => 'required|email|min:1|max:255'
        ];

        $data = $request->validate($rules);

        return $data;
    }

    /** Metodos para atualizar o perfil do admin_franqueado */

    public function profilePage()
    {
        $franqueado = Franqueado::findOrFail($this->user_franqueado->id);
        return view('admin_franqueado.perfil.show',compact('franqueado'));
    }
    public function updateProfilePage()
    {
        $franqueado = Franqueado::findOrFail($this->user_franqueado->id);
        return view('admin_franqueado.perfil.edit',compact('franqueado'));
    }
    public function updateProfile(Request $request)
    {
        $request['franqueado_id'] = $this->user_franqueado->id;
        try {
            $data = $this->getData($request);
            $franqueado = Franqueado::findOrFail($this->user_franqueado->id);
            $franqueado->nome = $data['nome'];
            $franqueado->nome_responsavel = $data['nome_responsavel'];
            $franqueado->email = $data['email'];
            $franqueado->cnpj = $data['cnpj'];
            $franqueado->inscricao_estadual = $data['inscricao_estadual'];
            $franqueado->inscricao_municipal = $data['inscricao_municipal'];
            $franqueado->cpf_responsavel = $data['cpf_responsavel'];
            $franqueado->rg_responsavel = $data['rg_responsavel'];
            $franqueado->razao_social = $data['razao_social'];
            $franqueado->profissao_responsavel = $data['profissao_responsavel'];
            $franqueado->telefone_responsavel = $data['telefone_responsavel'];
            $franqueado->cep = $data['cep'];
            $franqueado->estado = $data['estado'];
            $franqueado->cidade = $data['cidade'];
            $franqueado->bairro = $data['bairro'];
            $franqueado->rua = $data['rua'];
            if(isset($data['token_asaas_producao']))
                $franqueado->token_asaas_producao = $data['token_asaas_producao'];

            if(isset($data['token_autentique']))
                $franqueado->token_autentique = $data['token_autentique'];

            if(isset($data['token_asaas_debug']))
                $franqueado->token_asaas_debug = $data['token_asaas_debug'];

            $franqueado->update();
            return redirect()->route('admin_franqueado.profile.edit')
                ->with('success_message', 'Perfil foi atualizado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }
}
