<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class MaterialController extends Controller
{
public function index(Request $request)
{
    $idAlmacen = $request->query('idAlmacen');

    $query = DB::table('materiales')
        ->join('almacenes', 'materiales.idAlmacen', '=', 'almacenes.idAlmacen')
        ->select('materiales.*', 'almacenes.nombreAlmacen as nombreAlmacen');

    if ($idAlmacen) {
        $query->where('materiales.idAlmacen', $idAlmacen);
    }

    $materiales = $query->get();
    return response()->json($materiales);
}


    public function store(Request $request)
    {
        $request->validate([
            'nombreMaterial' => 'required|string|max:100',
            'marcaMaterial' => 'required|string|max:100',
            'numeroSerie' => 'nullable|string|max:100',
            'descripcionMaterial' => 'nullable|string',
            'color' => 'nullable|string|max:50',
            'estatus' => 'nullable|string|max:50',
            'estado' => 'nullable|in:libre,ocupado', // 👈 validación para el nuevo campo
            'idAlmacen' => 'required|exists:almacenes,idAlmacen',
        ]);

        $material = Material::create($request->all());
        return response()->json($material, 201);
    }

    public function show($id)
    {
        $material = Material::with('almacen')->findOrFail($id);
        return response()->json($material);
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $request->validate([
            'nombreMaterial' => 'sometimes|string|max:100',
            'marcaMaterial' => 'sometimes|string|max:100',
            'numeroSerie' => 'nullable|string|max:100',
            'descripcionMaterial' => 'nullable|string',
            'color' => 'nullable|string|max:50',
            'estatus' => 'nullable|string|max:50',
            'estado' => 'nullable|in:libre,ocupado', // 👈 validación para actualizar
            'idAlmacen' => 'sometimes|exists:almacenes,idAlmacen',
        ]);

        $material->update($request->all());
        return response()->json($material);
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return response()->json(['message' => 'Material eliminado correctamente']);
    }


}
