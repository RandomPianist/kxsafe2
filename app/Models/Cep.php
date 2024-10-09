<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $created_at
 * @property int    $updated_at
 * @property string $cod
 * @property string $logradouro_tipo
 * @property string $logradouro_tipo_abv
 * @property string $logradouro_descr
 * @property string $logradouro_intervalo_min
 * @property string $logradouro_intervalo_max
 * @property string $cod_ibge_uf
 * @property string $cod_ibge_cidade
 * @property string $cidade
 * @property string $bairro
 * @property string $estado
 * @property string $uf
 */
class Cep extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cep';

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
        'cod', 'logradouro_tipo', 'logradouro_tipo_abv', 'logradouro_descr', 'logradouro_intervalo_min', 'logradouro_intervalo_max', 'cod_ibge_uf', 'cod_ibge_cidade', 'cidade', 'bairro', 'estado', 'uf', 'created_at', 'updated_at'
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
        'id' => 'int', 'cod' => 'string', 'logradouro_tipo' => 'string', 'logradouro_tipo_abv' => 'string', 'logradouro_descr' => 'string', 'logradouro_intervalo_min' => 'string', 'logradouro_intervalo_max' => 'string', 'cod_ibge_uf' => 'string', 'cod_ibge_cidade' => 'string', 'cidade' => 'string', 'bairro' => 'string', 'estado' => 'string', 'uf' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
