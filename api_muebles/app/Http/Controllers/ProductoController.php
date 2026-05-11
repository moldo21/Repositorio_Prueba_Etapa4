<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Http\Resources\ProductoResource;
use App\Models\Galeria;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Producto::with(['categoria', 'galerias']);

        // Filtros
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        if ($request->filled('min') && $request->filled('max')) {
            $min = (float) $request->min;
            $max = (float) $request->max;
            if ($min <= $max) {
                $query->whereBetween('precio', [$min, $max]);
            }
        } elseif ($request->filled('min')) {
            $query->where('precio', '>=', (float) $request->min);
        } elseif ($request->filled('max')) {
            $query->where('precio', '<=', (float) $request->max);
        }

        if ($request->filled('color')) {
            $query->where('color_principal', 'LIKE', '%' . $request->color . '%');
        }

        if ($request->filled('busqueda')) {
            $busqueda = $request->busqueda;
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'LIKE', '%' . $busqueda . '%')
                    ->orWhere('descripcion', 'LIKE', '%' . $busqueda . '%');
            });
        }

        if ($request->boolean('destacado')) {
            $query->where('destacado', true);
        }

        // Ordenación
        $orden     = $request->query('orden', 'created_at');
        $direccion = in_array($request->query('dir'), ['asc', 'desc']) ? $request->query('dir') : 'desc';

        match ($orden) {
            'precio' => $query->orderBy('precio', $direccion),
            'nombre' => $query->orderBy('nombre', $direccion),
            'novedad' => $query->orderBy('destacado', 'desc')->orderBy('created_at', 'desc'),
            default  => $query->orderBy('created_at', $direccion),
        };

        // Paginación
        $perPage  = min((int) $request->query('por_pagina', 12), 50);
        $productos = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => ProductoResource::collection($productos),
            'meta'    => [
                'total'         => $productos->total(),
                'por_pagina'    => $productos->perPage(),
                'pagina_actual' => $productos->currentPage(),
                'ultima_pagina' => $productos->lastPage(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $producto = Producto::with(['categoria', 'galerias'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => new ProductoResource($producto),
        ]);
    }

    public function store(StoreProductoRequest $request): JsonResponse
    {
        $datos = $request->validated();
        $nombreImagen = null;

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $nombreImagen = 'producto_' . time() . '_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
            $archivo->move(public_path('imagenes'), $nombreImagen);
        }

        $datos['imagen_principal'] = $nombreImagen;
        $datos['destacado'] = $request->boolean('destacado');

        $producto = Producto::create($datos);

        if ($nombreImagen) {
            Galeria::create([
                'producto_id'  => $producto->id,
                'ruta'         => $nombreImagen,
                'es_principal' => true,
                'orden'        => 1,
            ]);
        }

        $producto->load(['categoria', 'galerias']);

        return response()->json([
            'success' => true,
            'message' => 'Producto creado correctamente.',
            'data'    => new ProductoResource($producto),
        ], 201);
    }

    public function update(UpdateProductoRequest $request, int $id): JsonResponse
    {
        $producto = Producto::findOrFail($id);
        $datos = $request->validated();

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $nombreImagen = 'producto_' . time() . '_' . uniqid() . '.' . $archivo->getClientOriginalExtension();
            $archivo->move(public_path('imagenes'), $nombreImagen);
            $datos['imagen_principal'] = $nombreImagen;
        }

        if ($request->has('destacado')) {
            $datos['destacado'] = $request->boolean('destacado');
        }

        $producto->update($datos);
        $producto->load(['categoria', 'galerias']);

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado correctamente.',
            'data'    => new ProductoResource($producto),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $producto = Producto::findOrFail($id);

        // Eliminar imágenes físicas
        foreach ($producto->galerias as $galeria) {
            $ruta = public_path('imagenes/' . $galeria->ruta);
            if (file_exists($ruta)) {
                unlink($ruta);
            }
        }

        $producto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado correctamente.',
        ]);
    }
}
