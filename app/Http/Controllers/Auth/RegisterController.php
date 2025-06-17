<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validación de los datos
        $validator = Validator::make($request->all(), [
            'nombreUsuario' => 'required|string|max:100',
            'primerApellido' => 'required|string|max:100',
            'segundoApellido' => 'nullable|string|max:100',
            'correo' => 'required|email|max:100|unique:users,correo',
            'contrasena' => 'required|string|min:8|max:100',
            'celular' => 'nullable|string|max:20',
            'tipo_usuario' => 'required|string|size:1|in:0,1,2', // Asumo que 1 y 2 son tus tipos válidos
            'idAlmacen' => 'required_if:tipo_usuario,0|nullable|integer|exists:almacenes,idAlmacen'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Creación del usuario
            $user = User::create([
                'nombreUsuario' => $request->nombreUsuario,
                'primerApellido' => $request->primerApellido,
                'segundoApellido' => $request->segundoApellido,
                'correo' => $request->correo,
                'contrasena' => Hash::make($request->contrasena),
                'celular' => $request->celular,
                'tipo_usuario' => $request->tipo_usuario,
                'idAlmacen' => $request->idAlmacen
            ]);

            // Respuesta exitosa (ocultamos la contraseña)
            $userData = $user->toArray();
            unset($userData['contrasena']);

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente',
                'user' => $userData
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}