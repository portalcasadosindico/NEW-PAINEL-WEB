<?php

namespace App\Models\BO;

use App\Models\AfiliadoOrcamentoInteresse;
use App\Models\Categoria;
use App\Models\ImagemOrcamento;
use App\Models\Orcamento;
use App\Models\OrcamentoAssinatura;
use App\Models\Sindico;
use App\Models\Vistoria;
use App\Models\VistoriaImagem;
use App\Util\Formatacao;
use App\Util\StatusOrcamento;
use App\Util\StatusVistoria;
use App\Util\Validacao;
use Carbon\Carbon;

class OrcamentoBO
{

    
    public static function transform($orcamento)
    {
        return $orcamento;
    }
}
