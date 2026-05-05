<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cidade extends Model
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
    protected $table = 'cidade';

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
        'estado_id',
        'data_cadastro',
        'data_atualizacao',
    ];

    /**
     * Get the Estado for this model.
     *
     * @return App\Models\Estado
     */
    public function Estado()
    {
        return $this->belongsTo('App\Models\Estado', 'estado_id', 'id');
    }

}
