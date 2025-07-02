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
}
