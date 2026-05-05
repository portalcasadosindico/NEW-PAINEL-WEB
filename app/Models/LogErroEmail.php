<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogErroEmail extends Model
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
    protected $table = 'logErroEmail';

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
        'dataErro',
        'mensagemErro',
    ];

}
