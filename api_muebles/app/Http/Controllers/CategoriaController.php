<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Resources\CategoriaResource;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(): JsonResponse
    {
        $categorias = Categoria::withCount('productos')->get();

        return response()->json([
            'success' => true,
            'data'    => CategoriaResource::collection($categorias),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $categoria = Categoria::withCount('productos')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => new CategoriaResource($categoria),
        ]);
    }

    public function store(StoreCategoriaRequest $request): JsonResponse
    {
        $categoria = Categoria::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Categoría creada correctamente.',
            'data'    => new CategoriaResource($categoria),
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'nombre'      => 'sometimes|string|max:255|unique:categorias,nombre,' . $id,
            'descripcion' => 'nullable|string|max:500',
        ]);

        $categoria = Categoria::findOrFail($id);
        $categoria->update($request->only(['nombre', 'descripcion']));

        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada correctamente.',
            'data'    => new CategoriaResource($categoria),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada correctamente.',
        ]);
    }
}
