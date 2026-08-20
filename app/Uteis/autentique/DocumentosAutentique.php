<?php


namespace App\Uteis\autentique;

use App\Models\AfiliadoRegiao;
use App\Models\Condominio;
use App\Models\ContratoAssinatura;
use App\Models\FranqueadoRegiao;
use App\Models\OrcamentoAssinatura;
use App\Uteis\StatusAssinaturaPlano;
use App\Uteis\Util;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\UsuarioApp;


class DocumentosAutentique
{

    /**
     * List all documents
     *
     * @param  int  $page
     * @return bool|string
     */
    public static function listAll($token, int $page = 1)
    {
        return Api::request($token, "listar", 'json', null, $page);
    }

    /**
     * List document by id
     *
     * @param string $documentId
     *
     * @return bool|string
     */
    public static function listById($token, string $documentId)
    {
        return Api::request($token, "ler", 'json', null, $documentId);
    }

    /**
     * Create Document
     *
     * @param array $attributes
     * @return bool|false|string
     */
    public static function create($token, array $attributes)
    {
        return Api::request(
            $token,
            "cadastrar",
            'form',
            $attributes
        );
    }

    /**
     * Sign document by id
     *
     * @param string $documentId
     *
     * @return bool|string
     */
    public static function signById($token, string $documentId)
    {
        return Api::request($token, "assinar", 'json', null, $documentId);
    }

    /**
     * Delete document by id
     *
     * @param string $documentId
     *
     * @return bool|string
     */
    public static function deleteById($token, string $documentId)
    {
        return Api::request($token, "deletar", 'json', null, $documentId);
    }


