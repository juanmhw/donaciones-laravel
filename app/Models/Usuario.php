<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'usuarioid';
    public $timestamps = false;
    protected $fillable = ['email','contrasena','nombre','apellido','telefono','imagenurl','activo','fecharegistro'];

    public function roles() {
        return $this->belongsToMany(Role::class, 'usuariosroles', 'usuarioid', 'rolid', 'usuarioid', 'rolid');
    }

    public function usuariosroles() {
        return $this->hasMany(UsuariosRol::class, 'usuarioid', 'usuarioid');
    }

    public function campanias() {
        return $this->hasMany(Campania::class, 'usuarioidcreador', 'usuarioid');
    }

    public function donaciones() {
        return $this->hasMany(Donacion::class, 'usuarioid', 'usuarioid');
    }

    public function mensajesEnviados() {
        return $this->hasMany(Mensaje::class, 'usuarioorigen', 'usuarioid');
    }

    public function mensajesRecibidos() {
        return $this->hasMany(Mensaje::class, 'usuariodestino', 'usuarioid');
    }
}
