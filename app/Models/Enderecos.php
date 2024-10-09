<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $id_empresa
 * @property int    $id_cep
 * @property int    $created_at
 * @property int    $updated_at
 * @property string $numero
 * @property string $complemento
 * @property string $referencia
 */
class Enderecos extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'enderecos';

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
        'numero', 'complemento', 'referencia', 'id_empresa', 'id_cep', 'created_at', 'updated_at'
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
        'id' => 'int', 'numero' => 'string', 'complemento' => 'string', 'referencia' => 'string', 'id_empresa' => 'int', 'id_cep' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
