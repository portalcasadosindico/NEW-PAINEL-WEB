<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bairro extends Model
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
    protected $table = 'bairro';

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
        'chave',
        'cidade_id',
        'regiao_id',
        'data_cadastro',
        'data_atualizacao'
    ];

    /**
     * Get the Cidade for this model.
     *
     * @return App\Models\Cidade
     */
    public function Cidade()
    {
        return $this->belongsTo('App\Models\Cidade', 'cidade_id', 'id');
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
