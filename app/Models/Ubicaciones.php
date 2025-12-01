<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubicaciones extends Model
{
    //
    protected $table = 'ubicaciones';
    protected $primaryKey = 'id_ubicacion';
    protected $fillable = ['id_ubicacion','nombre','descripcion'];

}
