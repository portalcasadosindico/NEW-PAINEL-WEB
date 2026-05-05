<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Condominio extends Model
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
    protected $table = 'condominio';

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
        'cep',
        'bairro',
        'endereco',
        'numero',
        'cidade',
        'estado',
        'complemento',
        'sindico_id',
        'bairro_id',
        'data_cadastro',
        'data_atualizacao',
        'cnpj',
    ];

    /**
     * Get the Sindico for this model.
     *
     * @return App\Models\Sindico
     */
    public function Sindico()
    {
        return $this->belongsTo('App\Models\Sindico', 'sindico_id', 'id');
    }

    /**
     * Get the Regiao for this model.
     *
     * @return App\Models\Bairor
     */
    public function Bairro()
    {
        return $this->belongsTo('App\Models\Bairro', 'bairro_id', 'id');
    }

    public function getRegiao()
    {
        $bairro = Bairro::where("id", $this->bairro_id)->first();
        $cidadesRegiao = null;
        $regiao_id = null;
        if ($bairro->regiao_id == null) {
            $cidadesRegiao = RegiaoFaixaCep::where("cidade_id", $bairro->cidade_id)->first();
            if ($cidadesRegiao) {
                $regiao_id = $cidadesRegiao->regiao_id;
            }
        }

        if (optional($cidadesRegiao)->regiao_id == null) {
            $this->cep = str_replace("-", "", $this->cep);
            $cep = $this->cep;
            $cont = 0;
            do {
                $cont++;
                $regiaoFaixaCep = RegiaoFaixaCep::where("cep", "LIKE", $cep)->orderBy("id", "desc")->first();
                $cep = substr($cep, 0, strlen($this->cep) - $cont);
                if ($cep == "" || strlen($cep) <= 5 || $cont == 6) {
                    break;
                }
            } while (!$regiaoFaixaCep);

            if ($regiaoFaixaCep) {
                $regiao_id = $regiaoFaixaCep->regiao_id;
            }
        }
        return $regiao_id;
    }
}
