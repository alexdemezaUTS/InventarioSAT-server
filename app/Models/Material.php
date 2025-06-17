<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materiales';
    protected $primaryKey = 'idMaterial';
    public $timestamps = false;

    protected $fillable = [
    'nombreMaterial', 
    'marcaMaterial', 
    'numeroSerie',
    'descripcionMaterial',
    'idAlmacen'
    ];
}
