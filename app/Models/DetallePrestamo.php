<?php
// app/Models/DetallePrestamo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetallePrestamo extends Model
{
    protected $table = 'detalle_prestamos';
    protected $primaryKey = 'idDetalle';
    public $timestamps = false;                     // la tabla no tiene created_at / updated_at

    // ⚠️ Agrega aquí “cantidad” si tu tabla la tiene
    protected $fillable = ['idPrestamo', 'idMaterial'];

    // ------------ Relaciones opcionales ------------
    public function prestamo(): BelongsTo
    {
        return $this->belongsTo(Prestamo::class, 'idPrestamo', 'idPrestamo');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'idMaterial', 'idMaterial');
    }
     public function detallesPrestamo(): HasMany
    {
        // eager-load material automáticamente
        return $this->hasMany(DetallePrestamo::class, 'idPrestamo', 'idPrestamo')
                    ->with('material:idMaterial,nombreMaterial,marcaMaterial,numeroSerie');
    }
    
}
