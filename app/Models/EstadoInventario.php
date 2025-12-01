<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoInventario extends Model
{
    //
    protected $table = 'estado_inventario';
    protected $primaryKey = 'id_estado';
    protected $fillable = ['id_estado','nombre'];

}
