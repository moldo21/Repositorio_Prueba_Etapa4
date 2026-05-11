<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

// Rutas públicas (sin autenticación)
Route::get('/muebles',      [ProductoController::class, 'index']);
Route::get('/muebles/{id}', [ProductoController::class, 'show']);
Route::get('/categorias',      [CategoriaController::class, 'index']);
Route::get('/categorias/{id}', [CategoriaController::class, 'show']);

// Rutas protegidas (requieren token válido de api_usuarios)
Route::middleware('token.externo:muebles.crear')->group(function () {
    Route::post('/muebles', [ProductoController::class, 'store']);
    Route::post('/categorias', [CategoriaController::class, 'store']);
});

Route::middleware('token.externo:muebles.editar')->group(function () {
    Route::put('/muebles/{id}',    [ProductoController::class, 'update']);
    Route::patch('/muebles/{id}',  [ProductoController::class, 'update']);
    Route::put('/categorias/{id}',   [CategoriaController::class, 'update']);
    Route::patch('/categorias/{id}', [CategoriaController::class, 'update']);
});

Route::middleware('token.externo:muebles.eliminar')->group(function () {
    Route::delete('/muebles/{id}',    [ProductoController::class, 'destroy']);
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy']);
});

// Gestión de galería (requiere muebles.editar)
Route::middleware('token.externo:muebles.editar')->group(function () {
    Route::post('/muebles/{productoId}/galeria',                           [GaleriaController::class, 'store']);
    Route::delete('/muebles/{productoId}/galeria/{galeriaId}',             [GaleriaController::class, 'destroy']);
    Route::post('/muebles/{productoId}/galeria/{galeriaId}/principal',     [GaleriaController::class, 'setPrincipal']);
    Route::post('/muebles/{productoId}/galeria/reordenar',                 [GaleriaController::class, 'reordenar']);
});
