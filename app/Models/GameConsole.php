<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameConsole extends Model
{
    //
    protected $table = 'consolas';
    protected $primaryKey = 'numero_serie';
    protected $keyType = 'string';
    protected $fillable = ['numero_serie','activofijo','id_modelo','color','observacion','id_estado','id_ubicacion'];

    public function versionInfo()
    {
        return $this->belongsTo(ModelProduct::class, 'id_modelo', 'id_modelo');
    }
    public function estadoInfo()
    {
        return $this->belongsTo(EstadoInventario::class, 'id_estado', 'id_estado');
    }
    public function ubicacionInfo()
    {
        return $this->belongsTo(Ubicaciones::class, 'id_ubicacion', 'id_ubicacion');
    }


}
