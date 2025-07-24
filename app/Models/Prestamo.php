<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;

    /** Nombre real de la tabla */
    protected $table = 'prestamos';

    /** Clave primaria personalizada */
    protected $primaryKey = 'idPrestamo';

    /** 
     * La tabla `prestamos` no tiene columnas `created_at` ni `updated_at`, 
     * así que desactivamos los timestamps.
     */
    public $timestamps = false;

    protected $fillable = [
        'idAdministrador',
        'idEmpleado',
        'idAlmacenOrigen',
        'idAlmacenDestino',
        'detalles',
        'comentarios', 
        'fechaPrestamo',
        'fechaEntrega',
        'estadoentrega',
        'gdrive_folder_id',
        'pdf_path',
    ];

    protected $casts = [
        'fechaPrestamo' => 'date',
        'fechaEntrega'  => 'date',
    ];

    /* ---------- Relaciones ---------- */

    // Empleado que solicita el préstamo
    public function empleado()
    {
        return $this->belongsTo(User::class, 'idEmpleado', 'idUsuario');
    }

    // Administrador que aprueba / rechaza
    public function administrador()
    {
        return $this->belongsTo(User::class, 'idAdministrador', 'idUsuario');
    }

    // Almacén origen
    public function almacenOrigen()
    {
        return $this->belongsTo(Almacen::class, 'idAlmacenOrigen', 'idAlmacen');
    }

    // Almacén destino
    public function almacenDestino()
    {
        return $this->belongsTo(Almacen::class, 'idAlmacenDestino', 'idAlmacen');
    }

    // Detalle de materiales (si ya tienes la tabla `detalle_prestamos`)
    public function detallesPrestamo()
    {
        return $this->hasMany(DetallePrestamo::class, 'idPrestamo', 'idPrestamo');
    }
    
}
