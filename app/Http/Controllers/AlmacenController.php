<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    public function index() {
       return response()->json(Almacen::select('idAlmacen', 'nombreAlmacen')->get());
    }

    public function store(Request $request) {
        $almacen = Almacen::create($request->all());
        return response()->json($almacen, 201);
    }

    public function show($id) {
        return response()->json(Almacen::findOrFail($id));
    }

    public function update(Request $request, $id) {
        $almacen = Almacen::findOrFail($id);
        $almacen->update($request->all());
        return response()->json($almacen);
    }

    public function destroy($id) {
        Almacen::destroy($id);
        return response()->json(['message' => 'Almacén eliminado correctamente']);
    }

    public function getMaterialesPorAlmacen($idAlmacen)
{
    $materiales = Material::where('idAlmacen', $idAlmacen)->get();
    return response()->json($materiales);
}

public function storeMaterialUsuario(Request $request)
{
    $data = $request->validate([
        'nombreMaterial' => 'required',
        'marcaMaterial' => 'required',
        // otros campos excepto idAlmacen
    ]);
    
    $data['idAlmacen'] = $request->input('idAlmacen'); // o tomarlo del token o request
    
    $material = Material::create($data);
    return response()->json($material, 201);
}

}
