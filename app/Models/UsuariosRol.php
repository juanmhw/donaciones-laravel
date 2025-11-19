<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UsuariosRol extends Model
{
    protected $table = 'usuariosroles';
    protected $primaryKey = 'usuariorolid';
    public $timestamps = false;
    protected $fillable = ['usuarioid','rolid','fechaasignacion'];

    public function usuario() {
        return $this->belongsTo(Usuario::class, 'usuarioid', 'usuarioid');
    }

    public function rol() {
        return $this->belongsTo(Role::class, 'rolid', 'rolid');
    }
}
