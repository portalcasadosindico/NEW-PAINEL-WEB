<?php
namespace App\Http\Controllers;

use App\Models\Configuracao;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FCM
{
    public static $URL = "https://fcm.googleapis.com/fcm/send";

    private static function getTokenFCM()
    {
        $config = Configuracao::orderBy("id", "desc")->first();
        return $config->api_key_fcm;
    }

    public static function send($token_notification, $titulo, $corpo, $paramns = [])
    {
        $notification = [
            "to" => $token_notification,
            "collapse_key" => "type_a",
            "notification" => [
                "body" => $corpo,
                "title" => $titulo
            ],
            "data" => $paramns
        ];
        try {
            $response = Http::withHeaders([
                'Authorization' => "key=" . self::getTokenFCM(),
                'Content-Type' => "application/json"
            ])->post(self::$URL,
                $notification
            );

            if ($response->failed() || (is_array($response->json()) && ($response->json()['failure'] ?? 0) > 0)) {
                Log::error('Falha ao enviar push FCM', [
                    'titulo' => $titulo,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('Exceção ao enviar push FCM: ' . $e->getMessage(), [
                'titulo' => $titulo,
            ]);
            return null;
        }
    }

    public static function sendToTopic($topico, $titulo, $corpo, $paramns = [])
    {
        $notification = (object) [
            "to" => "/topics/$topico",
            "collapse_key" => "type_a",
            "notification" => [
                "body" => $corpo,
                "title" => $titulo
            ],
            "data" => $paramns
        ];
        $response = Http::withHeaders([
            'Authorization' => "key=".self::getTokenFCM(),
        ])->post(self::$URL, [
            $notification
        ]);
        return $response;
    }
}
