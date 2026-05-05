<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranqueadoRegiaoPlanoDisponibilizado extends Model
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
    protected $table = 'franqueado_regiao_plano_disponibilizado';

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
        'franqueado_regiao_id',
        'plano_disponivel_franqueado_id',
        'data_cadastro',
        'data_atualizacao',
    ];

    /**
     * Get the FranqueadoRegiao for this model.
     *
     * @return App\Models\FranqueadoRegiao
     */
    public function FranqueadoRegiao()
    {
        return $this->belongsTo('App\Models\FranqueadoRegiao', 'franqueado_regiao_id', 'id');
    }

    /**
     * Get the PlanoDisponivelFranqueado for this model.
     *
     * @return App\Models\PlanoDisponivelFranqueado
     */
    public function PlanoDisponivelFranqueado()
    {
        return $this->belongsTo('App\Models\PlanoDisponivelFranqueado', 'plano_disponivel_franqueado_id', 'id');
    }

}
