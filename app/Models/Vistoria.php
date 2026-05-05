<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vistoria extends Model
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
    protected $table = 'vistoria';

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
        'descricao',
        'data_vistoria',
        'data_checkin',
        'data_checkout',
        'latitude_checkin',
        'longitude_checkin',
        'latitude_checkut',
        'longitude_checkout',
        'vistoriador_id',
        'orcamento_id',
        'data_cadastro',
        'data_atualizacao',
        'status',
    ];

    /**
     * Get the Vistoriador for this model.
     *
     * @return App\Models\Vistoriador
     */
    public function Vistoriador()
    {
        return $this->belongsTo('App\Models\Vistoriador', 'vistoriador_id', 'id');
    }

    /**
     * Get the Orcamento for this model.
     *
     * @return App\Models\Orcamento
     */
    public function Orcamento()
    {
        return $this->belongsTo('App\Models\Orcamento', 'orcamento_id', 'id');

    }

}
