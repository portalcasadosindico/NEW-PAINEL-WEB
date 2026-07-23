<?php

namespace App\Http\Controllers;

use App\Models\Bairro;
use App\Models\Cidade;
use App\Models\Configuracao;
use App\Models\LogSystem;
use App\Models\Regiao;
use App\Uteis\Formatacao;
use App\Uteis\ModusOperandiStatus;
use App\Uteis\Url;
use Exception;
use Illuminate\Http\Request;

class ConfiguracaoController extends Controller
{
    public function alterar_modus_operandi(Request $request)
    {
        try {
            $modo = $request['modo'];
            $config = Configuracao::orderBy("id", "desc")->first();
            if($modo==ModusOperandiStatus::$DEDUB || $modo==ModusOperandiStatus::$PRODUCAO || $modo==ModusOperandiStatus::$MANUTENCAO){
                $config->modus_operandi = $modo;
                $config->update();
                return ["status"=>true];
            } else {
                return ["status"=>false];
            }
        } catch (Exception $e) {
            
        }
    }

    public function logapp()
    {
        $logs = LogSystem::orderBy("id", "desc")->limit(400)->get();
        return view('admin.logs.index', compact('logs'));
    }

}
