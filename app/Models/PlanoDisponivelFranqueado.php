<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanoDisponivelFranqueado extends Model
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
    protected $table = 'plano_disponivel_franqueado';

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
        'nome',
        'descricao',
        'valor',
        'valor_comissao',
        'dias_trial',
        'regiao_id',
        'quantidade_meses_vigencia',
        'usuario_sistema_admin_id',
        'data_cadastro',
        'data_atualizacao',
        'statusPlano',
    ];

    /**
     * Get the UsuarioSistemaAdmin for this model.
     *
     * @return App\Models\UsuarioSistemaAdmin
     */
    public function UsuarioSistemaAdmin()
    {
        return $this->belongsTo('App\Models\UsuarioSistemaAdmin', 'usuario_sistema_admin_id', 'id');
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

}
