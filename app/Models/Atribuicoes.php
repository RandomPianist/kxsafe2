<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $funcionario_ou_setor_valor
 * @property int    $validade
 * @property int    $obrigatorio
 * @property int    $lixeira
 * @property int    $created_at
 * @property int    $updated_at
 * @property string $produto_ou_referencia_valor
 */
class Atribuicoes extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'atribuicoes';

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
        'funcionario_ou_setor_chave', 'funcionario_ou_setor_valor', 'produto_ou_referencia_chave', 'produto_ou_referencia_valor', 'qtd', 'validade', 'obrigatorio', 'lixeira', 'created_at', 'updated_at'
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
        'id' => 'int', 'funcionario_ou_setor_valor' => 'int', 'produto_ou_referencia_valor' => 'string', 'validade' => 'int', 'obrigatorio' => 'int', 'lixeira' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
