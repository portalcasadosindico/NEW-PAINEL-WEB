<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Franqueado extends Authenticatable
{
    use SoftDeletes, Notifiable;

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
    protected $table = 'franqueado';

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
        'email',
        'senha',
        'cnpj',
        'cep',
        'estado',
        'cidade',
        'bairro',
        'rua',
        'inscricao_estadual',
        'inscricao_municipal',
        'cpf_responsavel',
        'rg_responsavel',
        'telefone_responsavel',
        'profissao_responsavel',
        'token_assas_debug',
        'token_assas_producao',
        'data_cadastro',
        'data_atualizacao',
        'email_autentique'
    ];

    public function getAuthPassword()
    {
        return $this->senha;
    }

    /**
     * Validate the password of the user for the Passport password grant.
     *
     * @param  string  $password
     * @return bool
     */
    public function validateForPassportPasswordGrant($password)
    {
        return Hash::check($password, $this->senha);
    }

    public static function isDataContratoOk($franqueado_id)
    {
        $franqueado = Franqueado::where("id", $franqueado_id)->first();

        $errors = [];

        if ($franqueado->nome == "") {
            $errors[] = "A franquia está sem nome.";
        }

        if ($franqueado->nome_responsavel == "") {
            $errors[] = "O franqueado está sem nome do responsável.";
        }

        if ($franqueado->cnpj == "") {
            $errors[] = "O franqueado está sem CNPJ.";
        }

        if ($franqueado->cep == "") {
            $errors[] = "O franqueado está sem CEP.";
        }

        if ($franqueado->rua == "") {
            $errors[] = "O franqueado está sem rua.";
        }

        if ($franqueado->bairro == "") {
            $errors[] = "O franqueado está sem bairro.";
        }

        if ($franqueado->cidade == "") {
            $errors[] = "O franqueado está sem cidade.";
        }

        if ($franqueado->estado == "") {
            $errors[] = "O franqueado está sem estado.";
        }

        if ($franqueado->razao_social == "") {
            $errors[] = "O franqueado está sem razão social.";
        }

        if ($franqueado->cpf_responsavel == "") {
            $errors[] = "O franqueado está sem CPF do responsável.";
        }

        return [
            "errors" => $errors,
            "status" => count($errors) == 0
        ];
    }
    /**
     * Get the FranqueadoRegiao for this model.
     *
     * @return App\Models\FranqueadoRegiao
     */
    public function franqueadoRegiao()
    {
        return $this->hasMany('App\Models\FranqueadoRegiao', 'franqueado_id', 'id');
    }
}
