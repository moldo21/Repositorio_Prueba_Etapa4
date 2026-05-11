<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Perfil y sesión
    Route::get('/perfil',  [AuthController::class, 'perfil'])->middleware('ability:perfil.ver');
    Route::get('/token-abilities', [AuthController::class, 'tokenAbilities'])->middleware('ability:perfil.ver');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('ability:perfil.ver');

    // Gestión de usuarios (solo admin)
    Route::middleware('ability:usuarios.ver')->group(function () {
        Route::get('/usuarios',          [UsuarioController::class, 'index']);
        Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show']);
    });

    Route::middleware('ability:usuarios.editar')->group(function () {
        Route::put('/usuarios/{usuario}',   [UsuarioController::class, 'update']);
        Route::patch('/usuarios/{usuario}', [UsuarioController::class, 'update']);
    });

    Route::middleware('ability:usuarios.eliminar')->group(function () {
        Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy']);
    });
});
