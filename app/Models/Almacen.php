<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    protected $table = 'almacenes';
    protected $primaryKey = 'idAlmacen';
    public $timestamps = false;

    protected $fillable = ['nombreAlmacen'];
}
