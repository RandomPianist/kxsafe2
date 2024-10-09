<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $id_franqueadora
 * @property int $id_franquia
 * @property int $id_maquina
 * @property int $id_nota
 * @property int $created_at
 * @property int $updated_at
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
        'inicio', 'id_franqueadora', 'id_franquia', 'id_maquina', 'id_nota', 'created_at', 'updated_at'
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
        'id' => 'int', 'id_franqueadora' => 'int', 'id_franquia' => 'int', 'id_maquina' => 'int', 'id_nota' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
