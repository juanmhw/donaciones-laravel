<?php

namespace App\Models\Ext;

use Illuminate\Database\Eloquent\Model;

class ExtEspacio extends Model
{
    protected $table = 'ext_espacios';
    protected $primaryKey = 'espacioid';
    public $timestamps = true;

    protected $fillable = [
        'idexterno',
        'estanteid',
        'codigo_espacio',
        'estado',
    ];

    public function estante()
    {
        return $this->belongsTo(ExtEstante::class, 'estanteid', 'estanteid');
    }
}
