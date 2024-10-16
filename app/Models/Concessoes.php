<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int  $id
 * @property int  $lixeira
 * @property int  $id_de
 * @property int  $id_para
 * @property int  $id_maquina
 * @property int  $id_nota
 * @property int  $created_at
 * @property int  $updated_at
 * @property Date $inicio
 * @property Date $fim
 */
class Concessoes extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'concessoes';

    /**
     * The primary key for the model.
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
        'taxa_inicial', 'inicio', 'fim', 'lixeira', 'id_de', 'id_para', 'id_maquina', 'id_nota', 'created_at', 'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'int', 'inicio' => 'date', 'fim' => 'date', 'lixeira' => 'int', 'id_de' => 'int', 'id_para' => 'int', 'id_maquina' => 'int', 'id_nota' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'inicio', 'fim', 'created_at', 'updated_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    // Scopes...

    // Functions ...

    // Relations ...
}
