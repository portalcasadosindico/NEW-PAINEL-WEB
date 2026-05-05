<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogSendinBlue extends Model
{
    
    /**
     * Variables update_at and created_at.
     */
    const CREATED_AT = 'data_cadastro';
    const UPDATED_AT = 'data_atualizacao';
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logSendinBlue';

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
        'dataCriacao',
        'idOrcamento',
        'retorno',
    ];

    /**
     * Get the Orcamento for this model.
     *
     * @return App\Models\Orcamento
     */
    public function Orcamento()
    {
        return $this->belongsTo('App\Models\Orcamento','idOrcamento','id');
    }
}
