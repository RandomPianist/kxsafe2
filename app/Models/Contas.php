<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $lixeira
 * @property int    $id_banco
 * @property int    $id_empresa
 * @property int    $created_at
 * @property int    $updated_at
 * @property string $descr
 * @property string $agencia
 * @property string $conta
 * @property string $pix
 * @property string $cedente
 * @property string $nossnum
 */
class Contas extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contas';

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
        'descr', 'agencia', 'conta', 'pix', 'cedente', 'nossnum', 'lixeira', 'id_banco', 'id_empresa', 'created_at', 'updated_at'
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
        'id' => 'int', 'descr' => 'string', 'agencia' => 'string', 'conta' => 'string', 'pix' => 'string', 'cedente' => 'string', 'nossnum' => 'string', 'lixeira' => 'int', 'id_banco' => 'int', 'id_empresa' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
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
