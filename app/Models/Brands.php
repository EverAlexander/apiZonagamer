<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Brands extends Model
{
    //
    protected $table = 'marcas';
    protected $primaryKey = 'id_marca';
    protected $fillable = ['id_marca','nombre','descripcion'];

    public function modelos()
    {
        return $this->hasMany(ModelProduct::class, 'marca', 'id_marca');
    }
}
