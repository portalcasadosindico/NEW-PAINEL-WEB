<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Afiliado extends Model
{
    use SoftDeletes;

    /**
     * Variables update_at, created_at and deleted_at.
     */
    const CREATED_AT = 'data_cadastro';
    const UPDATED_AT = 'data_atualizacao';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'afiliado';

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
        'razao_social',
        'nome_fantasia',
        'email',
        'telefone',
        'cnpj',
        'cartao_cnpj',
        'inscricao_estadual',
        'inscricao_municipal',
        'cep',
        'estado',
        'cidade',
        'bairro',
        'numero',
        'rua',
        'complemento',
        'ramo_atividade',
        'numero_funcionarios',
        'logo',
        'usuario_app_id',
        'data_cadastro',
        'data_atualizacao',
        'data_contrato',
        'status',
        'asaas_customer_id'

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
     * The relationships that should always be loaded.
     *
     * @var array
     */
    // protected $with = [''];

    /**
     * Get the categorias for the afiliado.
     */
    public function categorias()
    {
        return $this->hasMany('App\Models\AfiliadoCategoria');
    }

    public function regioes()
    {
        return $this->hasMany('App\Models\AfiliadoRegiao');
    }
    /**
     * Get the responsavel for the afiliado.
     */
    public function responsavel()
    {
        return $this->hasOne('App\Models\ResponsavelAfiliado');
    }

    public static function isDataContratoOk($afiliado_id){
        $afiliado = Afiliado::where("id", $afiliado_id)->first();
        $afiliado->responsavelAfiliado = ResponsavelAfiliado::where("afiliado_id", $afiliado->id)->first();

        $errors = [];
        if($afiliado->cnpj==""){
            $errors[] = "O afiliado está sem CNPJ.";
        }

        if($afiliado->cep==""){
            $errors[] = "O afiliado está sem CEP.";
        }

        if($afiliado->rua==""){
            $errors[] = "O afiliado está sem rua.";
        }

        if($afiliado->bairro==""){
            $errors[] = "O afiliado está sem bairro.";
        }

        if($afiliado->cidade==""){
            $errors[] = "O afiliado está sem cidade.";
        }
        
        if($afiliado->estado==""){
            $errors[] = "O afiliado está sem estado.";
        }

        if($afiliado->razao_social==""){
            $errors[] = "O afiliado está sem razão social.";
        }

        if($afiliado->responsavelAfiliado==null || ($afiliado->responsavelAfiliado && $afiliado->responsavelAfiliado->nome=="")){
            $errors[] = "O afiliado está sem nome do responsável.";
        }

        if($afiliado->responsavelAfiliado==null || ($afiliado->responsavelAfiliado && $afiliado->responsavelAfiliado->CPF=="")){
            $errors[] = "O afiliado está sem o CPF do responsável.";
        }

        return [
            "errors" => $errors,
            "status" => count($errors)==0
        ];
    }

}
