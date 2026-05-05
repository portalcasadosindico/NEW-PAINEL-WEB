<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResponsavelPolitica extends Model
{

    use SoftDeletes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'responsavel_politica';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cpf',
        'politica_privacidade_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['data_cadastro'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the PoliticaPrivacidade for this model.
     *
     * @return App\Models\PoliticaPrivacidade
     */
    public function PoliticaPrivacidade()
    {
        return $this->belongsTo('App\Models\PoliticaPrivacidade', 'politica_privacidade_id', 'id');
    }

    /**
     * Set the data_cadastro.
     *
     * @param  string  $value
     * @return void
     */
    public function setDataCadastroAttribute($value)
    {
        $this->attributes['data_cadastro'] = !empty($value) ? \DateTime::createFromFormat('[% date_format %]', $value) : null;
    }

    /**
     * Get data_cadastro in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getDataCadastroAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    }

    /**
     * Get deleted_at in array format
     *
     * @param  string  $value
     * @return array
     */
    // public function getDeletedAtAttribute($value)
    // {
    //     return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    // }

}
