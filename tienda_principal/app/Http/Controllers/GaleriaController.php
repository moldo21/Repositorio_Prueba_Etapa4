<?php

namespace App\Http\Controllers;

use App\Services\MueblesApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GaleriaController extends Controller
{
    private MueblesApiService $mueblesApi;

    public function __construct(MueblesApiService $mueblesApi)
    {
        $this->mueblesApi = $mueblesApi;
    }

    private function esGestorOAdmin(): bool
    {
        return in_array(Session::get('usuario_rol'), ['Administrador', 'Gestor']);
    }

    public function store(Request $request, $productoId)
    {
        if (!$this->esGestorOAdmin()) {
            return back()->with('error', 'No tienes permiso.');
        }

        $request->validate([
            'imagenes.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $resultado = $this->mueblesApi->subirImagenes($productoId, $request->file('imagenes'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al subir imágenes.']);
        }

        return back()->with('success', 'Imágenes añadidas a la galeria.');
    }

    public function destroy($productoId, $galeriaId)
    {
        if (!$this->esGestorOAdmin()) {
            return back()->with('error', 'No tienes permiso.');
        }

        try {
            $resultado = $this->mueblesApi->eliminarImagen($productoId, $galeriaId);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar imagen.']);
        }

        $exitoGaleria = false;
        if (isset($resultado['success'])) {
            $exitoGaleria = $resultado['success'];
        }
        if (!$exitoGaleria) {
            $mensajeError = 'Error al eliminar imagen.';
            if (isset($resultado['message'])) {
                $mensajeError = $resultado['message'];
            }
            return back()->with('error', $mensajeError);
        }

        return back()->with('success', 'Imagen eliminada correctamente.');
    }

    public function setPrincipal($productoId, $galeriaId)
    {
        if (!$this->esGestorOAdmin()) {
            return back()->with('error', 'No tienes permiso.');
        }

        try {
            $this->mueblesApi->setPrincipalImagen($productoId, $galeriaId);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar imagen principal.']);
        }

        return back()->with('success', 'Imagen principal actualizada.');
    }

    public function reordenar(Request $request, $productoId)
    {
        if (!$this->esGestorOAdmin()) {
            return response()->json(['error' => 'No tienes permiso'], 403);
        }

        $request->validate([
            'orden'   => 'required|array',
            'orden.*' => 'integer',
        ]);

        try {
            $resultado = $this->mueblesApi->reordenarGaleria($productoId, $request->orden);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al reordenar'], 500);
        }

        return response()->json(['success' => true]);
    }
}
