<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;

class LogSystem extends Model
{
    use Notifiable;

    /**
     * Variables update_at, created_at.
     */
    const CREATED_AT = 'data_cadastro';
    const UPDATED_AT = 'data_atualizacao';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_system';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [];

  
}
