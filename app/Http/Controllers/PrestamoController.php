<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Services\GoogleDriveService;  

class PrestamoController extends Controller
{
    /* ========== GET /prestamos (lista) ========== */
    public function index(Request $request)
    {
        $query = Prestamo::with([
            'empleado:idUsuario,nombreUsuario,primerApellido,segundoApellido,correo,celular',
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
            'gdrive_folder_id',
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
            'idAlmacenOrigen'  => ['nullable', 'exists:almacenes,idAlmacen'],
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
/* ========== PATCH /prestamos/{id}/estado ========== */
public function updateEstado(Request $request, int $id, GoogleDriveService $drive)
{
    $request->validate([
        'estadoentrega' => ['required', Rule::in(['pendiente','aceptado','rechazado','devuelto'])],
    ]);

    DB::beginTransaction();
    try {
        $prestamo = Prestamo::with('detallesPrestamo.material')->lockForUpdate()->findOrFail($id);

        $prestamo->update([
            'estadoentrega'   => $request->estadoentrega,
            'idAdministrador' => Auth::id(),
        ]);

        if ($request->estadoentrega === 'aceptado') {
            foreach ($prestamo->detallesPrestamo as $d) {
                $d->material?->update(['estado' => 'ocupado']);
            }

            // ✅ Crear carpeta si no existe
            if (!$prestamo->gdrive_folder_id) {
                $folderId = $drive->createPrestamoTree($prestamo->idPrestamo);
                $prestamo->update(['gdrive_folder_id' => $folderId]);
            }

        } elseif (in_array($request->estadoentrega, ['devuelto','rechazado'])) {
            foreach ($prestamo->detallesPrestamo as $d) {
                $d->material?->update(['estado' => 'libre']);
            }
        }

        DB::commit();

        return response()->json(
            $prestamo->fresh(['administrador', 'detallesPrestamo.material']),
            200
        );

    } catch (\Throwable $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Error al cambiar estado',
            'error'   => $e->getMessage()
        ], 500);
    }
}
}
