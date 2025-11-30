<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //
    use HasFactory;

    protected $table = 'categorias';
    //protected $primaryKey = 'idCat';
    protected $fillable = ['idCat','nombrecate','descripcion'];
}