    public static function enviarContratoAutentique($afiliadoRegiao, $plano_assinatura, $afiliado, $testemunha1, $testemunha2)
    {
        try{
            $tokenAutentique = null;
            if($afiliadoRegiao){
                $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $afiliadoRegiao->regiao_id)->where("status","ativo")->orderBy("id","desc")->first();
                if($franqueadoRegiao){
                    $tokenAutentique = Util::getTokenAutentique($franqueadoRegiao->franqueado_id);
                    $franqueado = Util::getFranqueado($franqueadoRegiao->franqueado_id);
                }
            }
            if($tokenAutentique==null || $plano_assinatura->arquivo_original==null){
                Log::warning('Falha ao enviar contrato pro Autentique: token ou arquivo ausente', [
                    'afiliado_regiao_id' => $afiliadoRegiao->id ?? null,
                    'regiao_id' => $afiliadoRegiao->regiao_id ?? null,
                    'franqueadoRegiao_franqueado_id' => $franqueadoRegiao->franqueado_id ?? null,
                    'tokenAutentique_vazio' => empty($tokenAutentique),
                    'arquivo_original' => $plano_assinatura->arquivo_original ?? null,
                ]);
                return false;
            }
            
            DB::beginTransaction();

            $plano_assinatura->titulo_contrato = 'Contrato #' . $plano_assinatura->id;

            $attributes = [
                'document' => [
                    'name' => $plano_assinatura->titulo_contrato,
                ],
                'file' => "../storage/app/public/" . $plano_assinatura->arquivo_original,
            ];

            #FRANQUEADO
            $attributes["signers"][] = [
                'email' => $franqueado->email_autentique,
                'action' => 'SIGN'
            ];

            #AFILIADO
            $attributes["signers"][] = [
                'name' =>  $afiliado->razao_social,
                'action' => 'SIGN'
            ];

            #TESTEMUNHA 1
            $attributes["signers"][] = [
                'email' =>  $testemunha1,
                'action' => 'SIGN_AS_A_WITNESS'
            ];

            #TESTEMUNHA 2
            $attributes["signers"][] = [
                'email' =>  $testemunha2,
                'action' => 'SIGN_AS_A_WITNESS'
            ];

            $resRaw = DocumentosAutentique::create($tokenAutentique, $attributes);
            $res = json_decode($resRaw);

            if(!$res || !isset($res->data)){
                Log::warning('Falha ao criar documento no Autentique: resposta inesperada', [
                    'afiliado_regiao_id' => $afiliadoRegiao->id ?? null,
                    'raw_response' => $resRaw,
                ]);
                return false;
            }

            $plano_assinatura->documento_id_autentique = $res->data->createDocument->id;
            //DocumentosAutentique::signById($tokenAutentique, $plano_assinatura->documento_id_autentique);
            $res2 = json_decode(DocumentosAutentique::listById($tokenAutentique, $plano_assinatura->documento_id_autentique));

            if(!$res2 || !isset($res2->data)){
                return false;
            }

            $plano_assinatura->arquivo_original_autentique = $res2->data->document->files->original;
            $plano_assinatura->arquivo_assinado = $res2->data->document->files->signed;
            $plano_assinatura->update();

            $assinaturas = $res2->data->document->signatures;
            $assinatura_franqueado = new ContratoAssinatura();
            $assinatura_franqueado->plano_assinatura_afiliado_regiao_id = $plano_assinatura->id;
            $assinatura_franqueado->documento_id_autentique = $plano_assinatura->documento_id_autentique;
            $assinatura_franqueado->tipo_usuario = "franqueado";
            $assinatura_franqueado->email = $franqueado->email_autentique;
            $assinatura_franqueado->franqueado_id = $franqueado->id;
            $assinatura_franqueado->nome = $attributes['document']['name'];

            $assinatura_afiliado = new ContratoAssinatura();
            $assinatura_afiliado->plano_assinatura_afiliado_regiao_id = $plano_assinatura->id;
            $assinatura_afiliado->documento_id_autentique = $plano_assinatura->documento_id_autentique;
            $assinatura_afiliado->tipo_usuario = "afiliado";
            $assinatura_afiliado->email = isset($afiliado->usuarioApp->email) ? $afiliado->usuarioApp->email : $afiliado->email;
            $assinatura_afiliado->afiliado_id = $afiliado->id;
            $assinatura_afiliado->nome = $attributes['document']['name'];
            $assinatura_afiliado->nome_assinante = $afiliado->razao_social;

            $assinatura_testemunha1 = new ContratoAssinatura();
            $assinatura_testemunha1->plano_assinatura_afiliado_regiao_id = $plano_assinatura->id;
            $assinatura_testemunha1->documento_id_autentique = $plano_assinatura->documento_id_autentique;
            $assinatura_testemunha1->tipo_usuario = "testemunha1";
            $assinatura_testemunha1->email = $testemunha1;
            $assinatura_testemunha1->nome = $attributes['document']['name'];

            $assinatura_testemunha2 = new ContratoAssinatura();
            $assinatura_testemunha2->plano_assinatura_afiliado_regiao_id = $plano_assinatura->id;
            $assinatura_testemunha2->documento_id_autentique = $plano_assinatura->documento_id_autentique;
            $assinatura_testemunha2->tipo_usuario = "testemunha2";
            $assinatura_testemunha2->email = $testemunha2;
            $assinatura_testemunha2->nome = $attributes['document']['name'];

            foreach ($assinaturas as $assinatura) {
                if(isset($assinatura->user) && $assinatura->user!=null){
                    if ($assinatura->user->email == $assinatura_franqueado->email) {
                        $assinatura_franqueado->public_id = $assinatura->public_id;
                        $assinatura_franqueado->user_id_autentique = $assinatura->user->id;
                        $assinatura_franqueado->signed = isset($assinatura->signed->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->signed->created_at)) : null;
                    }
    
                    if ($assinatura->user->email == $testemunha1) {
                        $assinatura_testemunha1->public_id = $assinatura->public_id;
                        $assinatura_testemunha1->user_id_autentique = $assinatura->user->id;
                    }
    
                    if ($assinatura->user->email == $testemunha2) {
                        $assinatura_testemunha2->public_id = $assinatura->public_id;
                        $assinatura_testemunha2->user_id_autentique = $assinatura->user->id;
                    }
    
                    if ($assinatura->user->email == $assinatura_afiliado->email) {
                        $assinatura_afiliado->public_id = $assinatura->public_id;
                        $assinatura_afiliado->user_id_autentique = $assinatura->user->id;
                    }
                } else {
                    if($assinatura->name==$assinatura_afiliado->nome_assinante){
                        $assinatura_afiliado->public_id = $assinatura->public_id;
                        $assinatura_afiliado->short_link = $assinatura->link->short_link;
                    }
                }
            }

            $plano_assinatura->status = StatusAssinaturaPlano::$AGUARDANDO;
            $plano_assinatura->status_afiliado = StatusAssinaturaPlano::$AGUARDANDO;
            $plano_assinatura->status_testemunha1 = StatusAssinaturaPlano::$AGUARDANDO;
            $plano_assinatura->status_testemunha2 = StatusAssinaturaPlano::$AGUARDANDO;
            $plano_assinatura->update();

