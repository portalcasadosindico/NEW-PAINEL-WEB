<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regiao extends Model
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
    protected $table = 'regiao';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**updated
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'descricao',
        'data_cadastro',
        'data_atualizacao',
    ];

}
