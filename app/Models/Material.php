<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Almacen;

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
        'color',
        'estatus',
        'estado', // 👈 agregado aquí
        'idAlmacen'
    ];

    // Relación con almacén
    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'idAlmacen', 'idAlmacen');
    }

}
