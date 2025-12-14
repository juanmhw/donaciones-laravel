<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $table = 'mensajes';
    protected $primaryKey = 'mensajeid';
    public $timestamps = false;

    protected $fillable = [
        'conversacionid',
        'usuarioid',
        'asunto',
        'contenido',
        'fechaenvio',
    ];

    protected $casts = [
        'fechaenvio' => 'datetime',
    ];

    public function conversacion()
    {
        return $this->belongsTo(Conversacion::class, 'conversacionid', 'conversacionid');
    }

    public function autor()
    {
        return $this->belongsTo(Usuario::class, 'usuarioid', 'usuarioid');
    }
}
