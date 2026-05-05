<?php


namespace App\Http\Controllers;




//Import the PHPMailer class into the global namespace

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
require '../lib/vendor/autoload.php';
require '../lib/vendor/phpmailer/phpmailer/src/PHPMailer.php';

use PHPMailer\PHPMailer\PHPMailer;

class EnviarEmail
{
    private $mail;
    function __construct()
    {
        $this->mail = new PHPMailer();
        $this->mail->Host = 'casadosindico.srv.br';
        $this->mail->Username = 'sender@casadosindico.srv.br';
        $this->mail->Password = 'sB4h3E1Au#Gf';
        $this->mail->isSMTP();
        $this->mail->SMTPDebug = 0;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 587;
        $this->mail->CharSet = "UTF-8";
        $this->mail->SMTPAuth = true;
        $this->mail->setFrom('sender@casadosindico.srv.br', 'Casa do Síndico');
        $this->mail->AddCustomHeader("List-Unsubscribe", "<mailto:adm@casadosindico.srv.br?subject=Cancelar assinatura>, <https://casadosindico.srv.br/contato>");
    }

    public function send($assunto, $corpo, $adress, $name_adress, $reply = 'contato@casadosindico.srv.br', $reply_name = 'Casa do Síndico')
    {
        
        $this->mail->addReplyTo($reply, $reply_name);
        $this->mail->addAddress($adress, $name_adress);
        $this->mail->Subject = $assunto;
        $this->mail->msgHTML($corpo);

        if (!$this->mail->send()) {
            return false;
        } else {
            return true;
        }
    }
}
