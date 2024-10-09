<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $id_li
 * @property int    $id_ni
 * @property int    $id_fornecedor
 * @property int    $created_at
 * @property int    $updated_at
 * @property Date   $data
 * @property string $descr
 */
class Estoque extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'estoque';

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
        'qtd', 'valor', 'data', 'es', 'descr', 'id_li', 'id_ni', 'id_fornecedor', 'created_at', 'updated_at'
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
        'id' => 'int', 'data' => 'date', 'descr' => 'string', 'id_li' => 'int', 'id_ni' => 'int', 'id_fornecedor' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
