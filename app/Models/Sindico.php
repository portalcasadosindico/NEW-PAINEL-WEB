<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sindico extends Model
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
    protected $table = 'sindico';

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
        'numero_documento',
        'CPF',
        'telefone',
        'usuario_app_id',
        'franqueado_id',
        'data_cadastro',
        'data_atualizacao',
        'condominio_id',
    ];

    
    protected $dates = [
        
    ];

    /**
     * Get the UsuarioApp for this model.
     *
     * @return App\Models\UsuarioApp
     */
    public function UsuarioApp()
    {
        return $this->belongsTo('App\Models\UsuarioApp', 'usuario_app_id', 'id');
    }

    /**
     * Get the Franqueado for this model.
     *
     * @return App\Models\Franqueado
     */
    public function Franqueado()
    {
        return $this->belongsTo('App\Models\Franqueado', 'franqueado_id', 'id');
    }

}
