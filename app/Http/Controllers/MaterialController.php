<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index() {
        return response()->json(Material::all());
    }

    public function store(Request $request) {
        $material = Material::create($request->all());
        return response()->json($material, 201);
    }

    public function show($id) {
        return response()->json(Material::findOrFail($id));
    }

    public function update(Request $request, $id) {
        $material = Material::findOrFail($id);
        $material->update($request->all());
        return response()->json($material);
    }

    public function destroy($id) {
        Material::destroy($id);
        return response()->json(['message' => 'Material eliminado correctamente']);
    }
}

