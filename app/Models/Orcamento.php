<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orcamento extends Model
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
    protected $table = 'orcamento';

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
        'afilliado_id',
        'categoria_id',
        'condominio_id',
        'regiao_id',
        'data_cadastro',
        'data_atualizacao',
        'status',
        'status_afiliado',
        'status_sindico',
        'contrato',
        'data_assinatura_sindico',
        'data_assinatura_afiliado',
        'data_assinatura_franqueado',
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
     * Get the Categoria for this model.
     *
     * @return App\Models\Categoria
     */
    public function Categoria()
    {
        return $this->belongsTo('App\Models\Categoria', 'categoria_id', 'id');
    }
    /**
     * Get the Condominio for this model.
     *
     * @return App\Models\Condominio
     */
    public function Condominio()
    {
        // return $this->belongsTo('App\Models\Condominio', 'condominio_id', 'id');
        return $this->belongsTo('App\Models\Condominio', 'condominio_id', 'id')->withTrashed();
    }
    /**
     * Get the Regiao for this model.
     *
     * @return App\Models\Orcamento
     */
    public function Regiao()
    {
        return $this->belongsTo('App\Models\Regiao', 'regiao_id', 'id');
    }

}
