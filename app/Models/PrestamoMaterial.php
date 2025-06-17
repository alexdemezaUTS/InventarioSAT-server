<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrestamoMaterial extends Model
{
    protected $table = 'prestamos_material';
    protected $primaryKey = 'idPrestamo';
    public $timestamps = false;

    protected $fillable = [
        'idMaterial',
        'idAlmacenOrigen',
        'idAlmacenDestino',
        'idUsuarioEntrega',
        'idUsuarioRecepcion',
        'detallesEntrega',
        'fechaPrestamo',
        'fechaHoraEntrega',
        'estadoentrega',
        'fotoEnvio',
        'fotoRecepcion',
        'fotoDevolucion',
        'fotoRecepcionD'
    ];
}
