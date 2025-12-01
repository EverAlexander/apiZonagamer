<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class consola_complementos extends Model
{
    //
    protected $table = 'consola_complementos';
    protected $primaryKey = 'id';
    protected $fillable = ['id','numero_serie','idComplemento','tiene'];

}
