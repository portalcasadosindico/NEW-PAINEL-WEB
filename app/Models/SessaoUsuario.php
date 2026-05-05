<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SessaoUsuario extends Model
{
    use SoftDeletes;
    
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
    protected $table = 'sessaoUsuario';

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
        'idUsuario',
        'inicioSessao',
        'fimSessao',
    ];

    /**
     * Get the Bairro for this model.
     *
     * @return App\Models\Bairro
     */
    public function Bairro()
    {
        return $this->belongsTo('App\Models\Bairro','bairro_id','id');
    }

}
