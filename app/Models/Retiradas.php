<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int  $id
 * @property int  $id_local
 * @property int  $id_maquina
 * @property int  $id_produto
 * @property int  $id_atribuicao
 * @property int  $id_nota
 * @property int  $id_funcionario
 * @property int  $id_supervisor
 * @property int  $created_at
 * @property int  $updated_at
 * @property Date $data
 */
class Retiradas extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'retiradas';

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
        'qtd', 'data', 'id_local', 'id_maquina', 'id_produto', 'id_atribuicao', 'id_nota', 'id_funcionario', 'id_supervisor', 'created_at', 'updated_at'
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
        'id' => 'int', 'data' => 'date', 'id_local' => 'int', 'id_maquina' => 'int', 'id_produto' => 'int', 'id_atribuicao' => 'int', 'id_nota' => 'int', 'id_funcionario' => 'int', 'id_supervisor' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'data', 'created_at', 'updated_at'
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
