<?php

namespace App\Http\Controllers;

use App\Models\Configuracao;
use App\Models\UsuarioApp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\EnviarEmail;
use App\Http\Controllers\EnviarEmailSendinBlue;

class SenderEmails
{
    public static function getConfig()
    {
        return Configuracao::orderBy("id", "DESC")->first();
    }


    public static function emailConfirmacao($email, $nome, $usuario_id, $tipo = "sendingblue")
    {
        
        $config = self::getConfig();

        $link_android = "";
        $link_ios = "";

        if ($config->link_android) {
            $link_android = "<a style='padding: 8px; border: 1px solid #ededed;' href='{$config->link_android}'>
                                <img src='https://casadosindico.srv.br/assets/playstore.png' />
                             </a>";
        }

        if ($config->link_ios) {
            $link_ios = "<a style='padding: 8px; border: 1px solid #ededed;' href='{$config->link_ios}'>
                            <img src='https://casadosindico.srv.br/assets/appstore.png' />
                            </a>";
        }

        

        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img style='' src='{$config->logo}'>
                                    <h1>Seja bem-vindo a {$config->nome_empresa}</h1>
                                    <h2 style='text-align: center;'>Você está recebendo este e-mail para verificação da conta do aplicativo {$config->nome_empresa}</h2>
                                </div>
                                <p style='text-align: center;' align='center'>
                                    <h5>Por favor, clique no link para verificar sua conta de e-mail</h5>
                                    <a style='padding: 8px; border: 1px solid #ededed; font-size: 21px;' href='https://casadosindico.srv.br/confirmarConta.php?q=" . md5(md5($usuario_id)) . "'>Verificar e-mail</a>
                                </p>
                                <p style='text-align: center;'>
                                    <h3>Baixe o aplicativo em uma das lojas</h3>
                                    $link_android &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    $link_ios
                                    <p>
                                        Se você não lembrar da sua senha, baixe o aplicativo, clique em <b>Já possuo uma conta</b> e depois clique em <b>Esqueci minha senha</b>. Você receberá uma senha nova neste mesmo e-mail.
                                    </p>
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";
                    
        try {
            if ($tipo == "sendingblue") {
                //Sendingblue
                //$sender = new EnviarEmailSendinBlue();
                $sender = new EnviarEmail();
            } else {
                //Server
                $sender = new EnviarEmail();
            }
            
            $res = $sender->send(
                "{$config->nome_empresa} - Confirme o seu e-mail",
                $html,
                $email,
                $nome
            );
            
            return $res;
        } catch (Exception $e) {
            return "ok2";
            return $e;
        }
    }

    public static function emailNotification($titulo, $corpo, $email, $nome, $tipo = "sendingblue")
    {
        $config = self::getConfig();

        try {

            $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #ffffff; width: 300px; margin: auto; max-width: 100%; border: 1px solid #ccc; border-radius: 0px; margin-top: 35px;'>
                                    <div style='text-align: center; background-color: #ededed; width: 100%; max-width: 100%; padding: 16px;'>
                                        <img style='' src='{$config->logo}'>
                                    </div>
                                    <div style='text-align: center; width: 100%; max-width: 100%; padding: 16px;'>
                                        <p>Olá $nome, a Casa do Síndico tem um recado para você. <b>Confira abaixo</b></p>
                                        <h4>$titulo</h4>
                                        <h6 style='text-align: center; margin: 16px;'>$corpo</h6>
                                        <br>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

            if ($tipo == "sendingblue") {
                //Sendingblue
                $sender = new EnviarEmailSendinBlue();
            } else {
                //Server
                $sender = new EnviarEmail();
            }
            $res = $sender->send(
                "{$config->nome_empresa} - " . $titulo,
                $html,
                $email,
                $nome
            );
            return $res;
        } catch (Exception $e) {
            return false;
        }
    }



    public static function enviarEmailAfiliadosNovaSolicitacao($email, $nome_afiliado, $id, $host = "sendinblue")
    {

        $config = self::getConfig();
        $link_android = "";
        $link_ios = "";

        if ($config['link_android']) {
            $link_android = "<a style='padding: 8px; border: 1px solid #ededed;' href='{$config['link_android']}'>
                                <img alt=''src='https://casadosindico.srv.br/assets/playstore.png' />
                             </a>";
        }

        if ($config['link_ios']) {
            $link_ios = "<a style='padding: 8px; border: 1px solid #ededed;' href='{$config['link_ios']}'>
                            <img alt=''src='https://casadosindico.srv.br/assets/appstore.png' />
                            </a>";
        }

        $aux = $email;
        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img alt=''style='' src='{$config->logo}'>
                                    <h1>A solicitação #$id de um síndico acaba de chegar para você e aguarda o seu parecer.</h1>
                                    <h2 style='text-align: center;'>Acesse o aplicativo Casa do Síndico com o e-mail $aux e confira.</h2>
                                </div>
                                <p style='text-align: center;'>
                                    <h3>Baixe o aplicativo em uma das lojas</h3>
                                    $link_android &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    $link_ios
                                    <p>
                                        Se você não lembrar da sua senha, baixe o aplicativo, clique em <b>Já possuo uma conta</b> e depois clique em <b>Esqueci minha senha</b>. Você receberá uma senha nova neste mesmo e-mail.
                                    </p>
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                                
								<p>
                                    Não quero mais receber e-mails. <a href='https://casadosindico.srv.br/descadastrar'>Descadastrar e-mail</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            if ($host == "sendinblue") {
                $sender = new EnviarEmailSendinBlue();
            } else {
                $sender = new EnviarEmail();
            }
            $res = $sender->send(
                "Casa do Síndico - Nova solicitação pelo App",
                $html,
                $email,
                $nome_afiliado,
            );
            return $res;
        } catch (Exception $e) {
            return false;
        }
    }


    public static function enviarEmailAlteracaoStatusOrcamentoAfiliado($context, $email, $nome, $orcamento, $tipo_usuario, $tipo = "sendingblue")
    {
        $config = self::getConfig();
        $aux = $email;

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

        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img style='' src='{$config->logo}'>
                                    <h2>$mensagem.</h2>
                                    <h2 style='text-align: center;'>Acesse o Aplicativo com o e-mail $aux.</h2>
                                </div>
                                <p style='text-align: center;' align='center'>
                                    <h5>Acesse o App e confira</h5>
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            if ($tipo == "sendingblue") {
                //Sendingblue
                $sender = new EnviarEmailSendinBlue();
            } else {
                //Server
                $sender = new EnviarEmail();
            }
            $res = $sender->send(
                $config->nome_empresa . " - Alteração de status da solicitação",
                $html,
                $email,
                $nome
            );
            return $context->successResponse('E-mail enviado com sucesso!', $res);
        } catch (Exception $e) {
            return $context->successResponse('Erro!', $e);
        }
    }
}
