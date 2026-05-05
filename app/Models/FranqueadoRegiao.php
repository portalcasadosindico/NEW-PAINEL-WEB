<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranqueadoRegiao extends Model
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
    protected $table = 'franqueado_regiao';

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
        'regiao_id',
        'franqueado_id',
        'usuario_sistema_admin_id',
        'data_inicio_atividade',
        'data_fim_atividade',
        'data_cadastro',
        'data_atualizacao',
        'status',
    ];

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
     * Get the Franqueado for this model.
     *
     * @return App\Models\Franqueado
     */
    public function Franqueado()
    {
        return $this->belongsTo('App\Models\Franqueado', 'franqueado_id', 'id');
    }

    /**
     * Get the UsuarioSistemaAdmin for this model.
     *
     * @return App\Models\UsuarioSistemaAdmin
     */
    public function UsuarioSistemaAdmin()
    {
        return $this->belongsTo('App\Models\UsuarioSistemaAdmin', 'usuario_sistema_admin_id', 'id');
    }
}
