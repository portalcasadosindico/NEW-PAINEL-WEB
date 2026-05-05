<?php


namespace App\Http\Controllers;

use App\Models\Configuracao;

class SenderNotificacao
{
    public static function getConfig()
    {
        return Configuracao::orderBy("id", "DESC")->first();
    }

    public static function send($token_user, $mensagem, $dado=null)
    {
        $config = self::getConfig();
        FCM::send($token_user, $config->nome_empresa, $mensagem, ["tipo" => "alert", "orcamento"=>$dado]);
    }

    public static function enviarNotificacaoNovoContrato($token_user)
    {
        $config = self::getConfig();
        FCM::send($token_user, $config->nome_empresa, "Seu contrato já está disponível.", ["tipo" => "alert", "update_contrato" => "true"]);
    }

    public static function enviarNotificacaoAlteracaoSolicitacao($token_user, $id, $status, $orcamento)
    {
        $config = self::getConfig();
        FCM::send($token_user, $config->nome_empresa, "Solicitação #$id foi alterada. Status: " . $status, ["tipo" => "alert", "orcamento"=>$orcamento]);
    }

    public static function enviarNotificacaoAlteracaoSolicitacaoContrato($token_user, $id, $status, $orcamento)
    {
        $config = self::getConfig();
        FCM::send($token_user, $config->nome_empresa, "Solicitação #$id está com o contrato disponível. Status: " . $status, ["tipo" => "alert", "orcamento" => $orcamento, "update_contrato" => "true"]);
    }

    public static function enviarNotificacaoNovaSolicitacao($id, $token_user, $nome_condominio)
    {
        $config = self::getConfig();
        FCM::send($token_user, $config->nome_empresa, "Você possui nova solicitação para o condomínio $nome_condominio. #$id", ["tipo" => "navegacao", "texto_botao" => "Ver", "url_botao" => "afiliado/orcamentos"]);
    }

    public static function enviarNotificacaoAlteracaoStatusOrcamento($token_user, $orcamento, $tipo_usuario)
    {
        $config = self::getConfig();
        if ($tipo_usuario == "sindico") {
            if ($orcamento->status_afiliado == 5) {
                $mensagem = "O prestador de serviço da solicitação #" . $orcamento->id . " CONCLUIU o serviço.";
            } elseif ($orcamento->status_afiliado == 9) {
                $mensagem = "O prestador de serviço da solicitação #" . $orcamento->id . " CANCELOU o serviço.";
            }
        } elseif ($tipo_usuario == "afiliado") {
            if ($orcamento->status_sindico == 5) {
                $mensagem = "O síndico do serviço da solicitação #" . $orcamento->id . " CONCLUIU o serviço.";
            } elseif ($orcamento->status_sindico == 9) {
                $mensagem = "O síndico do serviço da solicitação #" . $orcamento->id . " CANCELOU o serviço.";
            }
        }

        FCM::send($token_user, $config->nome_empresa, $mensagem, ["tipo" => "alert", "orcamento" => $orcamento]);
    }

}
