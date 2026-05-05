<?php

namespace App\Models;

use App\Uteis\StatusPlano;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AfiliadoRegiao extends Model
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
    protected $table = 'afiliado_regiao';

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
        'afiliado_id',
        'regiao_id',
        'plano_assinatura_afiliado_regiao_id',
        'data_pagamento_plano',
        'data_expiracao_plano',
        'data_cadastro',
        'data_atualizacao',
    ];

    /**
     * Get the Afiliado for this model.
     *
     * @return App\Models\Afiliado
     */
    public function Afiliado()
    {
        return $this->belongsTo('App\Models\Afiliado', 'afiliado_id', 'id');
    }

    /**
     * Get the Regiao for this model.
     *
     * @return App\Models\Regiao
     */
    public function Regiao()
    {
        return $this->belongsTo('App\Models\Regiao', 'regiao_id', 'id');
    }

    /**
     * Get the PlanoAssinaturaAfiliadoRegiao for this model.
     *
     * @return App\Models\PlanoAssinaturaAfiliadoRegiao
     */
    public function PlanoAssinaturaAfiliadoRegiao()
    {
        return $this->belongsTo('App\Models\PlanoAssinaturaAfiliadoRegiao', 'plano_assinatura_afiliado_regiao_id', 'id');
    }

    

    public static function tranform($contrato)
	{
		$contrato['regiao'] = $contrato->regiao()->withTrashed()->first();
		$contrato['plano_assinatura'] = PlanoAssinaturaAfiliadoRegiao::withTrashed()->where("id", $contrato->plano_assinatura_afiliado_regiao_id)->first();
		
        if (isset($contrato['plano_assinatura']->data_cancelamento) &&  $contrato['plano_assinatura']->data_cancelamento != null) {
			$planoAssinatura = PlanoAssinaturaAfiliadoRegiao::withTrashed()->where("id", $contrato['plano_assinatura']->id)->first();
			if ($planoAssinatura) {
				$planoAssinatura->statusPlano = StatusPlano::$CANCELADO;
				$planoAssinatura->data_expiracao = null;
				$planoAssinatura->update();
			}

			$contrato['plano_assinatura'] = PlanoAssinaturaAfiliadoRegiao::withTrashed()->where("id", $contrato->plano_assinatura_afiliado_regiao_id)->first();
		} else if (isset($contrato['plano_assinatura']->id)) {
		}
		$contrato['assinatura'] = ContratoAssinatura::withTrashed()->where("afiliado_id", $contrato->afiliado_id)->where("plano_assinatura_afiliado_regiao_id", $contrato->plano_assinatura_afiliado_regiao_id)->orderBy("id", "desc")->first();
		return $contrato;
	}
}
