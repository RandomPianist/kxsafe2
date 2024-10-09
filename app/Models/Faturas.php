<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int  $id
 * @property int  $ndoc
 * @property int  $parcela
 * @property int  $id_tpdoc
 * @property int  $id_nota
 * @property int  $created_at
 * @property int  $updated_at
 * @property Date $emissao
 * @property Date $vencimento
 */
class Faturas extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'faturas';

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
        'rp', 'valor', 'emissao', 'vencimento', 'ndoc', 'parcela', 'pago', 'id_tpdoc', 'id_nota', 'created_at', 'updated_at'
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
        'id' => 'int', 'emissao' => 'date', 'vencimento' => 'date', 'ndoc' => 'int', 'parcela' => 'int', 'id_tpdoc' => 'int', 'id_nota' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'emissao', 'vencimento', 'created_at', 'updated_at'
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
