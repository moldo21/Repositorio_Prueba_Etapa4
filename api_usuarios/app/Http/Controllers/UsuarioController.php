<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUsuarioRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UsuarioController extends Controller
{
    public function index(): JsonResponse
    {
        $usuarios = User::with('rol')->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => UserResource::collection($usuarios),
            'meta'    => [
                'total'        => $usuarios->total(),
                'por_pagina'   => $usuarios->perPage(),
                'pagina_actual' => $usuarios->currentPage(),
                'ultima_pagina' => $usuarios->lastPage(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $usuario = User::with('rol')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => new UserResource($usuario),
        ]);
    }

    public function update(UpdateUsuarioRequest $request, int $id): JsonResponse
    {
        $usuario = User::findOrFail($id);

        $datos = $request->only(['nombre', 'apellidos', 'email', 'rol_id']);

        if ($request->filled('password')) {
            $datos['password'] = bcrypt($request->password);
        }

        $usuario->update($datos);
        $usuario->load('rol');

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente.',
            'data'    => new UserResource($usuario),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $usuario = User::findOrFail($id);

        // No permitir eliminar al propio usuario autenticado
        if (auth()->id() === $usuario->id) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar tu propia cuenta.',
            ], 403);
        }

        $usuario->tokens()->delete();
        $usuario->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente.',
        ]);
    }
}
