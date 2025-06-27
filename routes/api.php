<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Auth\RegisterController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/prueba', function () {
    return response()->json(['mensaje' => 'Ruta API funciona']);
});

use App\Http\Controllers\UserController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PrestamoMaterialController;
;

Route::get('/almacenes', [AlmacenController::class, 'index']);
Route::post('/almacenes', [AlmacenController::class, 'store']);
Route::get('/almacenes/{id}', [AlmacenController::class, 'show']);
Route::put('/almacenes/{id}', [AlmacenController::class, 'update']);
Route::delete('/almacenes/{id}', [AlmacenController::class, 'destroy']);



Route::get('/materiales', [MaterialController::class, 'index']);
Route::post('/materiales', [MaterialController::class, 'store']);
Route::get('/materiales/{id}', [MaterialController::class, 'show']);
Route::put('/materiales/{id}', [MaterialController::class, 'update']);
Route::delete('/materiales/{id}', [MaterialController::class, 'destroy']);
Route::get('/materiales/almacen/{id}', [MaterialController::class, 'byAlmacen']);






Route::post('/login', [LoginController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);


// Obtener lista de almacenes
Route::get('/almacenes', function() {
    return response()->json(
        DB::table('almacenes')
          ->select('idAlmacen', 'nombreAlmacen')
          ->get()
    );
});
Route::apiResource('almacenes', \App\Http\Controllers\AlmacenController::class);


Route::post('/register', [RegisterController::class, 'register']);


Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);



use App\Http\Controllers\PrestamoController;

Route::get   ('/prestamos',                [PrestamoController::class, 'index']);

Route::get   ('/prestamos/{id}',           [PrestamoController::class, 'show']);
Route::put   ('/prestamos/{id}',           [PrestamoController::class, 'update']);
Route::delete('/prestamos/{id}',           [PrestamoController::class, 'destroy']);

// Cambiar estado (aceptar, rechazar, devolver…)

// routes/api.php


Route::middleware('auth:sanctum')->group(function () {
    Route::post ('/prestamos',              [PrestamoController::class, 'store']);
    Route::patch('/prestamos/{id}/estado',  [PrestamoController::class, 'updateEstado']);
});


use App\Http\Controllers\DetallePrestamoController;


Route::post('/detalle-prestamos', [DetallePrestamoController::class, 'store']);


