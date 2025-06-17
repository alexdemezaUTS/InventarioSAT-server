<?php

namespace App\Http\Controllers;

use App\Models\PrestamoMaterial;
use Illuminate\Http\Request;

class PrestamoMaterialController extends Controller
{
    public function index() {
        return response()->json(PrestamoMaterial::all());
    }

    public function store(Request $request) {
        $prestamo = PrestamoMaterial::create($request->all());
        return response()->json($prestamo, 201);
    }

    public function show($id) {
        return response()->json(PrestamoMaterial::findOrFail($id));
    }

    public function update(Request $request, $id) {
        $prestamo = PrestamoMaterial::findOrFail($id);
        $prestamo->update($request->all());
        return response()->json($prestamo);
    }

    public function destroy($id) {
        PrestamoMaterial::destroy($id);
        return response()->json(['message' => 'Préstamo eliminado correctamente']);
    }
}
