<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
{
    $usuarios = DB::table('users')
        ->leftJoin('almacenes', 'users.idAlmacen', '=', 'almacenes.idAlmacen')
        ->select('users.*', 'almacenes.nombreAlmacen')
        ->get();

    return response()->json($usuarios);
}



    public function store(Request $request) {
        $user = User::create($request->all());
        return response()->json($user, 201);
    }

    public function show($id) {
        return response()->json(User::findOrFail($id));
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json($user);
    }

    public function destroy($id) {
        User::destroy($id);
        return response()->json(['message' => 'Eliminado correctamente']);
    }
}
