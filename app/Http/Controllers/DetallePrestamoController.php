<?php

// namespace App\Http\Controllers;

// use App\Models\DetallePrestamo;
// use App\Models\Material;
// use App\Models\Prestamo;
// use Illuminate\Http\Request;

// class DetallePrestamoController extends Controller
// {
// public function store(Request $request, $idPrestamo)
// {
//     $prestamo = Prestamo::findOrFail($idPrestamo);

//     $validated = $request->validate([
//         'items'                  => 'required|array|min:1',
//         'items.*.idMaterial'     => 'required|exists:materiales,idMaterial',
//         'items.*.cantidad'       => 'required|integer|min:1',
//     ]);

//     $creados = [];
//     foreach ($validated['items'] as $item) {
//         // ✅ Solo materiales del almacén de origen
//         Material::where('idMaterial', $item['idMaterial'])
//             ->where('idAlmacen', $prestamo->idAlmacenOrigen)
//             ->firstOrFail();

//         $creados[] = DetallePrestamo::create([
//             'idPrestamo' => $prestamo->idPrestamo,
//             'idMaterial' => $item['idMaterial'],
//             'cantidad'   => $item['cantidad'],
//         ]);
//     }

//     return response()->json($creados, 201);
// }
// }



// app/Http/Controllers/DetallePrestamoController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\DetallePrestamo;

class DetallePrestamoController extends Controller
{
    /**
     * POST /detalle-prestamos
     * Crea un detalle de préstamo y devuelve 201 + JSON con el material
     */
   public function store(Request $request): JsonResponse
{
    $data = $request->validate([
        'idPrestamo' => 'required|exists:prestamos,idPrestamo',
        'idMaterial' => 'required|exists:materiales,idMaterial',
        
    ]);

    $detalle = DetallePrestamo::create($data);

    $detalle->load([
        'material:idMaterial,nombreMaterial,marcaMaterial,numeroSerie'
    ]);

    return response()->json($detalle, 201);
}

public function destroy(Request $request): JsonResponse
    {
        $data = $request->validate([
            'idPrestamo' => 'required|exists:prestamos,idPrestamo',
            'idMaterial' => 'required|exists:materiales,idMaterial',
        ]);

        $deleted = DetallePrestamo::where($data)->delete();

        if ($deleted) {
            return response()->json(['message' => 'Detalle eliminado'], 200);
        }
        return response()->json(['message' => 'Detalle no encontrado'], 404);
    }
   public function getOcupados(): JsonResponse
    {
        $detallesActivos = DetallePrestamo::
            whereHas('prestamo', function ($query) {
                $query->where('estadoentrega', 'aceptado');
            })
            // MODIFICADO: Se añade la carga de la relación 'almacenDestino' del préstamo.
            ->with([
                'material', 
                'prestamo.empleado:idUsuario,nombreUsuario,primerApellido', // Carga solo los campos necesarios del empleado
                'prestamo.almacenDestino:idAlmacen,nombreAlmacen' // Carga solo los campos necesarios del almacén
            ])
            ->get();

        $resultado = $detallesActivos->map(function ($detalle) {
            // Salta este detalle si por alguna razón no tiene un préstamo o material asociado.
            if (!$detalle->prestamo || !$detalle->material) {
                return null;
            }

            $empleado = $detalle->prestamo->empleado;
            $almacen = $detalle->prestamo->almacenDestino;

            // MODIFICADO: Se construye el nombre completo del empleado.
            $nombreCompleto = $empleado 
                ? trim($empleado->nombreUsuario . ' ' . $empleado->primerApellido) 
                : 'No asignado';

            return [
                'material' => $detalle->material,
                'usuario' => $nombreCompleto,
                // NUEVO: Se agrega la información del almacén de destino.
                'almacen_destino' => $almacen ? $almacen->nombreAlmacen : 'No especificado',
            ];
        })->filter(); // Elimina cualquier resultado nulo.

        return response()->json($resultado);
    }
}
