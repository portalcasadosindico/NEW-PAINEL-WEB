<?php


namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Http;



class EnviarEmailSendinBlue
{
    private $api_url = "https://api.sendinblue.com/v3/";

    public function send($assunto, $corpo, $adress, $name_adress, $reply = 'contato@casadosindico.srv.br', $reply_name = 'Casa do Síndico')
    {
        
        $url = $this->api_url . 'smtp/email';
        try {
            $body = [
                "sender" => [
                    "name" => $reply_name,
                    "email" => $reply
                ],
                "to" => [
                    [
                        "email" => $adress,
                        "name" => $name_adress
                    ]
                ],
                "htmlContent" => $corpo,
                "subject" => $assunto,
                "replyTo" => ["email" => $reply, "name" => $reply_name],
                "headers" => [
                    "List-Unsubscribe" => "<mailto:adm@casadosindico.srv.br?subject=Cancelar assinatura>, <https://casadosindico.srv.br/contato>"
                ],
            ];
            
            $response = Http::withHeaders([
                "content-type" => "application/json",
                "api-key" => "env_key_here",
                "accept" => "application/json",
            ])->post($url, $body);
            return $response;
            if (key_exists("messageId", $response->json())) {
                return true;
            } else {
                return false;
            }
            // return $response->json();
        } catch (Exception $e) {
            return ['errors' => $e->getMessage()];
        }
    }
}
