<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Controls extends Model
{
    //
    protected $table = 'controles_Consola';
    protected $primaryKey = 'numero_serie';
    protected $keyType = 'string';
    protected $fillable = ['numero_serie','descripcion','modelo','color','id_estado','id_ubicacion','observacion','fechaActualizacion'];

    public function versionInfo()
    {
        return $this->belongsTo(ModelProduct::class, 'modelo', 'id_modelo');
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
