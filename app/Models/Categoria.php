<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
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
    protected $table = 'categoria';

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
        'imagem',
        'chave_url',
        'data_cadastro',
        'data_atualizacao',
        'status',
    ];

    /**
     * Get the AfiliadoCategorias for this model.
     *
     * @return App\Models\AfiliadoCategoria
     */
    public function AfiliadoCategorias()
    {
        return $this->hasMany('App\Models\AfiliadoCategoria', 'categoria_id', 'id');
    }

    /**
     * Get the Orcamentos for this model.
     *
     * @return App\Models\Orcamento
     */
    public function Orcamentos()
    {
        return $this->hasMany('App\Models\Orcamento', 'categoria_id', 'id');
    }
}
