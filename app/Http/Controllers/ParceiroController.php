<?php

namespace App\Http\Controllers;

use App\Models\Configuracao;
use App\Models\Parceiro;
use App\Models\PlanoDisponivelFranqueado;
use App\Uteis\Formatacao;
use App\Uteis\Validacao;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;

class ParceiroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $parceiros = Parceiro::orderBy("status", "asc")->orderBy("nome", "asc")->get();
        return view('parceiros.index', compact('parceiros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $planos = PlanoDisponivelFranqueado::where("tipo", 1)->get();
        return view('parceiros.create', compact('planos'));
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
            return redirect()->route('parceiros.index');
        }
        try {
            if (!$request->has('logo')) {
                $request['logo'] = null;
            }
            $data = $this->getData($request);
            $parceiro = new Parceiro();
            $parceiro->nome = $data['nome'];
            if (isset($data['logo']) && $data['logo'] != null) {
                $image_url = $data['logo']->store('parceiros/logo');
                $parceiro->logo = $image_url;
            } else {
                $parceiro->logo = $data['logo'];
            }
            $parceiro->link = $data['link'];
            $parceiro->email = $data['email'];
            $parceiro->nome_responsavel = $data['nome_responsavel'];
            $parceiro->telefone = $data['telefone'];
            $parceiro->status = $data['status'];

            $parceiro->plano_id = $request['plano_id'];

            $parceiro->save();

            if(isset($request['integrar_asaas'])){
                //Integrar assinatura ao asaas
            }

            return redirect()->route('parceiros.index')
            ->with('success_message', 'Parceiro foi adicionado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getData($request));
        }
    }

    public function gerar_contrato($parceiro_id, Request $request){
                
        $validador = new Validacao();
        $email_testemunha1 = $request['email_testemunha1'];
        $email_testemunha2 = $request['email_testemunha2'];
        if(isset($request['data_contrato'])){
            $data_contrato = Formatacao::data($request['data_contrato']);
        } else {
            $data_contrato = date("Y-m-d");
        }
        
       

        $validador->obrigatorio("email_testemunha1", $email_testemunha1, "E-mail da testemunha 1");
        $validador->obrigatorio("email_testemunha2", $email_testemunha2, "E-mail da testemunha 2");
        $validador->email("email_testemunha1", $email_testemunha1, "E-mail da testemunha 1");
        $validador->email("email_testemunha2", $email_testemunha2, "E-mail da testemunha 2");

        if($validador->verifica()){
            //Gerar PDF e resgatar o caminho salvo

            $modelo_contrato = "afiliado_parceiro";
            
            ob_start();
                $config = Configuracao::orderBy("id","desc")->first();
                $parceiro = Parceiro::where("id", $parceiro_id)->first();

                include("../app/Uteis/functions_helper.php");
                include("../resources/views/modelos_contratos/contrato_$modelo_contrato.blade.php");
                $html = ob_get_contents();
            ob_end_clean();
            
            $pasta = "../storage/app/public/contratos/novos";
            if(!file_exists($pasta))
	            mkdir($pasta, 0777, true);
            
 
            $mpdf = new Mpdf();
            $rodape = '<div style="font-size: 10px; color: #555;">
                    <strong>'.$config->nome_empresa.'</strong>
                    <br>
                    '.$config->endereco.'
                </div>';
            $mpdf->SetHTMLFooter($rodape);
            $mpdf->SetDisplayMode('fullpage');
            
            //$css = file_get_contents("css/estilo.css");
            //$mpdf->WriteHTML($css,1);
            $mpdf->WriteHTML($html);
            $n = rand(-9999, 99999);
            $mpdf->Output($pasta . "/".md5($config->cnpj.$n) . ".pdf", Destination::FILE);

            $planoAssinatura->arquivo_original = "contratos/novos/" . md5($config->cnpj.$n) . ".pdf";
            
            $planoAssinatura->tipo_assinatura = 1;
            $planoAssinatura->update();
            $this->enviarContratoAutentique($planoAssinatura, $afiliadoRegiao->afiliado, $email_testemunha1, $email_testemunha2);
           
            return ["status"=>true];
        } else {
            return ["errors"=>$validador->getErros(), "status"=>false];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Parceiro  $parceiro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $parceiro = Parceiro::findOrFail($id);
        return view('parceiros.show', compact('parceiro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Parceiro  $parceiro
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $planos = PlanoDisponivelFranqueado::where("tipo", 1)->get();
        $parceiro = Parceiro::findOrFail($id);
        return view('parceiros.edit', compact('planos', 'parceiro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Parceiro  $parceiro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		if(isset($request['cancel'])){
            return redirect()->route('parceiros.index');
        }
        try {
            $data = $this->getDataUpdate($request);
            $parceiro = Parceiro::findOrFail($id);
            $parceiro->nome = $data['nome'];
            if (isset($data['logo']) && $data['logo'] != null) {
                Storage::delete($parceiro->logo);
                $image_url = $data['logo']->store('parceiros/logo');
                $parceiro->logo = $image_url;
            }
            
            $parceiro->link = $data['link'];
            $parceiro->email = $data['email'];
            $parceiro->nome_responsavel = $data['nome_responsavel'];
            $parceiro->telefone = $data['telefone'];
            $parceiro->status = $data['status'];
            if(isset($request['integrar_asaas']) && $parceiro->plano_id != $request['plano_id']){
                //Integrar assinatura ao asaas
                $parceiro->plano_id = $request['plano_id'];
            }
            $parceiro->update();
            
            return redirect()->route('parceiros.index')
            ->with('success_message', 'Parceiro foi atualizado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors($this->getDataUpdate($request));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Parceiro  $parceiro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $parceiro = Parceiro::findOrFail($id);
            $parceiro->delete();
            return redirect()->route('parceiros.index')
            ->with('success_message', 'Parceiro foi deletado com sucesso.');
        } catch (Exception $e) {
            return back()->withInput()
                ->withErrors('Erro ao deletar parceiro, tente mais tarde.');
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
            'nome' => 'required|string|min:1|max:50',
            'logo' => 'required|image|max:255',
            'link' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'nome_responsavel' => 'nullable|string|max:100',
            'telefone' => 'nullable|string|max:45',
            'status' => 'required|string|max:45',
        ];

        $data = $request->validate($rules);

        return $data;
    }

    protected function getDataUpdate(Request $request)
    {
        $rules = [
            'nome' => 'required|string|min:1|max:50',
            'logo' => 'nullable|image|max:255',
            'link' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'nome_responsavel' => 'nullable|string|max:100',
            'telefone' => 'nullable|string|max:45',
            'status' => 'required|string|max:45',
        ];

        $data = $request->validate($rules);

        return $data;
    }
}