            $assinatura_franqueado->save();
            $assinatura_afiliado->save();
            $assinatura_testemunha1->save();
            $assinatura_testemunha2->save();
            DB::commit();
            return true;
        } catch(Exception $e){
            Log::error('Erro ao gerar contrato: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            DB::rollBack();
            return false;
        }
    }



    public static function enviarContratoServico($orcamento, $email_testemunha1, $email_testemunha2){
        
        $email_testemunha1 = !empty($email_testemunha1) ? $email_testemunha1 : 'alexandrejacquet333@gmail.com';
        $email_testemunha2 = !empty($email_testemunha2) ? $email_testemunha2 : 'financeiro@casadosindico.srv.br';
        
        Log::channel('orcamento')->info('Verificando email testemunha', [
            'email testemunha 1' => $email_testemunha1,
            'email testemunha 2' => $email_testemunha2,
        ]);
        
        Log::channel('contratoServico')->info('Chamou o enviarContratoServico');
        try{
            $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $orcamento->regiao_id)->orderBy("id", "desc")->first();
            $condominio = Condominio::where("id", $orcamento->condominio_id)->first();
            $attributes = [
                'document' => [
                    'name' => $orcamento->titulo_contrato,
                ],
                'file' => "../storage/app/public/" . $orcamento->contrato,
            ];

            if (!$orcamento->data_assinatura_sindico)
                $attributes["signers"][] = [
                    'name' => $orcamento->condominio->sindico->nome,
                    'action' => 'SIGN'
                ];

            if (!$orcamento->data_assinatura_afiliado && $orcamento->afiliado()->withTrashed()->first())
                $attributes["signers"][] = [
                    'name' =>  $orcamento->afiliado()->withTrashed()->first()->razao_social . ".",
                    'action' => 'SIGN'
                ];

            if (!$orcamento->data_assinatura_franqueado)
                $attributes["signers"][] = [
                    'email' =>  $franqueadoRegiao->franqueado->email_autentique,
                    'action' => 'SIGN'
                ];


            if ($email_testemunha1)
                $attributes["signers"][] = [
                    'email' =>  $email_testemunha1,
                    'action' => 'SIGN_AS_A_WITNESS'
                ];

            if ($email_testemunha1)
                $attributes["signers"][] = [
                    'email' =>  $email_testemunha2,
                    'action' => 'SIGN_AS_A_WITNESS'
                ];
                
            Log::channel('contratoServico')->error('Atributos', ['attributes' => $attributes]);
            Log::channel('contratoServico')->error('token', ['token' => $franqueadoRegiao->franqueado->token_autentique]);

            $res = json_decode(DocumentosAutentique::create($franqueadoRegiao->franqueado->token_autentique, $attributes));

            if(!$res || !isset($res->data)){
                // Log::channel('contratoServico')->error('Falha ao criar documento', ['res' => $res]);
                return;
            }
            
            // $responseRaw = DocumentosAutentique::create($franqueadoRegiao->franqueado->token_autentique, $attributes);
            // Log::channel('contratoServico')->error('Resposta bruta da API', ['response' => $responseRaw]);
            // $res = json_decode($responseRaw);
            
            // if (json_last_error() !== JSON_ERROR_NONE) {
            //     Log::channel('contratoServico')->error('Erro no JSON decode', [
            //         'json_error' => json_last_error_msg(),
            //         'raw' => $responseRaw
            //     ]);
            // }



            $orcamento->documento_id_autentique = $res->data->createDocument->id;

            DocumentosAutentique::signById($franqueadoRegiao->franqueado->token_autentique, $orcamento->documento_id_autentique);

            $res2 = json_decode(DocumentosAutentique::listById($franqueadoRegiao->franqueado->token_autentique, $orcamento->documento_id_autentique));

            if(!$res2 || !isset($res2->data)){
                return false;
            }

            $orcamento->contrato_original = $res2->data->document->files->original;
            $orcamento->contrato_assinado = $res2->data->document->files->signed;
            $orcamento->update();
            
            Log::channel('orcamento')->info('Verificando contrato e afiliado', [
                'email franqueado' => $franqueadoRegiao->franqueado ? $franqueadoRegiao->franqueado->email_autentique : 'Não encontrado',
                'email sindico' => $orcamento->condominio && $orcamento->condominio->sindico && $orcamento->condominio->sindico->usuarioApp ? $orcamento->condominio->sindico->usuarioApp->email : 'Não encontrado',
                'email afiliado' => $orcamento->afiliado()->withTrashed()->first() && $orcamento->afiliado()->withTrashed()->first()->usuarioApp ? $orcamento->afiliado()->withTrashed()->first()->usuarioApp->email : 'Não encontrado',
                'email testemunha 1' => $email_testemunha1 ?? 'Não encontrado',
                'email testemunha 2' => $email_testemunha2 ?? 'Não encontrado',
            ]);


            $assinaturas = $res2->data->document->signatures;
            $assinatura_franqueado = new OrcamentoAssinatura();
            $assinatura_franqueado->orcamento_id = $orcamento->id;
            $assinatura_franqueado->documento_id_autentique = $orcamento->documento_id_autentique;
            $assinatura_franqueado->tipo_usuario = "franqueado";
            $assinatura_franqueado->email = $franqueadoRegiao->franqueado->email_autentique;
            $assinatura_franqueado->franqueado_id = $franqueadoRegiao->franqueado->id;
            $assinatura_franqueado->nome = $attributes['document']['name'];
            
            $appId = $orcamento->condominio->sindico->usuario_app_id;
            $emailSindico = UsuarioApp::withTrashed()->findOrFail($appId);

            $assinatura_sindico = new OrcamentoAssinatura();
            $assinatura_sindico->orcamento_id = $orcamento->id;
            $assinatura_sindico->documento_id_autentique = $orcamento->documento_id_autentique;
            $assinatura_sindico->tipo_usuario = "sindico";
            // $assinatura_sindico->email = $orcamento->condominio->sindico->usuarioApp->email;
            $assinatura_sindico->email = $emailSindico->email;
            $assinatura_sindico->sindico_id = $condominio->sindico->id;
            $assinatura_sindico->nome = $attributes['document']['name'];
            $assinatura_sindico->nome_assinante = $orcamento->condominio->sindico->nome;

            $assinatura_afiliado = new OrcamentoAssinatura();
            $assinatura_afiliado->orcamento_id = $orcamento->id;
            $assinatura_afiliado->documento_id_autentique = $orcamento->documento_id_autentique;
            $assinatura_afiliado->tipo_usuario = "afiliado";
            $assinatura_afiliado->email = $orcamento->afiliado()->withTrashed()->first()->usuarioApp->email;
            $assinatura_afiliado->afiliado_id = $orcamento->afiliado_id;
            $assinatura_afiliado->nome = $attributes['document']['name'];
            $assinatura_afiliado->nome_assinante = $orcamento->afiliado()->withTrashed()->first()->razao_social . ".";

            $assinatura_testemunha1 = new OrcamentoAssinatura();
            $assinatura_testemunha1->orcamento_id = $orcamento->id;
            $assinatura_testemunha1->documento_id_autentique = $orcamento->documento_id_autentique;
            $assinatura_testemunha1->tipo_usuario = "testemunha1";
            $assinatura_testemunha1->email = $email_testemunha1;
            $assinatura_testemunha1->nome = $attributes['document']['name'];

            $assinatura_testemunha2 = new OrcamentoAssinatura();
            $assinatura_testemunha2->orcamento_id = $orcamento->id;
            $assinatura_testemunha2->documento_id_autentique = $orcamento->documento_id_autentique;
            $assinatura_testemunha2->tipo_usuario = "testemunha2";
            $assinatura_testemunha2->email = $email_testemunha2;
            $assinatura_testemunha2->nome = $attributes['document']['name'];



            foreach ($assinaturas as $assinatura) {
                if (isset($assinatura->user) && $assinatura->user != null) {
                    if ($assinatura->user->email == $assinatura_franqueado->email) {
                        $assinatura_franqueado->public_id = $assinatura->public_id;
                        $assinatura_franqueado->user_id_autentique = $assinatura->user->id;
                        $assinatura_franqueado->signed = isset($assinatura->signed->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->signed->created_at)) : null;
                    }

                    if ($assinatura->user->email == $assinatura_testemunha1->email) {
                        $assinatura_testemunha1->public_id = $assinatura->public_id;
                        $assinatura_testemunha1->user_id_autentique = $assinatura->user->id;
                    }

                    if ($assinatura->user->email == $assinatura_testemunha2->email) {
                        $assinatura_testemunha2->public_id = $assinatura->public_id;
                        $assinatura_testemunha2->user_id_autentique = $assinatura->user->id;
                    }
                } else {
                    if ($assinatura->name == $assinatura_afiliado->nome_assinante) {
                        $assinatura_afiliado->public_id = $assinatura->public_id;
                        $assinatura_afiliado->short_link = $assinatura->link->short_link;
                    }
                    if ($assinatura->name == $assinatura_sindico->nome_assinante) {
                        $assinatura_sindico->public_id = $assinatura->public_id;
                        $assinatura_sindico->short_link = $assinatura->link->short_link;
                    }
                }
            }

            $orcamento->update();

            $assinatura_franqueado->save();

            $assinatura_sindico->save();

            $assinatura_afiliado->save();
            $assinatura_testemunha1->save();
            $assinatura_testemunha2->save();

            return true;
        } catch(Exception $e){
            Log::error('Erro ao processar ação', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }


}
