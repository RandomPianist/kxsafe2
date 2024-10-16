<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $senha
 * @property int    $supervisor
 * @property int    $lixeira
 * @property int    $id_empresa
 * @property int    $id_setor
 * @property int    $created_at
 * @property int    $updated_at
 * @property string $nome
 * @property string $cpf
 * @property string $funcao
 * @property string $foto
 * @property string $telefone
 * @property string $email
 * @property string $pis
 * @property string $rosto
 * @property Date   $admissao
 */
class Funcionarios extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'funcionarios';

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
        'nome', 'cpf', 'funcao', 'admissao', 'senha', 'foto', 'telefone', 'email', 'pis', 'supervisor', 'rosto', 'lixeira', 'id_empresa', 'id_setor', 'created_at', 'updated_at'
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
        'id' => 'int', 'nome' => 'string', 'cpf' => 'string', 'funcao' => 'string', 'admissao' => 'date', 'senha' => 'int', 'foto' => 'string', 'telefone' => 'string', 'email' => 'string', 'pis' => 'string', 'supervisor' => 'int', 'rosto' => 'string', 'lixeira' => 'int', 'id_empresa' => 'int', 'id_setor' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'admissao', 'created_at', 'updated_at'
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
