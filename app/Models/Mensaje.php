<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $table = 'mensajes';
    protected $primaryKey = 'mensajeid';
    public $timestamps = false;

    protected $fillable = [
        'remitenteid',
        'destinatarioid',
        'asunto',
        'contenido',
        'fechaenvio',
        'leido',
        'respondido',
    ];

    protected $casts = [
        'leido'       => 'boolean',
        'respondido'  => 'boolean',
        'fechaenvio'  => 'datetime',
    ];

    public function remitente()
    {
        return $this->belongsTo(Usuario::class, 'remitenteid', 'usuarioid');
    }

    public function destinatario()
    {
        return $this->belongsTo(Usuario::class, 'destinatarioid', 'usuarioid');
    }

    public function respuestas()
    {
        return $this->hasMany(RespuestaMensaje::class, 'mensajeid', 'mensajeid');
    }
}
