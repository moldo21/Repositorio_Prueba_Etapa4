<?php

namespace App\Http\Controllers;

use App\Services\MueblesApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductosController extends Controller
{
    private MueblesApiService $mueblesApi;

    public function __construct(MueblesApiService $mueblesApi)
    {
        $this->mueblesApi = $mueblesApi;
    }

    private function puedeGestionarMuebles(): bool
    {
        $abilities = Session::get('abilities', []);
        return in_array('muebles.crear', $abilities) || in_array('muebles.editar', $abilities);
    }

    private function esAdmin(): bool
    {
        $rol = Session::get('usuario_rol');
        return $rol === 'Administrador';
    }

    private function esGestorOAdmin(): bool
    {
        $rol = Session::get('usuario_rol');
        return in_array($rol, ['Administrador', 'Gestor']);
    }

    public function index(Request $request)
    {
        $filtros = array_filter([
            'categoria'  => $request->categoria,
            'min'        => $request->min,
            'max'        => $request->max,
            'color'      => $request->color,
            'busqueda'   => $request->busqueda,
            'orden'      => $request->query('orden', 'created_at'),
            'dir'        => $request->query('dir', 'desc'),
        ]);

        $porPagina = (int) $request->cookie('paginacion', 6);
        if ($request->has('paginacion')) {
            $porPagina = (int) $request->paginacion;
            cookie()->queue('paginacion', $porPagina, 60 * 24 * 30);
        }
        $filtros['por_pagina'] = $porPagina;

        try {
            $resultado = $this->mueblesApi->getMuebles($filtros);
            $productos = [];
            if (isset($resultado['data'])) {
                $productos = $resultado['data'];
            }
            $meta = [];
            if (isset($resultado['meta'])) {
                $meta = $resultado['meta'];
            }

            // Validar rango de precios
            if ($request->filled('min') && $request->filled('max') && (float) $request->min > (float) $request->max) {
                return redirect()->route('productos.index', $request->except(['min', 'max']))
                    ->withErrors(['rango_precio' => 'El precio mínimo no puede ser mayor que el máximo.']);
            }

            $categoriasRes = $this->mueblesApi->getCategorias();
            $categorias = [];
            if (isset($categoriasRes['data'])) {
                $categorias = $categoriasRes['data'];
            }
        } catch (\Exception $e) {
            $productos  = [];
            $meta       = [];
            $categorias = [];
        }

        return view('productos.index', compact('productos', 'categorias', 'meta'));
    }

    public function show($id)
    {
        try {
            $resultado = $this->mueblesApi->getMueble($id);
            $producto = null;
            if (isset($resultado['data'])) {
                $producto = $resultado['data'];
            }
        } catch (\Exception $e) {
            abort(404);
        }

        if (!$producto) {
            abort(404);
        }

        return view('productos.show', compact('producto'));
    }

    public function create()
    {
        if (!$this->esGestorOAdmin()) {
            return redirect()->route('productos.index')->with('error', 'No tienes permiso.');
        }

        try {
            $res = $this->mueblesApi->getCategorias();
            $categorias = [];
            if (isset($res['data'])) {
                $categorias = $res['data'];
            }
        } catch (\Exception $e) {
            $categorias = [];
        }

        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        if (!$this->esGestorOAdmin()) {
            return redirect()->route('productos.index')->with('error', 'No tienes permiso.');
        }

        $request->validate([
            'categoria_id'    => 'required',
            'nombre'          => 'required|string|max:80',
            'descripcion'     => 'required|string|max:255',
            'precio'          => 'required|numeric|min:0',
            'stock'           => 'required|integer|min:0',
            'materiales'      => 'nullable|string|max:255',
            'dimensiones'     => 'nullable|string|max:255',
            'color_principal' => 'nullable|string|max:50',
            'imagen'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $resultado = $this->mueblesApi->createMueble(
                $request->except(['imagen', '_token']),
                $request->file('imagen')
            );
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al comunicarse con la API de muebles.'])->withInput();
        }

        $exitoStore = false;
        if (isset($resultado['success'])) {
            $exitoStore = $resultado['success'];
        }
        if (!$exitoStore) {
            $errores = [];
            if (isset($resultado['errors'])) {
                $errores = $resultado['errors'];
            }
            if ($errores) {
                return back()->withErrors($errores)->withInput();
            }
            $mensajeError = 'Error al crear el producto.';
            if (isset($resultado['message'])) {
                $mensajeError = $resultado['message'];
            }
            return back()->withErrors(['error' => $mensajeError])->withInput();
        }

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit($id)
    {
        if (!$this->esGestorOAdmin()) {
            return redirect()->route('productos.index')->with('error', 'No tienes permiso.');
        }

        try {
            $res = $this->mueblesApi->getMueble($id);
            $producto = null;
            if (isset($res['data'])) {
                $producto = $res['data'];
            }
            $catRes = $this->mueblesApi->getCategorias();
            $categorias = [];
            if (isset($catRes['data'])) {
                $categorias = $catRes['data'];
            }
        } catch (\Exception $e) {
            abort(404);
        }

        if (!$producto) {
            abort(404);
        }

        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        if (!$this->esGestorOAdmin()) {
            return redirect()->route('productos.index')->with('error', 'No tienes permiso.');
        }

        $request->validate([
            'categoria_id'    => 'required',
            'nombre'          => 'required|string|max:80',
            'descripcion'     => 'required|string|max:255',
            'precio'          => 'required|numeric|min:0',
            'stock'           => 'required|integer|min:0',
            'materiales'      => 'nullable|string|max:255',
            'dimensiones'     => 'nullable|string|max:255',
            'color_principal' => 'nullable|string|max:50',
        ]);

        try {
            $resultado = $this->mueblesApi->updateMueble(
                $id,
                $request->except(['imagen', '_token', '_method']),
                $request->file('imagen')
            );
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al comunicarse con la API de muebles.'])->withInput();
        }

        $exitoUpdate = false;
        if (isset($resultado['success'])) {
            $exitoUpdate = $resultado['success'];
        }
        if (!$exitoUpdate) {
            $mensajeError = 'Error al actualizar.';
            if (isset($resultado['message'])) {
                $mensajeError = $resultado['message'];
            }
            return back()->withErrors(['error' => $mensajeError])->withInput();
        }

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        if (!$this->esAdmin()) {
            return redirect()->route('productos.index')->with('error', 'No tienes permiso.');
        }

        try {
            $this->mueblesApi->deleteMueble($id);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar el producto.']);
        }

        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente.');
    }
}

