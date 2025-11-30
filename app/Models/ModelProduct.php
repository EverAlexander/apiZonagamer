<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelProduct extends Model
{
    //
    protected $table = 'modelos';
    protected $primaryKey = 'id_modelo';
    protected $fillable = ['id_modelo','nombre','modelo','marca'];

    public function marcaInfo()
    {
        return $this->belongsTo(Brands::class, 'marca', 'id_marca');
    }
}
