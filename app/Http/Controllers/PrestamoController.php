<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Services\GoogleDriveService;
use Barryvdh\DomPDF\Facade\Pdf as PDF; // 1. IMPORTA LA LIBRERÍA PDF
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Storage;     // 2. IMPORTA EL LOG PARA MANEJO DE ERRORES

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
            'detallesPrestamo.material:idMaterial,nombreMaterial,marcaMaterial,numeroSerie'
        ]);

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
            'empleado:idUsuario,nombreUsuario,primerApellido',
            'administrador:idUsuario,nombreUsuario,primerApellido',
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
            'idAlmacenOrigen'  => ['nullable', 'exists:almacenes,idAlmacen'],
            'idAlmacenDestino' => ['required', 'exists:almacenes,idAlmacen'],
            'fechaPrestamo'    => ['required', 'date'],
            'fechaEntrega'     => ['nullable', 'date', 'after_or_equal:fechaPrestamo'],
            'detalles'         => ['nullable', 'string'],
            'comentarios'      => ['nullable', 'string'],
        ]);

        $validated['idEmpleado']    = Auth::user()?->idUsuario;
        $validated['estadoentrega'] = 'pendiente';

        if (!$validated['idEmpleado']) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        try {
            $prestamo = Prestamo::create($validated)
                ->load(['almacenOrigen', 'almacenDestino']);

            return response()->json($prestamo, 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al crear préstamo',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /* ========== PUT /prestamos/{id} (actualizar) ========== */
    public function update(Request $request, $id)
    {
        // ... (Tu método update se mantiene igual)
    }

    /* ========== DELETE /prestamos/{id} (eliminar) ========== */
    public function destroy($id)
    {
        // ... (Tu método destroy se mantiene igual)
    }

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

                // Se mantiene la lógica para crear la carpeta en Google Drive
                if (!$prestamo->gdrive_folder_id) {
                    $folderId = $drive->createPrestamoTree($prestamo->idPrestamo);
                    $prestamo->update(['gdrive_folder_id' => $folderId]);
                }
                
                // Se llama al método para generar y guardar el PDF localmente
                $this->generarYGuardarPdfLocalmente($prestamo);

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

    /**
     * Genera un PDF para el préstamo y lo guarda en el almacenamiento público del servidor.
     */
    private function generarYGuardarPdfLocalmente(Prestamo $prestamo)
    {
        try {
            $prestamo->load(['empleado', 'administrador', 'almacenDestino', 'detallesPrestamo.material']);

            $pdf = PDF::loadView('pdf.prestamo', ['prestamo' => $prestamo]);
            $contenidoPdf = $pdf->output();
            
            $nombreArchivo = 'reporte_prestamo_' . $prestamo->idPrestamo . '_' . time() . '.pdf';
            $ruta = 'pdfs/prestamos/' . $nombreArchivo;

            // Guarda el archivo en storage/app/public/pdfs/prestamos/
            Storage::disk('public')->put($ruta, $contenidoPdf);

            // Guarda la ruta en la base de datos
            $prestamo->update(['pdf_path' => $ruta]);

        } catch (\Throwable $e) {
            Log::error('Fallo al generar o guardar PDF para préstamo ID: ' . $prestamo->idPrestamo . ' - Error: ' . $e->getMessage());
        }
    }
   
public function updateComentario(Request $request, $id)
{
    $prestamo = Prestamo::find($id);
    if (!$prestamo) {
        return response()->json(['message' => 'Préstamo no encontrado'], 404);
    }

    $validated = $request->validate([
        'comentarios' => ['required', 'string'],
    ]);

    $prestamo->comentarios = $validated['comentarios'];
    $prestamo->save();

    return response()->json($prestamo, 200);
}
}