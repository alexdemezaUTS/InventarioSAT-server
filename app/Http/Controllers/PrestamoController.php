<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PrestamoController extends Controller
{
    /* ========== GET /prestamos (lista) ========== */
    public function index(Request $request)
    {
        $query = Prestamo::with([
            'empleado:idUsuario,nombreUsuario',
            'administrador:idUsuario,nombreUsuario',
            'almacenOrigen:idAlmacen,nombreAlmacen',
            'almacenDestino:idAlmacen,nombreAlmacen',
            'detallesPrestamo.material:idMaterial,nombreMaterial,nombreMaterial,marcaMaterial,numeroSerie'
        ]);

        // Filtros opcionales ?estadoentrega=&idEmpleado=&idAlmacenOrigen=
        if ($request->filled('estadoentrega')) {
            $query->where('estadoentrega', $request->estadoentrega);
        }
        if ($request->filled('idEmpleado')) {
            $query->where('idEmpleado', $request->idEmpleado);
        }
        if ($request->filled('idAlmacenOrigen')) {
            $query->where('idAlmacenOrigen', $request->idAlmacenOrigen);
        }

        $prestamos = $query->orderByDesc('idPrestamo')->get();

        return response()->json($prestamos, 200);
    }

    /* ========== GET /prestamos/{id} (detalle) ========== */
    public function show($id)
    {
        $prestamo = Prestamo::with([
            'empleado:idUsuario,nombreUsuario',
            'administrador:idUsuario,nombreUsuario',
            'almacenOrigen:idAlmacen,nombreAlmacen',
            'almacenDestino:idAlmacen,nombreAlmacen',
            'detallesPrestamo.material:idMaterial,nombreMaterial,marcaMaterial,numeroSerie'
        ])->find($id);

        if (!$prestamo) {
            return response()->json(['message' => 'Préstamo no encontrado'], 404);
        }

        return response()->json($prestamo, 200);
    }

    /* ========== POST /prestamos (crear) ========== */
     public function store(Request $request)
    {
        $validated = $request->validate([
            'idAlmacenOrigen'  => ['required', 'exists:almacenes,idAlmacen'],
            'idAlmacenDestino' => ['required', 'exists:almacenes,idAlmacen'],
            'fechaPrestamo'    => ['required', 'date'],
            'fechaEntrega'     => ['nullable', 'date', 'after_or_equal:fechaPrestamo'],
            'detalles'         => ['nullable', 'string'],
        ]);

        // ← aquí usamos tu PK idUsuario
        $validated['idEmpleado']    = Auth::user()?->idUsuario;
        $validated['estadoentrega'] = 'pendiente';

        if (!$validated['idEmpleado']) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        try {
            $prestamo = Prestamo::create($validated)
                ->load(['almacenOrigen', 'almacenDestino']);

            return response()->json($prestamo, 201);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error al crear préstamo',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    /* ========== PUT /prestamos/{id} (actualizar) ========== */
    public function update(Request $request, $id)
    {
        $prestamo = Prestamo::find($id);
        if (!$prestamo) {
            return response()->json(['message' => 'Préstamo no encontrado'], 404);
        }

        $validated = $request->validate([
            'idAdministrador'  => ['nullable', 'exists:users,idUsuario'],
            'idAlmacenOrigen'  => ['sometimes', 'exists:almacenes,idAlmacen'],
            'idAlmacenDestino' => ['sometimes', 'exists:almacenes,idAlmacen'],
            'fechaPrestamo'    => ['sometimes', 'date'],
            'fechaEntrega'     => ['nullable', 'date', 'after_or_equal:fechaPrestamo'],
            'detalles'         => ['nullable', 'string'],
            'estadoentrega'    => ['sometimes', Rule::in(['pendiente','aceptado','rechazado','devuelto'])],
        ]);

        $prestamo->update($validated);

        return response()->json($prestamo->fresh([
            'empleado:idUsuario,nombre',
            'administrador:idUsuario,nombre'
        ]), 200);
    }

    /* ========== DELETE /prestamos/{id} (eliminar) ========== */
    public function destroy($id)
    {
        $prestamo = Prestamo::find($id);
        if (!$prestamo) {
            return response()->json(['message' => 'Préstamo no encontrado'], 404);
        }

        $prestamo->delete();

        return response()->json(['message' => 'Préstamo eliminado'], 200);
    }

    /* ========== PATCH /prestamos/{id}/estado (cambiar estado) ========== */
       /** PATCH /api/prestamos/{id}/estado (cambiar estado) */
    public function updateEstado(Request $request, $id)
    {
        $request->validate([
            'estadoentrega' => ['required', Rule::in(['pendiente','aceptado','rechazado','devuelto'])],
        ]);

        try {
            $prestamo = Prestamo::findOrFail($id);
            $prestamo->update([
                'estadoentrega'  => $request->estadoentrega,
                'idAdministrador'=> Auth::user()?->idUsuario,
            ]);

            return response()->json($prestamo->fresh(['administrador']), 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error al cambiar estado',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
