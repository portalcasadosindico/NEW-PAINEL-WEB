<?php

namespace App\Http\Controllers;

use App\Models\Afiliado;
use App\Models\AfiliadoRegiao;
use App\Models\Configuracao;
use App\Models\ContratoAssinatura;
use App\Models\Franqueado;
use App\Models\FranqueadoRegiao;
use App\Models\Notificacao;
use App\Models\PlanoAssinaturaAfiliadoRegiao;
use App\Models\ResponsavelAfiliado;
use App\Uteis\autentique\DocumentosAutentique;
use App\Uteis\Formatacao;
use App\Uteis\StatusAssinaturaPlano;
use App\Uteis\Util;
use App\Uteis\Validacao;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class Destination
{
    const FILE = 'F';
    const DOWNLOAD = 'D';
    const STRING_RETURN = 'S';
    const INLINE = 'I';
}
class AfiliadoRegiaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $afiliado_regioes = AfiliadoRegiao::paginate(25);
        $afiliado_regioes->load('afiliado','regiao');
        return view('admin_franqueado.afiliados.index',compact('afiliado_regioes'));
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

    public function gerar_contrato($afiliado_regiao_id, Request $request){

        DB::beginTransaction();

        try{
            $afiliadoRegiao = AfiliadoRegiao::where("id", $afiliado_regiao_id)->first();
            $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $afiliadoRegiao->regiao_id)->where("status","ativo")->orderBy("id","desc")->first();
            $franqueado = Util::getFranqueado($franqueadoRegiao->franqueado_id);
            if($this->user_franqueado)
                $franqueado = $this->user_franqueado;

            $validador = new Validacao();
            $email_testemunha1 = $request['email_testemunha1'];
            $email_testemunha2 = $request['email_testemunha2'];
            if(isset($request['data_contrato'])){
                $data_contrato = Formatacao::data($request['data_contrato']);
            } else {
                $data_contrato = date("Y-m-d");
            }
            

            

            if($afiliadoRegiao){
                $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $afiliadoRegiao->regiao_id)->where("franqueado_id", $franqueado->id)->where("status", "ativo")->orderBy("id", "desc")->first();
                if($franqueadoRegiao==null){
                    return ["errors"=>[ Validacao::getError("Operação ilegal. Esta região não e sua.") ], "status"=>false];
                }
            }

            $planoAssinatura = PlanoAssinaturaAfiliadoRegiao::where("id", $afiliadoRegiao->plano_assinatura_afiliado_regiao_id)->first();
            $planoAssinatura->data_contrato = $data_contrato;
            $afiliado = Afiliado::where("id", $afiliadoRegiao->afiliado_id)->first();


            if($afiliado->usuarioApp && ($afiliado->usuarioApp->email == $email_testemunha1 || $afiliado->usuarioApp->email == $email_testemunha2)){
                return ["errors"=>[ Validacao::getError("Operação ilegal. O afiliado não pode ser testemunha do seu próprio contrato.") ], "status"=>false];
            }

            if($email_testemunha1 == $email_testemunha2){
                return ["errors"=>[ Validacao::getError("Operação ilegal. As testemunhas não podem ser a mesma pessoa.") ], "status"=>false];
            }
            
            

            $dataContratoOk = Franqueado::isDataContratoOk($franqueado->id);
            if($dataContratoOk['status']==false){
                return ["errors"=>[ Validacao::getError(implode("\n", $dataContratoOk['errors'])) ], "status"=>false];
            }

            $dataContratoOk = Afiliado::isDataContratoOk($afiliado->id);
            if($dataContratoOk['status']==false){
                return ["errors"=>[ Validacao::getError($dataContratoOk['errors']) ], "status"=>false];
            }

            if($afiliado->usuarioApp && $afiliado->usuarioApp->email == $franqueado->email){
                return ["errors"=>[ Validacao::getError("Operação ilegal. O franqueado não pode ser afiliado do seu próprio contrato.") ], "status"=>false];
            }

            if($franqueado->email == $email_testemunha1 || $franqueado->email == $email_testemunha2){
                return ["errors"=>[ Validacao::getError("Operação ilegal. O franqueado não pode ser testemunha do seu próprio contrato.") ], "status"=>false];
            }

            

            $validador->obrigatorio("email_testemunha1", $email_testemunha1, "E-mail da testemunha 1");
            $validador->obrigatorio("email_testemunha2", $email_testemunha2, "E-mail da testemunha 2");
            $validador->email("email_testemunha1", $email_testemunha1, "E-mail da testemunha 1");
            $validador->email("email_testemunha2", $email_testemunha2, "E-mail da testemunha 2");

            if($validador->verifica()){
                //Gerar PDF e resgatar o caminho salvo
                 if($planoAssinatura->tipo==1)
                     $modelo_contrato = "afiliado_parceiro";
                elseif($planoAssinatura->valor==0)
                    $modelo_contrato = "afiliado_gratuito";
                elseif($planoAssinatura->isTerceirizada==1)
                    $modelo_contrato = "afiliado_terceirizado";
                elseif($planoAssinatura->valor_comissao!==null && $planoAssinatura->valor_comissao==0)
                    $modelo_contrato = "afiliado_semcomissao";
                elseif($planoAssinatura->valor_comissao>0)
                    $modelo_contrato = "afiliado_comcomissao";
                
                ob_start();
                    $config = Configuracao::orderBy("id","desc")->first();

                    if($this->user_franqueado)
                        $franqueado = $this->user_franqueado;
                    else
                        $franqueado = $this->user_franqueado;
                    


                    $afiliado = Afiliado::withTrashed()->where("id", $afiliadoRegiao->afiliado_id)->first();
                    $afiliado->responsavelAfiliado = ResponsavelAfiliado::where("afiliado_id", $afiliado->id)->first();

                    include("../app/Uteis/functions_helper.php");
                    include("../resources/views/modelos_contratos/contrato_$modelo_contrato.blade.php");
                    $html = ob_get_contents();
                ob_end_clean();
                
                $pasta = "../storage/app/public/contratos/novos";
                if(!file_exists($pasta))
                    mkdir($pasta, 0777, true);
                
    
                $mpdf = new Mpdf();
                $rodape = '<div style="font-size: 10px; color: #555;">
                        <strong>'.$franqueado->razao_social.'</strong>
                        <br>
                        ' . $franqueado->rua . ", " . $franqueado->numero . ". " . $franqueado->bairro . ", " . $franqueado->cidade . "/" . $franqueado->estado.'
                    </div>';
                $mpdf->SetHTMLFooter($rodape);
                $mpdf->SetDisplayMode('fullpage');
                $mpdf->WriteHTML($html);
                $n = rand(-9999, 99999);
                $mpdf->Output($pasta . "/".md5($config->cnpj.$n) . ".pdf", Destination::FILE);

                $planoAssinatura->arquivo_original = "contratos/novos/" . md5($config->cnpj.$n) . ".pdf";
                
                $planoAssinatura->tipo_assinatura = 1;
                $planoAssinatura->update();


                // DB::commit();

                
                DocumentosAutentique::enviarContratoAutentique($afiliadoRegiao, $planoAssinatura, $afiliadoRegiao->afiliado, $email_testemunha1, $email_testemunha2);
                
                DB::commit();
                $afiliado = Afiliado::withTrashed()->where("id", $afiliadoRegiao->afiliado_id)->first();
                $usuarioApp = $afiliado->usuarioApp;
                
                if ($usuarioApp && $usuarioApp->token_notification){
                    SenderNotificacao::enviarNotificacaoNovoContrato($usuarioApp->token_notification);
                }
                
                SenderEmails::emailNotification("Seu contrato está disponível", "Seu contrato já está disponível. Acesse o App e confira em Gerenciar Regiões", $usuarioApp->email, $afiliado->razao_social);
                $res = Notificacao::painelNotificarAfiliadoNovoContratoRegiao($afiliado, $afiliadoRegiao);
                return ["status"=>true];
            } else {
                DB::rollBack();
                return ["errors"=>$validador->getErros(), "status"=>false];
            }
        }catch(Exception $e){
            DB::rollBack();
            return ["errors"=>$e->getMessage(), "status"=>false];
        }
    }


    public function regiao($id)
    {
        $afiliado_regiao = AfiliadoRegiao::where('afiliado_id',$id)->with(['regiao','planoassinaturaafiliadoregiao','afiliado'])->first();
        return view('admin_franqueado.afiliados.regiao.show',compact('afiliado_regiao'));
    }

    public function destroy($afiliado_regiao_id)
    {
        try {
            $afiliadoRegiao = AfiliadoRegiao::withTrashed()->where("id", $afiliado_regiao_id)->first();
            $afiliado_id = $afiliadoRegiao->afiliado_id;
            $afiliadoRegiao->delete();
            return redirect()->route($this->url . '.afiliados.show', $afiliado_id)
                ->with('success_message', 'Afiliado foi atualizado com sucesso.');
        } catch (Exception $e) {
            return redirect()->route($this->url . '.afiliados.show', $afiliado_id)
                ->with('success_message', 'Afiliado foi atualizado com sucesso.');
        }
    }
}
