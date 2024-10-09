<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $validade
 * @property int    $consumo
 * @property int    $lixeira
 * @property int    $id_categoria
 * @property int    $id_fornecedor
 * @property int    $created_at
 * @property int    $updated_at
 * @property string $descr
 * @property string $referencia
 * @property string $tamanho
 * @property string $ca
 * @property string $detalhes
 * @property string $foto
 * @property string $cod_externo
 * @property string $cod_ou_id
 * @property Date   $validade_ca
 */
class Itens extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'itens';

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
        'descr', 'preco', 'referencia', 'tamanho', 'validade', 'ca', 'validade_ca', 'detalhes', 'foto', 'cod_externo', 'cod_ou_id', 'consumo', 'lixeira', 'id_categoria', 'id_fornecedor', 'created_at', 'updated_at'
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
        'id' => 'int', 'descr' => 'string', 'referencia' => 'string', 'tamanho' => 'string', 'validade' => 'int', 'ca' => 'string', 'validade_ca' => 'date', 'detalhes' => 'string', 'foto' => 'string', 'cod_externo' => 'string', 'cod_ou_id' => 'string', 'consumo' => 'int', 'lixeira' => 'int', 'id_categoria' => 'int', 'id_fornecedor' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'validade_ca', 'created_at', 'updated_at'
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
