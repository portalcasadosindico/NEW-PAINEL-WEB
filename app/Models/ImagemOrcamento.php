<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImagemOrcamento extends Model
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
    protected $table = 'imagem_orcamento';

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
        'descricao',
        'caminho_imagem',
        'orcamento_id',
        'data_cadastro',
        'data_atualizacao',
    ];

    /**
     * Get the Orcamento for this model.
     *
     * @return App\Models\Orcamento
     */
    public function Orcamento()
    {
        return $this->belongsTo('App\Models\Orcamento', 'orcamento_id', 'id');
    }

}
