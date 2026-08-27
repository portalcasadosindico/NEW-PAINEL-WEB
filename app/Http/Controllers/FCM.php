<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

// Push via FCM. Não fala mais direto com o Google (a API legada `fcm.googleapis.com/fcm/send`
// foi desativada pelo Google em 2024) — chama o endpoint interno da nossa própria API .NET
// (`POST /admin/push/enviar`, AdminPushController), que já usa FCM v1/OAuth2 corretamente via
// FirebaseService. Evita duplicar a lógica de autenticação de service account aqui em PHP.
class FCM
{
    public static function send($token_notification, $titulo, $corpo, $paramns = [])
    {
        if (empty($token_notification)) return false;

        $url = rtrim(env('DOTNET_API_URL'), '/') . '/admin/push/enviar';

        try {
            $response = Http::withHeaders([
                'x-push-admin-token' => env('PUSH_ADMIN_TOKEN'),
                'Content-Type' => 'application/json',
            ])->post($url, [
                'token' => $token_notification,
                'titulo' => $titulo,
                'corpo' => $corpo,
                'data' => (object) $paramns,
            ]);

            if ($response->failed()) {
                Log::error('Falha ao enviar push via API .NET', [
                    'titulo' => $titulo,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Exceção ao enviar push via API .NET: ' . $e->getMessage(), [
                'titulo' => $titulo,
            ]);
            return false;
        }
    }
}
