<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $tipo_contribuicao
 * @property int    $tipo
 * @property int    $lixeira
 * @property int    $id_grupo
 * @property int    $id_segmento
 * @property int    $id_matriz
 * @property int    $id_criadora
 * @property int    $created_at
 * @property int    $updated_at
 * @property string $razao_social
 * @property string $nome_fantasia
 * @property string $cnpj
 * @property string $ie
 * @property string $email
 * @property string $telefone
 */
class Empresas extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'empresas';

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
        'razao_social', 'nome_fantasia', 'cnpj', 'ie', 'email', 'telefone', 'tipo_contribuicao', 'tipo', 'royalties', 'lixeira', 'id_grupo', 'id_segmento', 'id_matriz', 'id_criadora', 'created_at', 'updated_at'
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
        'id' => 'int', 'razao_social' => 'string', 'nome_fantasia' => 'string', 'cnpj' => 'string', 'ie' => 'string', 'email' => 'string', 'telefone' => 'string', 'tipo_contribuicao' => 'int', 'tipo' => 'int', 'lixeira' => 'int', 'id_grupo' => 'int', 'id_segmento' => 'int', 'id_matriz' => 'int', 'id_criadora' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
