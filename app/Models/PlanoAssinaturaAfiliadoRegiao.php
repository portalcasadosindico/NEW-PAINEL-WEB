<?php

namespace App\Models;

use App\Uteis\Asaas;
use App\Uteis\autentique\DocumentosAutentique;
use App\Uteis\StatusAssinaturaPlano;
use App\Uteis\StatusPlano;
use App\Uteis\Util;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PlanoAssinaturaAfiliadoRegiao extends Model
{
    use SoftDeletes;

    /**
     * Variables update_at, created_at
     */
    const CREATED_AT = 'data_cadastro';
    const UPDATED_AT = 'data_atualizacao';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plano_assinatura_afiliado_regiao';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'descricao',
        'valor',
        'valor_comissao',
        'dias_trial',
        'quantidade_meses_vigencia',
        'franqueado_regiao_plano_disponibilizado_id',
        'data_pagamento',
        'data_cancelamento',
        'data_expiracao',
        'data_cadastro',
        'data_atualizacao',
        'statusPlano',
    ];

    /**
     * Get the FranqueadoRegiaoPlanoDisponibilizado for this model.
     *
     * @return App\Models\FranqueadoRegiaoPlanoDisponibilizado
     */
    public function FranqueadoRegiaoPlanoDisponibilizado()
    {
        return $this->belongsTo('App\Models\FranqueadoRegiaoPlanoDisponibilizado', 'franqueado_regiao_plano_disponibilizado_id', 'id');
    }



    public static function updateAssinaturasGeral($id){
        $planoAssinatura = PlanoAssinaturaAfiliadoRegiao::where("id", $id)->first();
        if ($planoAssinatura) {
            $regiaoAfiliado = AfiliadoRegiao::where("plano_assinatura_afiliado_regiao_id", $planoAssinatura->id)->first();
            if ($regiaoAfiliado) {
                $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $regiaoAfiliado->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
                if ($franqueadoRegiao) {
                    $filiadoFranqueadoAsaas = AfiliadoFranqueadoAsaas::where("franqueado_id", $franqueadoRegiao->franqueado_id)->where("afiliado_id", $regiaoAfiliado->afiliado_id)->orderBy("id","desc")->first();
                   // $res = Asaas::getCobrancas($filiadoFranqueadoAsaas->customer_id, Util::getTokenAsaasFranqueadoById($filiadoFranqueadoAsaas->franqueado_id));
                    //self::getCobrancasAsaasByAssinatura($planoAssinatura, $franqueadoRegiao->franqueado_id);
                    self::updateAssinaturasAsaas($planoAssinatura, $franqueadoRegiao->franqueado_id);
                    return self::updateAssinaturas($planoAssinatura, $franqueadoRegiao->franqueado_id);
                }
            }
        }
    }


    public static function getCobrancasAsaasByAssinatura($assinatura, $franqueado_id)
    {
        $tokenAsaas = Util::getTokenAsaasFranqueadoById($franqueado_id);
        $nova_data = null;
        if ($assinatura->asaas_assinatura_id && $tokenAsaas) {
            $assinatura_asaas = Asaas::getCobrancasByAssinatura($assinatura->asaas_assinatura_id, $tokenAsaas);
            if ($assinatura_asaas) {
                $assinatura->data_expiracao = $assinatura_asaas['nextDueDate'];
                if ($assinatura_asaas['status'] == "ACTIVE" && $assinatura['data_cancelamento'] == null) {
                    $statusPlano = 1;
                } else if ($assinatura_asaas['status'] == "EXPIRED" || $assinatura['data_cancelamento'] != null) {
                    $statusPlano = 2; //Cancelado
                    $assinatura->data_expiracao = null;
                }
                $assinatura->statusPlano = $statusPlano;
                $assinatura->update();
                return $assinatura;
            } else {
                $assinatura->statusPlano = StatusPlano::$INADIMPLENTE; //Em processo de cancelamento
                $assinatura->update();
                return $assinatura;
            }
        }

        return "sdfd";
        // if ($nova_data)
        //     $assinatura->data_expiracao = $nova_data;

        // return $assinatura;
    }

    public static function updateAssinaturasAsaas($assinatura, $franqueado_id)
    {
        $tokenAsaas = Util::getTokenAsaasFranqueadoById($franqueado_id);
        $nova_data = null;
        if ($assinatura->asaas_assinatura_id && $tokenAsaas) {
            $assinatura_asaas = Asaas::getAssinaturaById($assinatura->asaas_assinatura_id, $tokenAsaas);
            if ($assinatura_asaas) {
                $assinatura->data_expiracao = $assinatura_asaas['nextDueDate'];
                if ($assinatura_asaas['status'] == "ACTIVE" && $assinatura['data_cancelamento'] == null) {
                    $statusPlano = 1;
                } else if ($assinatura_asaas['status'] == "EXPIRED" || $assinatura['data_cancelamento'] != null) {
                    $statusPlano = 2; //Cancelado
                    $assinatura->data_expiracao = null;
                }
                // alterei aqui douglas
                $assinatura->statusPlano = $statusPlano ?? '';
                $assinatura->update();
                return $assinatura;
            } else {
                $assinatura->statusPlano = StatusPlano::$INADIMPLENTE; //Em processo de cancelamento
                $assinatura->update();
                return $assinatura;
            }
        }

        return "sdfd";
        // if ($nova_data)
        //     $assinatura->data_expiracao = $nova_data;

        // return $assinatura;
    }

    public static function updateAssinaturas($planoAssinatura, $franqueado_id)
    {
        DB::beginTransaction();

        try{
            $tokenAutentique = Util::getTokenAutentique($franqueado_id);
            
            if ($tokenAutentique && $planoAssinatura->documento_id_autentique && ($planoAssinatura->statusPlano==StatusPlano::$ATIVO || $planoAssinatura->statusPlano==StatusPlano::$PENDENTE) && ($planoAssinatura->status==StatusAssinaturaPlano::$AGUARDANDO || $planoAssinatura->status==StatusAssinaturaPlano::$VISUALIZADO)  ) {
                
                $res = json_decode(DocumentosAutentique::listById($tokenAutentique, $planoAssinatura->documento_id_autentique));


                if ($res && !isset($res->errors) && isset($res->data)) {
                    $doc = $res->data->document;
                    if ($planoAssinatura->arquivo_original_autentique == null)
                        $planoAssinatura->arquivo_original_autentique = isset($doc->files->original) ? $doc->files->original : null;

                    if ($planoAssinatura->arquivo_assinado == null)
                        $planoAssinatura->arquivo_assinado = isset($doc->files->signed) ? $doc->files->signed : null;

                    if (isset($doc->signatures))
                        foreach ($doc->signatures as $j => $assinatura) {
                            $assinaturaLocal = ContratoAssinatura::where("public_id", $assinatura->public_id)->where("plano_assinatura_afiliado_regiao_id", $planoAssinatura->id)->first();
                            if ($assinaturaLocal) {
                                $assinaturaLocal->signed = isset($assinatura->signed->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->signed->created_at)) : null;
                                $assinaturaLocal->viewed = isset($assinatura->viewed->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->viewed->created_at)) : null;
                                $assinaturaLocal->rejected = isset($assinatura->rejected->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->rejected->created_at)) : null;
                                $assinaturaLocal->update();
                            }
                        }

                    $assinaturasLocal = ContratoAssinatura::where("plano_assinatura_afiliado_regiao_id", $planoAssinatura->id)->get();

                    $assinadoFranqueado = false;
                    $assinadoTestemunha1 = false;
                    $assinadoTestemunha2 = false;
                    $assinadoAfiliado = false;

                    $assinaturas[$planoAssinatura->id] = [];
                    foreach ($assinaturasLocal as $assLocal) {
                        if ($assLocal->tipo_usuario == "franqueado" && $assLocal->signed) {
                            $assinadoFranqueado = true;
                        } else if ($assLocal->tipo_usuario == "testemunha1" && $assLocal->signed) {
                            $assinadoTestemunha1 = true;
                            $planoAssinatura->status_testemunha1 = StatusAssinaturaPlano::$ASSINADO;
                        } else if ($assLocal->tipo_usuario == "afiliado" && $assLocal->signed) {
                            $assinadoAfiliado = true;
                            $planoAssinatura->status_afiliado = StatusAssinaturaPlano::$ASSINADO;
                        } else if ($assLocal->tipo_usuario == "testemunha2" && $assLocal->signed) {
                            $assinadoTestemunha2 = true;
                            $planoAssinatura->status_testemunha2 = StatusAssinaturaPlano::$ASSINADO;
                        }


                        if ($assLocal->tipo_usuario == "franqueado") {
                            $assinaturas[$planoAssinatura->id]["franqueado"] = $assLocal;
                        } else if ($assLocal->tipo_usuario == "testemunha1") {
                            $assinaturas[$planoAssinatura->id]["testemunha1"] = $assLocal;
                        } else if ($assLocal->tipo_usuario == "afiliado") {
                            $assinaturas[$planoAssinatura->id]["afiliado"] = $assLocal;
                        } else if ($assLocal->tipo_usuario == "testemunha2") {
                            $assinaturas[$planoAssinatura->id]["testemunha2"] = $assLocal;
                        }
                    }

                    if ($assinadoTestemunha1 && $assinadoAfiliado && $assinadoTestemunha2 && $assinadoFranqueado) {
                        $planoAssinatura->status = StatusAssinaturaPlano::$ASSINADO;
                    }

                    try {
                        $planoAssinatura->update();
                        DB::commit();
                        return $assinaturas;
                    } catch (Exception $e) {
                        DB::rollBack();
                        return null;
                    }
                } else {
                    DB::rollBack();
                    return null;
                }
            } else {
                $assinaturasLocal = ContratoAssinatura::where("plano_assinatura_afiliado_regiao_id", $planoAssinatura->id)->get();
                $assinaturas[$planoAssinatura->id] = [];
                foreach ($assinaturasLocal as $assLocal) {
                    if ($assLocal->tipo_usuario == "franqueado") {
                        $assinaturas[$planoAssinatura->id]["franqueado"] = $assLocal;
                    } else if ($assLocal->tipo_usuario == "testemunha1") {
                        $assinaturas[$planoAssinatura->id]["testemunha1"] = $assLocal;
                    } else if ($assLocal->tipo_usuario == "afiliado") {
                        $assinaturas[$planoAssinatura->id]["afiliado"] = $assLocal;
                    } else if ($assLocal->tipo_usuario == "testemunha2") {
                        $assinaturas[$planoAssinatura->id]["testemunha2"] = $assLocal;
                    }
                }
                DB::rollBack();
                return $assinaturas;
            }
        } catch(Exception $e){
            DB::rollBack();
            return null;
        }
    }


}
