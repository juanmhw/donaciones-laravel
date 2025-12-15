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
        'leido',
    ];

    // relación base
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuarioid', 'usuarioid');
    }

    // ✅ ALIAS PARA CHAT
    public function autor()
    {
        return $this->belongsTo(Usuario::class, 'usuarioid', 'usuarioid');
    }

    public function conversacion()
    {
        return $this->belongsTo(Conversacion::class, 'conversacionid', 'conversacionid');
    }
}

