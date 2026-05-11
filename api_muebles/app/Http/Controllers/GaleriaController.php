<?php

namespace App\Http\Controllers;

use App\Http\Resources\GaleriaResource;
use App\Models\Galeria;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GaleriaController extends Controller
{
    public function store(Request $request, int $productoId): JsonResponse
    {
        $request->validate([
            'imagenes'   => 'required|array',
            'imagenes.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $producto     = Producto::findOrFail($productoId);
        $maxOrden    = $producto->galerias()->max('orden');
        $ultimoOrden = 0;
        if ($maxOrden !== null) {
            $ultimoOrden = $maxOrden;
        }
        $nuevasImagenes = [];

        foreach ($request->file('imagenes') as $img) {
            $nombre = 'galeria_' . time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('imagenes'), $nombre);

            $ultimoOrden++;
            $galeria = Galeria::create([
                'producto_id'  => $producto->id,
                'ruta'         => $nombre,
                'es_principal' => false,
                'orden'        => $ultimoOrden,
            ]);
            $nuevasImagenes[] = $galeria;
        }

        return response()->json([
            'success' => true,
            'message' => 'Imágenes añadidas correctamente.',
            'data'    => GaleriaResource::collection(collect($nuevasImagenes)),
        ], 201);
    }

    public function destroy(int $productoId, int $galeriaId): JsonResponse
    {
        $producto = Producto::findOrFail($productoId);

        if ($producto->galerias()->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'El producto debe tener al menos una imagen.',
            ], 422);
        }

        $galeria = Galeria::where('producto_id', $productoId)->where('id', $galeriaId)->firstOrFail();

        $ruta = public_path('imagenes/' . $galeria->ruta);
        if (file_exists($ruta)) {
            unlink($ruta);
        }

        $eraPrincipal = $galeria->es_principal;
        $galeria->delete();

        if ($eraPrincipal) {
            $nueva = $producto->galerias()->orderBy('orden')->first();
            if ($nueva) {
                $nueva->update(['es_principal' => true]);
                $producto->update(['imagen_principal' => $nueva->ruta]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Imagen eliminada correctamente.',
        ]);
    }

    public function setPrincipal(int $productoId, int $galeriaId): JsonResponse
    {
        $producto = Producto::findOrFail($productoId);
        $galeria  = Galeria::where('producto_id', $productoId)->where('id', $galeriaId)->firstOrFail();

        Galeria::where('producto_id', $productoId)->update(['es_principal' => false]);
        $galeria->update(['es_principal' => true]);
        $producto->update(['imagen_principal' => $galeria->ruta]);

        return response()->json([
            'success' => true,
            'message' => 'Imagen principal actualizada.',
            'data'    => new GaleriaResource($galeria),
        ]);
    }

    public function reordenar(Request $request, int $productoId): JsonResponse
    {
        $request->validate([
            'orden'   => 'required|array',
            'orden.*' => 'integer|exists:galerias,id',
        ]);

        Producto::findOrFail($productoId);

        foreach ($request->orden as $indice => $galeriaId) {
            Galeria::where('id', $galeriaId)
                ->where('producto_id', $productoId)
                ->update(['orden' => $indice + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Galería reordenada correctamente.',
        ]);
    }
}
