<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartaoCnpj extends Model
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
    protected $table = 'cartao_cnpj';

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
        'arquivo',
        'afiliado_id',
        'data_cadastro',
        'data_atualizacao',
        'status',
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

}
